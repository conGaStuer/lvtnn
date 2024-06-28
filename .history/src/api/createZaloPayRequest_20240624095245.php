<?php
$appid = 2553;  // App ID của bạn trên ZaloPay sandbox
$macKey = 'PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL';  // MAC key của bạn trên ZaloPay sandbox
$endpoint = 'https://sandbox.zalopay.com.vn/v001/tpe/createorder';  // Endpoint sandbox của ZaloPay

// Dữ liệu đơn hàng mẫu
$order = [
    'appid' => $appid,
    'apptransid' => '240624_09501900228', // Mã giao dịch định danh của ứng dụng
    'apptime' => 1719197419432, // Unix timestamp in milliseconds
    'appuser' => 'demo',
    'amount' => 1000,
    'description' => 'Demo - Thanh toan don hang #<ORDERID>',
    'item' => '[{"itemid":"knb","itemname":"kim nguyen bao","itemprice":198400,"itemquantity":1}]',
    'embeddata' => '{"promotioninfo":"","merchantinfo":"embeddata123"}',
    'bankcode' => 'zalopayapp', // Mã ngân hàng hoặc 'zalopayapp' để thanh toán qua ZaloPay
    'callbackurl' => 'http://localhost/LVTN/book-store/src/api/payment/handleZaloPayCallback.php',
];

// Tạo mã MAC
$data = $order['appid'] . "|" . $order['apptransid'] . "|" . $order['appuser'] . "|" . $order['amount'] . "|" . $order['apptime'] . "|" . $order['embeddata'] . "|" . $order['item'];
$order['mac'] = hash_hmac('sha256', $data, $macKey);

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