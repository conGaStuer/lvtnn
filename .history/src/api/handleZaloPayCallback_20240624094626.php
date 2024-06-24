<?php
// Xử lý callback từ Zalo Pay
include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);
if ($data['return_code'] == 1) {
    // Xử lý thành công, cập nhật trạng thái đơn hàng
    // Ví dụ: Cập nhật vào database, gửi email xác nhận cho khách hàng, v.v.
    echo json_encode(['return_code' => 1, 'return_message' => 'Payment successful']);
} else {
    // Xử lý thất bại
    echo json_encode(['return_code' => 0, 'return_message' => 'Payment failed']);
}
?>