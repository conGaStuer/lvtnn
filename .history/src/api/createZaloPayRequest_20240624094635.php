<?php
include "config.php";

// Lấy dữ liệu từ request POST
$data = json_decode(file_get_contents("php://input"), true);
$amount = $data['amount'];
$description = $data['description'];

// Cấu hình thông tin Zalo Pay
$appid = 'YOUR_APP_ID';  // Thay YOUR_APP_ID bằng appid của bạn
$macKey = 'YOUR_MAC_KEY';  // Thay YOUR_MAC_KEY bằng macKey của bạn
$endpoint = 'https://sandbox.zalopay.com.vn/v001/tpe/createorder';  // Sử dụng endpoint sandbox hoặc production tùy theo môi trường

$order = [
    'appid' => $appid,
    'apptransid' => date("Ymd") . "_" . rand(1, 100000), // Mã giao dịch định danh của ứng dụng
    'apptime' => round(microtime(true) * 1000), // Unix timestamp in milliseconds
    'appuser' => 'demo',
    'amount' => $amount,
    'description' => $description,
    'item' => '[]',
    'embeddata' => '{}',
    'bankcode' => 'zalopayapp',
    'callbackurl' => 'http://localhost/LVTN/book-store/src/api/payment/handleZaloPayCallback.php',
];

$data = $order['appid'] . "|" . $order['apptransid'] . "|" . $order['appuser'] . "|" . $order['amount'] . "|" . $order['apptime'] . "|" . $order['embeddata'] . "|" . $order['item'];
$order['mac'] = hash_hmac('sha256', $data, $macKey);

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>