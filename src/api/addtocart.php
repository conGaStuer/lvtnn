<?php
session_start();

require ("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu sách và thông tin người dùng được gửi từ phía client

    $maSach = $_POST['MaSach'];
    $donGia = $_POST['DonGia'];
    $maND = $_POST['userId']; // Lấy mã người dùng từ FormData

    if (!empty($maSach) && !empty($donGia) && !empty($maND)) {
        // Kiểm tra xem đơn hàng của người dùng đã tồn tại hay chưa
        $sql_check_order = "SELECT * FROM don_dat_hang WHERE maND = '$maND' AND tinh_trang = 'Chưa xác nhận'";
        $result_check_order = $conn->query($sql_check_order);

        if ($result_check_order->num_rows > 0) {
            // Nếu đơn hàng chưa xác nhận đã tồn tại, thêm vào chi tiết đơn hàng
            $row_order = $result_check_order->fetch_assoc();
            $maDDH = $row_order['maDDH'];
            $sql_check_detail = "SELECT * FROM chitiet_donhang WHERE maDDH = '$maDDH' AND maSach = '$maSach'";
            $result_check_detail = $conn->query($sql_check_detail);

            if ($result_check_detail->num_rows > 0) {
                // Nếu sách đã tồn tại trong chi tiết đơn hàng, cập nhật số lượng và tổng tiền
                $row_detail = $result_check_detail->fetch_assoc();
                $soLuong = $row_detail['soLuong'] + 1;
                $sql_update = "UPDATE chitiet_donhang SET soLuong = '$soLuong', thanhTien = '$soLuong' * gia WHERE maDDH = '$maDDH' AND maSach = '$maSach'";
                $result_update = $conn->query($sql_update);
                echo json_encode(array("message" => "Đã cập nhật số lượng sách trong giỏ hàng."));
            } else {
                // Nếu sách chưa tồn tại trong chi tiết đơn hàng, thêm vào chi tiết đơn hàng với số lượng là 1
                $sql_insert_detail = "INSERT INTO chitiet_donhang (maDDH, maSach, soLuong, gia, thanhTien) VALUES ('$maDDH', '$maSach', 1, '$donGia', '$donGia')";
                $result_insert_detail = $conn->query($sql_insert_detail);
                echo json_encode(array("message" => "Sách đã được thêm vào giỏ hàng."));
            }
        } else {
            // Nếu đơn hàng chưa xác nhận chưa tồn tại, tạo mới đơn hàng và thêm vào chi tiết đơn hàng
            $sql_insert_order = "INSERT INTO don_dat_hang (maND, tinh_trang) VALUES ('$maND', 'Chưa xác nhận')";
            $result_insert_order = $conn->query($sql_insert_order);

            if ($result_insert_order === TRUE) {
                $maDDH = $conn->insert_id;
                $sql_insert_detail = "INSERT INTO chitiet_donhang (maDDH, maSach, soLuong, gia, thanhTien) VALUES ('$maDDH', '$maSach', 1, '$donGia', '$donGia')";
                $result_insert_detail = $conn->query($sql_insert_detail);
                echo json_encode(array("message" => "Sách đã được thêm vào giỏ hàng."));
            } else {
                echo json_encode(array("message" => "Lỗi khi tạo đơn hàng mới."));
            }
        }
    } else {
        echo json_encode(array("message" => "Dữ liệu không hợp lệ."));
    }
} else {
    echo json_encode(array("message" => "Yêu cầu không hợp lệ."));
}

$conn->close();
?>