<?php
header('Content-Type: application/json');
include "config.php";

function addOrder($conn, $app_trans_id, $userId, $items)
{
    try {
        // Bắt đầu transaction
        $conn->begin_transaction();

        // Tạo đơn hàng mới
        $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
        if ($conn->query($sql_create_order) === TRUE) {
            $orderId = $conn->insert_id;

            // Thêm sự kiện
            $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
            if ($conn->query($sql_add_event) !== TRUE) {
                throw new Exception("Failed to add event: " . $conn->error);
            }

            // Thêm các mặt hàng vào đơn hàng
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
            return ['return_code' => 1, 'return_message' => 'Success'];
        } else {
            throw new Exception("Failed to create order: " . $conn->error);
        }
    } catch (Exception $e) {
        // Rollback transaction khi có lỗi
        $conn->rollback();
        return ['return_code' => 0, 'return_message' => $e->getMessage()];
    }
}

$result = [];

try {
    // Lấy thông tin từ URL parameters
    $app_trans_id = $_GET['app_trans_id'];
    $userId = $_GET['userId'];
    $items = json_decode($_GET['items'], true);

    // Gọi hàm thêm đơn hàng
    $addOrderResult = addOrder($conn, $app_trans_id, $userId, $items);

    // Trả về kết quả dưới dạng JSON
    echo json_encode($addOrderResult);
} catch (Exception $e) {
    // Xử lý ngoại lệ nếu có
    echo json_encode(['return_code' => 0, 'return_message' => $e->getMessage()]);
}
?>