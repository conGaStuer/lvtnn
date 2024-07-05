<?php
include "config.php";
header('Content-Type: application/json');
$config = [
    "app_id" => 2553,
    "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
    "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
    "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
];

function generateOrder($orderDetails)
{
    global $config;

    $embeddata = [
        "merchantinfo" => "embeddata123"
    ];
    $items = array_map(function ($item) {
        return [
            "itemid" => $item["MaSach"],
            "itemname" => $item["TenSach"],
            "itemprice" => $item["DonGia"],
            "itemquantity" => $item["SoLuong"]
        ];
    }, $orderDetails["items"]);

    $order = [
        "appid" => $config["appid"],
        "apptime" => round(microtime(true) * 1000), // milliseconds
        "apptransid" => date("ymd") . "_" . uniqid(), // transaction id
        "appuser" => $orderDetails["userId"],
        "item" => json_encode($items, JSON_UNESCAPED_UNICODE),
        "embeddata" => json_encode($embeddata, JSON_UNESCAPED_UNICODE),
        "amount" => array_reduce($items, function ($sum, $item) {
            return $sum + ($item["itemprice"] * $item["itemquantity"]);
        }, 0),
        "description" => "ZaloPay Intergration Demo",
        "bankcode" => "zalopayapp"
    ];

    $data = $order["appid"] . "|" . $order["apptransid"] . "|" . $order["appuser"] . "|" . $order["amount"]
        . "|" . $order["apptime"] . "|" . $order["embeddata"] . "|" . $order["item"];
    $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

    $context = stream_context_create([
        "http" => [
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($order)
        ]
    ]);

    $resp = file_get_contents($config["endpoint"], false, $context);
    return json_decode($resp, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $result = generateOrder($input);

    if ($result["return_code"] == 1) {
        echo json_encode([
            "status" => "success",
            "payment_url" => $result["order_url"]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to create ZaloPay order"
        ]);
    }
}
?>