<?php

// PHP Version 7.3.3
header('Content-Type: application/json');
include "config.php";
$result = [];

try {
    $key2 = "eG4r0GcoNtRGbO8";
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

    $requestmac = $postdatajson["mac"];

    // kiểm tra callback hợp lệ (đến từ ZaloPay server)
    if (strcmp($mac, $requestmac) != 0) {
        // callback không hợp lệ
        $result["returncode"] = -1;
        $result["returnmessage"] = "mac not equal";
    } else {
        // thanh toán thành công
        // merchant cập nhật trạng thái cho đơn hàng

        $datajson = json_decode($postdatajson["data"], true);
        $app_trans_id = $datajson["app_trans_id"];

        $userId = $datajson["app_user"];
        $amount = $datajson["amount"];
        $items = $datajson["items"];

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
                    throw new Exception("Failed to add event: " . $conn->error);
                }

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
                echo json_encode(['status' => 'success']);
            } else {
                throw new Exception("Failed to create order: " . $conn->error);
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo json_encode(['status' => 'fail', 'message' => $e->getMessage()]);
        }



        $result["returncode"] = 1;
        $result["returnmessage"] = "success";
    }
} catch (Exception $e) {
    $result["returncode"] = 0; // ZaloPay server sẽ callback lại (tối đa 3 lần)
    $result["returnmessage"] = $e->getMessage();
}

// thông báo kết quả cho ZaloPay server
echo json_encode($result);