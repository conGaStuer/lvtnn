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
    if ($result['return_message'] == "Giao dịch thành công") {
        try {
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
            foreach ($data['items'] as $item) {
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

            // Respond with success and payment URL

        } catch (Exception $e) {
            // Rollback transaction on failure
            $conn->rollback();

            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
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

