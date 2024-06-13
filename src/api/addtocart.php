<?php
// Kết nối đến cơ sở dữ liệu
include "config.php";
// Kiểm tra xem có dữ liệu được gửi từ frontend hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu được gửi từ frontend

    $data = json_decode(file_get_contents("php://input"), true);
    $maND = $data['userId'];
    $maSach = $data['maSach'];
    $donGia = $data['donGia'];
    $soLuong = $data['soLuong'];
    // Thêm vào bảng đơn đặt hàng (don_dat_hang)
    $sqlDonHang = "INSERT INTO don_dat_hang (ngaydat, maND, trangthai) VALUES (CURDATE(), '$maND', 'giohang')";
    $resultDonHang = $conn->query($sqlDonHang);
    if ($resultDonHang === true) {

        // Lấy ID của đơn đặt hàng vừa thêm vào

        $madon = $conn->insert_id;

        // Thêm vào bảng chi tiết đơn hàng (chi_tiet_don_hang)


        $sql_check = "SELECT * FROM chi_tiet_don_hang WHERE masach= '$maSach'";
        $result = $conn->query($sql_check);

        if ($result->num_rows > 0) {
            $sql_update = "UPDATE chi_tiet_don_hang SET soluong = soluong + '$soLuong' where masach= '$maSach'";
            $result_update = $conn->query($sql_update);
        } else {
            $note = ''; // Thay '' bằng ghi chú nếu có

            $sqlChiTietDonHang = "INSERT INTO chi_tiet_don_hang (masach, madon, soluong, dongia, note) 
        VALUES ('$maSach', '$madon', '$soLuong', '$donGia', '$note')";
            $stmtChiTietDonHang = $conn->query($sqlChiTietDonHang);

        }
    }
    // Trả về thông báo cho frontend
}
?>