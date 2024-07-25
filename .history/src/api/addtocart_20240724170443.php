<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$maND = $data['userId'];
$maSach = $data['maSach'];
$donGia = $data['donGia'];
$soLuong = $data['soLuong'];

// Check if the book exists and has enough stock
$sql_check_stock = "SELECT soLuong FROM sach WHERE masach = '$maSach'";
$result_check_stock = $conn->query($sql_check_stock);

if ($result_check_stock->num_rows > 0) {
    $row = $result_check_stock->fetch_assoc();
    if ($row['soLuong'] > 0 && $row['soLuong'] >= $soLuong) { // Check if there's enough stock

        // Check if there's an existing cart for the user
        $sql_check_cart = "SELECT * FROM don_dat_hang WHERE maND = '$maND' AND trangthai = 'giohang'";
        $result_check_cart = $conn->query($sql_check_cart);

        if ($result_check_cart->num_rows > 0) {
            // There's an existing cart
            $cart = $result_check_cart->fetch_assoc();
            $madon = $cart['madon'];
        } else {
            // No existing cart, create a new one
            $sql_create_cart = "INSERT INTO don_dat_hang (ngaydat, maND, trangthai) VALUES (CURDATE(), '$maND', 'giohang')";
            if ($conn->query($sql_create_cart) === TRUE) {
                $madon = $conn->insert_id;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi tạo giỏ hàng']);
                $conn->close();
                exit();
            }
        }

        // Check if the book is already in the cart
        $sql_check_item = "SELECT * FROM chi_tiet_don_hang WHERE madon = '$madon' AND masach = '$maSach'";
        $result_check_item = $conn->query($sql_check_item);

        if ($result_check_item->num_rows > 0) {
            // The item is already in the cart, update the quantity
            $sql_update_quantity = "UPDATE chi_tiet_don_hang SET soluong = soluong + '$soLuong' WHERE madon = '$madon' AND masach = '$maSach'";
            if ($conn->query($sql_update_quantity) !== TRUE) {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật số lượng sản phẩm']);
                $conn->close();
                exit();
            }
        } else {
            // The item is not in the cart, insert a new item
            $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) VALUES ('$madon', '$maSach', '$soLuong', '$donGia')";
            if ($conn->query($sql_insert_item) !== TRUE) {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm sản phẩm vào giỏ hàng']);
                $conn->close();
                exit();
            }
        }

        // Update the stock
        $sql_update_stock = "UPDATE sach SET soLuong = soLuong - '$soLuong' WHERE masach = '$maSach'";
        $conn->query($sql_update_stock);

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Số lượng sách không đủ trong kho']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Sách không tồn tại']);
}

$conn->close();
?>