<?php
header('Content-Type: application/json');
include "config.php";

// Logging function
function log_message($message)
{
    file_put_contents('callback_log.txt', date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

// Read callback data
$postdata = file_get_contents('php://input');
log_message("Received callback: $postdata");

try {
    // Decode the JSON data
    $postdatajson = json_decode($postdata, true);

    if (!isset($postdatajson["data"]) || !isset($postdatajson["mac"])) {
        throw new Exception("Invalid callback data");
    }

    // Key2 from ZaloPay, update with your key
    $key2 = $config["key2"];

    // Verify MAC
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);
    $requestmac = $postdatajson["mac"];
    log_message("Computed MAC: $mac, Received MAC: $requestmac");

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
        // Insert order
        $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
        if ($conn->query($sql_create_order) !== TRUE) {
            throw new Exception("Failed to create order: " . $conn->error);
        }

        $orderId = $conn->insert_id;
        log_message("Order created with ID: $orderId");

        // Insert order events
        $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
        if ($conn->query($sql_add_event) !== TRUE) {
            throw new Exception("Failed to add event: " . $conn->error);
        }

        // Insert order items
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
        log_message("Order and items successfully committed to the database");

        // Return success response
        echo json_encode([
            "return_code" => 1,
            "return_message" => "Success"
        ]);

    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        log_message("Transaction rolled back: " . $e->getMessage());
        echo json_encode([
            "return_code" => 0,
            "return_message" => $e->getMessage()
        ]);
    }

    // Close connection
    $conn->close();

} catch (Exception $e) {
    log_message("Error: " . $e->getMessage());
    echo json_encode([
        "return_code" => 0,
        "return_message" => $e->getMessage()
    ]);
}
?>