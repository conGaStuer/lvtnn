<?php
include "config.php";
header('Content-Type: application/json');

$config = [
    "app_id" => 2553,
    "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
    "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
    "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
];

// Xử lý callback từ ZaloPay sau khi thanh toán thành công
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Kiểm tra thanh toán đã thành công
    if (isset($data['returncode']) && $data['returncode'] == 1 && isset($data['returnmessage']) && $data['returnmessage'] == "Giao dịch thành công") {
        try {
            // Lấy thông tin từ dữ liệu callback
            $userId = $data['app_user'];
            $items = json_encode($data['item']);

            // Start transaction
            $conn->begin_transaction();

            // Insert order into database
            $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) 
                                 VALUES ('$userId', 'choduyet', CURDATE())";
            if ($conn->query($sql_create_order) !== TRUE) {
                throw new Exception("Failed to create order");
            }

            // Get the ID of the newly created order
            $orderId = $conn->insert_id;

            // Insert items into order_details table
            foreach ($data['item'] as $item) {
                $maSach = $item['MaSach'];
                $soLuong = $item['SoLuong'];
                $donGia = $item['DonGia'];

                $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) 
                                    VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
                if ($conn->query($sql_insert_item) !== TRUE) {
                    throw new Exception("Failed to add item to order");
                }
            }

            // Commit transaction if all queries succeed
            $conn->commit();

            // Phản hồi ZaloPay với mã HTTP 200 OK để xác nhận đã nhận được callback và xử lý thành công
            http_response_code(200);
            echo "Callback processed successfully.";
            exit;
        } catch (Exception $e) {
            // Rollback transaction on failure
            $conn->rollback();

            // Phản hồi lỗi về ZaloPay nếu có lỗi trong quá trình thêm vào cơ sở dữ liệu
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    } else {
        // Phản hồi lỗi về ZaloPay nếu thanh toán không thành công
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Payment was not successful'
        ]);
        exit;
    }
}

// Xử lý yêu cầu tạo giao dịch từ client
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['userId']) || !isset($data['items'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request data'
    ]);
    exit;
}

$userId = $data['userId'];
$items = json_encode($data['items']);

$transID = rand(0, 1000000);
$amount = 0;
foreach ($data['items'] as $item) {
    $amount += $item['DonGia'] * $item['SoLuong'];
}
$embeddata = [
    "redirecturl" => "true"
];
$order = [
    "app_id" => $config["app_id"],
    "app_time" => round(microtime(true) * 1000),
    "app_trans_id" => date("ymd") . "_" . $transID,
    "app_user" => $userId,
    "item" => $items,
    "embed_data" => json_encode($embeddata, JSON_UNESCAPED_UNICODE),
    "amount" => $amount,
    "description" => "Payment for order #$transID",
    "bank_code" => "zalopayapp",
    "callback_url" => "https://dc6b-113-172-127-251.ngrok-free.app/LVTN/book-store/src/api/zaloPayCallback.php"
];

$data_string = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
    . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];

$order["mac"] = hash_hmac("sha256", $data_string, $config["key1"]);

$context = stream_context_create([
    "http" => [
        "header" => "Content-type: application/x-www-form-urlencoded\r\n",
        "method" => "POST",
        "content" => http_build_query($order)
    ]
]);

$response = file_get_contents($config["endpoint"], false, $context);
$result = json_decode($response, true);
if ($result['return_code'] == 1) {
    // ZaloPay transaction created successfully
    echo json_encode([
        'status' => 'success',
        'payment_url' => $result['order_url']
    ]);
} else {
    // ZaloPay transaction creation failed
    echo json_encode([
        'status' => 'error',
        'message' => $result['return_message']
    ]);
}
?>
