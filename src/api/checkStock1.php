<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$maSach = $data['maSach'];
$newSoLuong = $data['soLuong'];

$sql = "SELECT soLuong FROM sach WHERE masach = '$maSach'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $soLuongTrongKho = $row['soLuong'];

    // Get the current quantity from chi_tiet_don_hang
    $sql_get_current = "SELECT soluong FROM chi_tiet_don_hang WHERE masach = '$maSach'";
    $result_get_current = $conn->query($sql_get_current);

    if ($result_get_current->num_rows > 0) {
        $row_current = $result_get_current->fetch_assoc();
        $currentSoLuong = $row_current['soluong'];
    } else {
        $currentSoLuong = 0;
    }

    $availableSoLuong = $soLuongTrongKho + $currentSoLuong;

    if ($availableSoLuong >= $newSoLuong) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Số lượng sách không đủ trong kho']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Sách không tồn tại']);
}

$conn->close();
?>