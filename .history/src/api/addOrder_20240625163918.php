<?php
include "config.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$items = $data['items'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Create a new order
    $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
    if ($conn->query($sql_create_order) === TRUE) {
        $orderId = $conn->insert_id;

        // Add event
        $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
        if ($conn->query($sql_add_event) !== TRUE) {
            throw new Exception("Failed to add event");
        }

        // Add items to the order
        foreach ($items as $item) {
            $maSach = $item['MaSach'];
            $soLuong = $item['SoLuong'];
            $donGia = $item['DonGia'];

            $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
            if ($conn->query($sql_insert_item) !== TRUE) {
                throw new Exception("Failed to add item to order");
            }
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception("Failed to create order");
    }
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['status' => 'fail', 'message' => $e->getMessage()]);
}

$conn->close();
?>