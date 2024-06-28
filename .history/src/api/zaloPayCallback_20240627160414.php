<?php
include "config.php";
header('Content-Type: application/json');

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
        $items = $datajson["items"];

        $conn->begin_transaction();

        try {
            $sql_update_order = "UPDATE don_dat_hang SET trangthai = 'choduyet' WHERE maND = ? AND trangthai = 'giohang'";
            $stmt = $conn->prepare($sql_update_order);
            $stmt->bind_param("s", $userId);

            if ($stmt->execute()) {
                $conn->commit();
                $result["return_code"] = 1;
                $result["return_message"] = "Success";
            } else {
                throw new Exception("Failed to update order status");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $result["return_code"] = 0;
            $result["return_message"] = $e->getMessage();
        }

        $stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

echo json_encode($result);
?>