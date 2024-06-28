<?php
header('Content-Type: application/json');
include "config.php";

$result = [];

try {
    $key2 = $config['key2'];

    // Đọc JSON data từ request
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);

    // Xác thực MAC
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);
    $requestmac = $postdatajson["mac"];

    if (strcmp($mac, $requestmac) !== 0) {
        $result["return_code"] = -1;
        $result["return_message"] = "Invalid MAC";
    } else {
        // Xử lý dữ liệu callback
        $datajson = json_decode($postdatajson["data"], true);
        $app_trans_id = $datajson["app_trans_id"];
        $userId = $datajson["app_user"];
        $amount = $datajson["amount"];
        $items = json_decode($datajson["item"], true);

        // Mở kết nối cơ sở dữ liệu (giả sử $conn là kết nối cơ sở dữ liệu của bạn)
        $conn->begin_transaction();

        try {
            // Tạo đơn hàng mới
            $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
            if ($conn->query($sql_create_order) !== TRUE) {
                throw new Exception("Failed to create order");
            }

            // Lấy ID của đơn hàng mới tạo
            $orderId = $conn->insert_id;

            // Thêm sự kiện vào bảng order_events
            $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
            if ($conn->query($sql_add_event) !== TRUE) {
                throw new Exception("Failed to add event");
            }

            // Thêm từng mặt hàng vào bảng chi_tiet_don_hang
            foreach ($items as $item) {
                $maSach = $item['MaSach'];
                $soLuong = $item['SoLuong'];
                $donGia = $item['DonGia'];

                $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
                if ($conn->query($sql_insert_item) !== TRUE) {
                    throw new Exception("Failed to add item to order");
                }
            }

            // Commit transaction nếu mọi thứ thành công
            $conn->commit();

            // Trả về thông báo thành công
            $result["return_code"] = 1;
            $result["return_message"] = "Success";
        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            $conn->rollback();
            $result["return_code"] = 0;
            $result["return_message"] = $e->getMessage();
        }

        // Đóng kết nối cơ sở dữ liệu
        $conn->close();
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

// Thông báo kết quả cho server ZaloPay
echo json_encode($result);
?>