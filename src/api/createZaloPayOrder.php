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
    // Nếu thành công, gọi callback tới zalocallback.php
    $callback_data = [
        'app_trans_id' => $order["app_trans_id"],
        'app_user' => $userId,
        'amount' => $amount,
        'items' => $data['items'] // Chuyển toàn bộ dữ liệu items để callback xử lý
    ];

    $callback_context = stream_context_create([
        "http" => [
            "header" => "Content-type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($callback_data)
        ]
    ]);

    // Thay đổi đường dẫn callback tại đây thành địa chỉ thực tế của zalocallback.php trên server của bạn
    $callback_url = 'http://localhost/LVTN/book-store/src/api/zaloPayCallback.php';
    $callback_response = file_get_contents($callback_url, false, $callback_context);

    // Kiểm tra kết quả callback và trả về thông tin cho client
    if ($callback_response === false) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to call callback'
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'payment_url' => $result['order_url']
        ]);
    }
} else {
    // Nếu không thành công, trả về lỗi cho client
    echo json_encode([
        'status' => 'error',
        'message' => $result['return_message']
    ]);
}
?>