<?php
include "config.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$appid = 2553;  // App ID của bạn trên ZaloPay sandbox
$macKey = 'PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL';  // MAC key của bạn trên ZaloPay sandbox
$endpoint = 'https://sandbox.zalopay.com.vn/v001/tpe/createorder';  // Endpoint sandbox của ZaloPay

$apptransid = date("ymd") . "_" . rand(100000, 999999); // Mã giao dịch định danh của ứng dụng
$apptime = round(microtime(true) * 1000); // Unix timestamp in milliseconds

$order = [
    'appid' => $appid,
    'apptransid' => $apptransid,
    'apptime' => $apptime,
    'appuser' => 'demo',
    'amount' => array_reduce($data['items'], function ($sum, $item) {
        return $sum + ($item['DonGia'] * $item['SoLuong']);
    }, 0),
    'description' => 'Demo - Thanh toan don hang #' . $apptransid,
    'item' => json_encode($data['items']),
    'embeddata' => json_encode(["promotioninfo" => "", "merchantinfo" => "embeddata123"]),
    'bankcode' => 'zalopayapp', // Mã ngân hàng hoặc 'zalopayapp' để thanh toán qua ZaloPay
    'callbackurl' => 'http://localhost/LVTN/book-store/src/api/payment/handleZaloPayCallback.php',
];

// Tạo mã MAC
$dataString = $order['appid'] . "|" . $order['apptransid'] . "|" . $order['appuser'] . "|" . $order['amount'] . "|" . $order['apptime'] . "|" . $order['embeddata'] . "|" . $order['item'];
$order['mac'] = hash_hmac('sha256', $dataString, $macKey);

// Gửi yêu cầu tới ZaloPay
$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>