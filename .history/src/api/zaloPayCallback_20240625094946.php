<?php
header('Content-Type: application/json');
include "config.php";

$result = [];

try {
    // Key2 từ ZaloPay, thay đổi theo key của bạn
    $key2 = "eG4r0GcoNtRGbO8";

    // Đọc dữ liệu từ request gửi đến
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);

    // Kiểm tra MAC
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);
    $requestmac = $postdatajson["mac"];

    if (strcmp($mac, $requestmac) != 0) {
        $result["return_code"] = -1;
        $result["return_message"] = "Invalid MAC";
    } else {
        // Xử lý dữ liệu callback
        $datajson = json_decode($postdatajson["data"], true);
        $app_trans_id = $datajson["app_trans_id"];
        $userId = $datajson["app_user"];
        $amount = $datajson["amount"];
        $items = $datajson["items"];

        // Mở kết nối đến cơ sở dữ liệu


        // Bắt đầu transaction để đảm bảo tính nhất quán trong các lệnh SQL
        $conn->begin_transaction();

        try {
            // Tạo đơn hàng mới
            $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
            if ($conn->query($sql_create_order) !== TRUE) {
                throw new Exception("Failed to create order");
            }

            // Lấy ID của đơn hàng vừa tạo
            $orderId = $conn->insert_id;

            // Thêm sự kiện vào bảng order_events
            $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
            if ($conn->query($sql_add_event) !== TRUE) {
                throw new Exception("Failed to add event");
            }

            // Thêm từng mặt hàng vào chi tiết đơn hàng
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
            $result["return_message"] = "success";
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

echo json_encode($result);
?>