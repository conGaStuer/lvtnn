<?php
include "config.php";
header('Content-Type: application/json');

$result = [];

try {
    $key2 = "eG4r0GcoNtRGbO8"; // Replace with your ZaloPay key2
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

    $requestmac = $postdatajson["mac"];

    if (strcmp($mac, $requestmac) != 0) {
        $result["return_code"] = -1;
        $result["return_message"] = "mac not equal";
    } else {
        $datajson = json_decode($postdatajson["data"], true);
        // Update order status in your database
        // Example: updateOrderStatus($datajson["app_trans_id"], "success");

        $result["return_code"] = 1;
        $result["return_message"] = "success";
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

echo json_encode($result);
