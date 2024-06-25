<?php
include "config.php";
header('Content-Type: application/json');

$config = [
    "app_id" => 2553,
    "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
    "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
    "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
];

$data = json_decode(file_get_contents('php://input'), true);
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
    "bank_code" => "zalopayapp"
];

$data_string = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"] . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
$order["mac"] = hash_hmac("sha256", $data_string, $config["key1"]);

$context = stream_context_create([
    "http" => [
        "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
        "method" => "POST",
        "content" => http_build_query($order)
    ]
]);

$response = file_get_contents($config["endpoint"], false, $context);
$result = json_decode($response, true);

if ($result['return_code'] == 1) {
    echo json_encode([
        "status" => "success",
        "payment_url" => $result['order_url']
    ]);
} else {
    echo json_encode([
        "status" => "fail",
        "message" => $result['return_message']
    ]);
}
