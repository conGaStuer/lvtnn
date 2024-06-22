<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$items = $data['items'];

// Create a new order
$sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
if ($conn->query($sql_create_order) === TRUE) {
    $orderId = $conn->insert_id;
    $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
    if ($conn->query($sql_add_event) !== TRUE) {
        echo json_encode(['status' => 'fail', 'message' => 'Failed to add event']);
        $conn->close();
        exit();
    }
    foreach ($items as $item) {
        $maSach = $item['MaSach'];
        $soLuong = $item['SoLuong'];
        $donGia = $item['DonGia'];

        // Add items to the new order
        $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
        if ($conn->query($sql_insert_item) !== TRUE) {
            echo json_encode(['status' => 'fail', 'message' => 'Failed to add item to order']);
            $conn->close();
            exit();
        }
    }

    // Remove the items from the cart
    foreach ($items as $item) {
        $maSach = $item['MaSach'];
        $sql_delete_cart_item = "DELETE FROM chi_tiet_don_hang WHERE madon IN (SELECT madon FROM don_dat_hang WHERE maND = '$userId' AND trangthai = 'giohang') AND masach = '$maSach'";
        if ($conn->query($sql_delete_cart_item) !== TRUE) {
            echo json_encode(['status' => 'fail', 'message' => 'Failed to remove item from cart']);
            $conn->close();
            exit();
        }
    }

    // Remove empty cart orders
    $sql_delete_empty_cart_orders = "DELETE FROM don_dat_hang WHERE maND = '$userId' AND trangthai = 'giohang' AND madon NOT IN (SELECT madon FROM chi_tiet_don_hang)";
    $conn->query($sql_delete_empty_cart_orders);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'fail', 'message' => 'Failed to create order']);
}

$conn->close();
?>