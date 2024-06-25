<?php
include "config.php";
header('Content-Type: application/json');

$config = [
    "app_id" => 2553,
    "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
    "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
    "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
];

// Đọc dữ liệu từ request gửi đến
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra và lấy thông tin từ dữ liệu
if (!isset($data['userId']) || !isset($data['items'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request data'
    ]);
    exit;
}

$userId = $data['userId'];
$items = json_encode($data['items']);

// Tạo mã giao dịch ngẫu nhiên
$transID = rand(0, 1000000);

// Tính tổng số tiền
$amount = 0;
foreach ($data['items'] as $item) {
    $amount += $item['DonGia'] * $item['SoLuong'];
}

// Chuẩn bị dữ liệu cho đơn hàng
$order = [
    "app_id" => $config["app_id"],
    "app_time" => round(microtime(true) * 1000),
    "app_trans_id" => date("ymd") . "_" . $transID,
    "app_user" => $userId,
    "item" => $items,
    "embed_data" => '{}',
    "amount" => $amount,
    "description" => "Payment for order #$transID",
    "bank_code" => "zalopayapp"
];

// Tạo chuỗi dữ liệu để tạo mã hash
$data_string = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"] . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];

// Tạo mã hash
$order["mac"] = hash_hmac("sha256", $data_string, $config["key1"]);

// Gửi yêu cầu tạo đơn hàng tới ZaloPay
$context = stream_context_create([
    "http" => [
        "header" => "Content-type: application/x-www-form-urlencoded\r\n",
        "method" => "POST",
        "content" => http_build_query($order)
    ]
]);

$response = file_get_contents($config["endpoint"], false, $context);
$result = json_decode($response, true);

// Kiểm tra kết quả từ ZaloPay
if ($result['return_code'] == 1) {
    // Nếu thành công, xử lý callback tại đây
    $mac = hash_hmac("sha256", json_encode($result["data"]), $config["key2"]);
    $requestmac = $result["mac"];

    if (strcmp($mac, $requestmac) != 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid MAC'
        ]);
        exit;
    }

    // Xử lý dữ liệu callback
    $datajson = $result["data"];
    $app_trans_id = $datajson["app_trans_id"];
    $userId = $datajson["app_user"];
    $amount = $datajson["amount"];
    $items = $data['items'];

    // Mở kết nối đến cơ sở dữ liệu
    // (Sử dụng $conn từ config.php)

    // Bắt đầu transaction để đảm bảo tính nhất quán trong các lệnh SQL
    $conn->begin_transaction();

    try {
        // Tạo đơn hàng mới
        $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
        if ($conn->query($sql_create_order) !== TRUE) {
            throw new Exception("Failed to create order");
        }

        // Lấy ID của đơn hàng vừa tạo
        $orderId = $conn->insert_id;

        // Thêm sự kiện vào bảng order_events
        $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
        if ($conn->query($sql_add_event) !== TRUE) {
            throw new Exception("Failed to add event");
        }

        // Thêm từng mặt hàng vào chi tiết đơn hàng
        foreach ($items as $item) {
            $maSach = $item['MaSach'];
            $soLuong = $item['SoLuong'];
            $donGia = $item['DonGia'];

            $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
            if ($conn->query($sql_insert_item) !== TRUE) {
                throw new Exception("Failed to add item to order");
            }
        }

        // Commit transaction nếu mọi thứ thành công
        $conn->commit();

        // Trả về thông báo thành công và URL thanh toán
        echo json_encode([
            'status' => 'success',
            'payment_url' => $result['order_url']
        ]);
    } catch (Exception $e) {
        // Rollback transaction nếu có lỗi xảy ra
        $conn->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }

    // Đóng kết nối cơ sở dữ liệu
    $conn->close();
} else {
    // Nếu không thành công, trả về lỗi cho client
    echo json_encode([
        'status' => 'error',
        'message' => $result['return_message']
    ]);
}
?>