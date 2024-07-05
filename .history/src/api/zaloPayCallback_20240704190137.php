<?php
header('Content-Type: application/json');
include "config.php";

$result = [];

try {
    // Decode the JSON data received
    $postdata = file_get_contents('php://input');
    $datajson = json_decode($postdata, true);

    // Extract necessary data
    $userId = $datajson["app_user"];
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

    $result["status"] = "success";
    $result["message"] = "Order added to database";
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    $result["status"] = "error";
    $result["message"] = $e->getMessage();
}

// Output JSON response
echo json_encode($result);
?>