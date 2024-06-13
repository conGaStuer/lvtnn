<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$maSach = $data['maSach'];
$soLuong = $data['soLuong'];

$sql_check_stock = "SELECT soLuong FROM sach WHERE masach = '$maSach'";
$result_check_stock = $conn->query($sql_check_stock);

if ($result_check_stock->num_rows > 0) {
    $row = $result_check_stock->fetch_assoc();
    if ($row['soLuong'] >= $soLuong) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Số lượng sách không đủ trong kho']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Sách không tồn tại']);
}
?>