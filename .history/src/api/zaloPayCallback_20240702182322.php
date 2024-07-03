<?php
header('Content-Type: application/json');
include "config.php";

$result = [];

try {
    $key2 = "eG4r0GcoNtRGbO8";
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

    $requestmac = $postdatajson["mac"];
    if (strcmp($mac, $requestmac) !== 0) {
        $result["return_code"] = -1;
        $result["return_message"] = "Invalid MAC";
    } else {
        $datajson = json_decode($postdatajson["data"], true);
        $app_trans_id = $datajson["app_trans_id"];
        $userId = $datajson["app_user"];
        $amount = $datajson["amount"];
        $items = json_decode($datajson["item"], true);

        // Gọi file addorder.php để thêm đơn hàng vào hệ thống
        $response = file_get_contents("http://localhost/LVTN/book-store/src/api/addOrder.php?app_trans_id=" . urlencode($app_trans_id) . "&userId=" . urlencode($userId) . "&items=" . urlencode($datajson["item"]));

        // Xử lý kết quả từ addorder.php
        $addOrderResult = json_decode($response, true);

        if ($addOrderResult['return_code'] === 1) {
            $result["return_code"] = 1;
            $result["return_message"] = "Success";
        } else {
            $result["return_code"] = 0;
            $result["return_message"] = "Failed to add order: " . $addOrderResult['return_message'];
        }
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

echo json_encode($result);
?>