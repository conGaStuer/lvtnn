<?php
include "config.php";
header('Content-Type: application/json');

// Log file path
$logFile = 'zaloPayCallback.log';

function logMessage($message)
{
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

logMessage("Callback received");

$result = [];

try {
    $key2 = "eG4r0GcoNtRGbO8";
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);
    logMessage("Post data: " . json_encode($postdatajson));
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

    $requestmac = $postdatajson["mac"];

    if (strcmp($mac, $requestmac) !== 0) {
        $result["return_code"] = -1;
        $result["return_message"] = "Invalid MAC";
        logMessage("Invalid MAC: calculated " . $mac . " but received " . $requestmac);
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
                if ($stmt->affected_rows > 0) {
                    $conn->commit();
                    $result["return_code"] = 1;
                    $result["return_message"] = "Success";
                    logMessage("Order status updated successfully for user: $userId");
                } else {
                    throw new Exception("No orders updated. Check if the order exists and is in 'giohang' status.");
                }
            } else {
                throw new Exception("Failed to execute update query.");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $result["return_code"] = 0;
            $result["return_message"] = $e->getMessage();
            logMessage("Error during transaction: " . $e->getMessage());
        }

        $stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
    logMessage("Exception caught: " . $e->getMessage());
}

logMessage("Response: " . json_encode($result));
echo json_encode($result);
?>