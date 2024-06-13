<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$maND = $data['userId'];
$maSach = $data['maSach'];
$donGia = $data['donGia'];
$soLuong = $data['soLuong'];

$sql_check_stock = "SELECT soLuong FROM sach WHERE masach = '$maSach'";
$result_check_stock = $conn->query($sql_check_stock);

if ($result_check_stock->num_rows > 0) {
    $row = $result_check_stock->fetch_assoc();
    if ($row['soLuong'] > 0 && $row['soLuong'] >= $soLuong) { // Thêm điều kiện soLuong > 0 vào đây
        $sqlDonHang = "INSERT INTO don_dat_hang (ngaydat, maND, trangthai) VALUES (CURDATE(), '$maND', 'giohang')";
        $resultDonHang = $conn->query($sqlDonHang);

        if ($resultDonHang === TRUE) {
            $madon = $conn->insert_id;

            $sql_check = "SELECT * FROM chi_tiet_don_hang WHERE masach= '$maSach'";
            $result = $conn->query($sql_check);

            if ($result->num_rows > 0) {
                $sql_update = "UPDATE chi_tiet_don_hang SET soluong = soluong + '$soLuong' WHERE masach= '$maSach'";
                $result_update = $conn->query($sql_update);
            } else {
                $note = '';
                $sqlChiTietDonHang = "INSERT INTO chi_tiet_don_hang (masach, madon, soluong, dongia, note) 
                VALUES ('$maSach', '$madon', '$soLuong', '$donGia', '$note')";
                $stmtChiTietDonHang = $conn->query($sqlChiTietDonHang);
            }

            $sql_update_stock = "UPDATE sach SET soLuong = soLuong - '$soLuong' WHERE masach = '$maSach'";
            $conn->query($sql_update_stock);

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm đơn hàng']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Số lượng sách không đủ trong kho']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Sách không tồn tại']);
}
?>