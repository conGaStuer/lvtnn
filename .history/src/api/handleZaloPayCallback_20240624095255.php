<?php
// Xử lý callback từ Zalo Pay
$data = json_decode(file_get_contents("php://input"), true);
file_put_contents('callback.log', json_encode($data)); // Ghi log để kiểm tra callback

if ($data['return_code'] == 1) {
    // Xử lý thành công, cập nhật trạng thái đơn hàng
    echo json_encode(['return_code' => 1, 'return_message' => 'Payment successful']);
} else {
    // Xử lý thất bại
    echo json_encode(['return_code' => 0, 'return_message' => 'Payment failed']);
}
?>