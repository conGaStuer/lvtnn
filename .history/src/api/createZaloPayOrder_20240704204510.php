<?php
include "config.php";
header('Content-Type: application/json');

$config = [
    "appid" => 553,
    "key1" => "9phuAOYhan4urywHTh0ndEXiV3pKHr5Q",
    "key2" => "Iyz2habzyr7AG8SgvoBCbKwKi3UzlLi3",
    "endpoint" => "https://sandbox.zalopay.com.vn/v001/tpe/createorder"
];
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

$order = [
    "app_id" => $config["app_id"],
    "app_time" => round(microtime(true) * 1000),
    "app_trans_id" => date("ymd") . "_" . $transID,
    "app_user" => $userId,
    "item" => $items,
    "embed_data" => '{}',
    "amount" => $amount,
    "description" => "Payment for order #$transID",
    "bank_code" => "zalopayapp",
    "callback_url" => "http://localhost/LVTN/book-store/src/api/zaloPayCallback.php"
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
    echo json_encode([
        'status' => 'success',
        'payment_url' => $result['order_url']
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => $result['return_message']
    ]);
}
?>