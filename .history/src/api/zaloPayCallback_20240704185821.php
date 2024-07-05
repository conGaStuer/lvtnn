<?php
header('Content-Type: application/json');
include "config.php";

$result = [];

try {
    // Your ZaloPay key for hashing
    $key2 = "eG4r0GcoNtRGbO8";

    // Get the raw POST data
    $postdata = file_get_contents('php://input');

    // Decode the JSON data received
    $postdatajson = json_decode($postdata, true);

    // Validate required fields
    if (!isset($postdatajson['data']) || !isset($postdatajson['mac'])) {
        throw new Exception("Invalid callback data");
    }

    // Verify the integrity of the callback using HMAC
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);
    if (strcmp($mac, $postdatajson["mac"]) !== 0) {
        throw new Exception("Invalid MAC");
    }

    // Decode the inner JSON data
    $datajson = json_decode($postdatajson["data"], true);

    // Extract necessary data
    $app_trans_id = $datajson["app_trans_id"];
    $userId = $datajson["app_user"];
    $amount = $datajson["amount"];
    $items = $datajson["items"];

    // Database operations
    $conn->beginTransaction();

    // Insert order into database
    $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) 
                         VALUES ('$userId', 'choduyet', CURDATE())";
    if ($conn->query($sql_create_order) !== TRUE) {
        throw new Exception("Failed to create order");
    }

    // Get the ID of the newly created order
    $orderId = $conn->insert_id;

    // Insert items into order_details table
    foreach ($items as $item) {
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

    $result["return_code"] = 1;
    $result["return_message"] = "Success";
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

// Output JSON response
echo json_encode($result);
?>