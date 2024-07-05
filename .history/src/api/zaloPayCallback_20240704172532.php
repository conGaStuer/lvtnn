<?php
include "config.php";
header('Content-Type: application/json');

$result = [];

try {
    // Parse JSON data from ZaloPay callback
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);

    // Validate required fields
    if (!isset($postdatajson["data"]) || !isset($postdatajson["mac"])) {
        throw new Exception("Invalid callback data");
    }

    // Validate MAC
    $key2 = "your_secret_key"; // Replace with your actual key
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);
    if (strcmp($mac, $postdatajson["mac"]) !== 0) {
        throw new Exception("MAC verification failed");
    }

    // Parse data from ZaloPay callback
    $datajson = json_decode($postdatajson["data"], true);
    $userId = $datajson["app_user"];
    $amount = $datajson["amount"];
    $items = $datajson["items"];

    // Start transaction
    $conn->begin_transaction();

    // Create a new order
    $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
    if ($conn->query($sql_create_order) === TRUE) {
        $orderId = $conn->insert_id;

        // Add items to the order
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
        $result["status"] = "success";
    } else {
        throw new Exception("Failed to create order: " . $conn->error);
    }
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $result["status"] = "fail";
    $result["message"] = $e->getMessage();
}

// Return JSON response
echo json_encode($result);
?>