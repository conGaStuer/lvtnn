<?php
include "../config.php";

// Lấy dữ liệu từ request POST
$data = json_decode(file_get_contents("php://input"), true);
$maDon = $data['maDon'];
$status = $data['status'];

// Cập nhật trạng thái đơn hàng
$sql_update_status = "
    UPDATE don_dat_hang
    SET trangthai = ?
    WHERE maDon = ?
";

$stmt = $conn->prepare($sql_update_status);
$stmt->bind_param("si", $status, $maDon);

$response = [];

if ($stmt->execute()) {
    // Thêm sự kiện vào bảng order_events
    $sql_add_event = "
        INSERT INTO order_events (order_id, event)
        VALUES (?, ?)
    ";
    $stmt_event = $conn->prepare($sql_add_event);
    $stmt_event->bind_param("ss", $maDon, $status);
    $stmt_event->execute();
    $stmt_event->close();

    $response['message'] = "Cập nhật trạng thái đơn hàng thành công";
} else {
    $response['message'] = "Lỗi khi cập nhật trạng thái đơn hàng";
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>