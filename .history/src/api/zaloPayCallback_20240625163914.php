<?php
header('Content-Type: application/json');
include "config.php";

$result = [];

try {
    // Read callback data
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);

    // Validate callback data
    if (!isset($postdatajson["data"]) || !isset($postdatajson["mac"])) {
        throw new Exception("Invalid callback data");
    }

    // Key2 from ZaloPay
    $key2 = $config["key2"];

    // Verify MAC
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);
    $requestmac = $postdatajson["mac"];

    if (strcmp($mac, $requestmac) != 0) {
        throw new Exception("Invalid MAC");
    }

    // Process callback data
    $datajson = json_decode($postdatajson["data"], true);
    $app_trans_id = $datajson["app_trans_id"];
    $userId = $datajson["app_user"];
    $amount = $datajson["amount"];
    $items = json_decode($datajson["item"], true);

    // Call the addOrder.php script to insert the order into the database
    $orderData = [
        'userId' => $userId,
        'items' => $items
    ];

    $order_context = stream_context_create([
        "http" => [
            "header" => "Content-type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($orderData)
        ]
    ]);

    // Replace with the actual URL of your addOrder.php script
    $order_url = 'http://localhost/LVTN/book-store/src/api/addOrder.php';
    $order_response = file_get_contents($order_url, false, $order_context);
    $order_result = json_decode($order_response, true);

    if ($order_result['status'] == 'success') {
        $result["return_code"] = 1;
        $result["return_message"] = "Success";
    } else {
        throw new Exception("Failed to add order: " . $order_result['message']);
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

echo json_encode($result);
?>