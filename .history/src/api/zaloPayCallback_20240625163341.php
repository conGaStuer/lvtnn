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

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Create new order
        $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
        if ($conn->query($sql_create_order) !== TRUE) {
            throw new Exception("Failed to create order: " . $conn->error);
        }

        $orderId = $conn->insert_id;

        // Add event to order_events
        $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
        if ($conn->query($sql_add_event) !== TRUE) {
            throw new Exception("Failed to add event: " . $conn->error);
        }

        // Insert items into chi_tiet_don_hang
        foreach ($items as $item) {
            $maSach = $item['MaSach'];
            $soLuong = $item['SoLuong'];
            $donGia = $item['DonGia'];

            $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
            if ($conn->query($sql_insert_item) !== TRUE) {
                throw new Exception("Failed to add item to order: " . $conn->error);
            }
        }

        // Commit transaction
        $conn->commit();

        $result["return_code"] = 1;
        $result["return_message"] = "Success";
    } catch (Exception $e) {
        // Rollback transaction if an error occurs
        $conn->rollback();
        $result["return_code"] = 0;
        $result["return_message"] = $e->getMessage();
    }

    // Close connection
    $conn->close();
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

echo json_encode($result);
?>