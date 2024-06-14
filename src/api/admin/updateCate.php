<?php
include '../config.php'; // Update with your actual database connection script

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['MaDanhMuc']) && isset($input['TenDanhMuc'])) {
    $MaDanhMuc = $input['MaDanhMuc'];
    $TenDanhMuc = $input['TenDanhMuc'];

    $query = "UPDATE danh_muc SET tenDM = ? WHERE maDM = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $TenDanhMuc, $MaDanhMuc);

    if ($stmt->execute()) {
        echo json_encode("Cap Nhat Thanh Cong");
    } else {
        echo json_encode("Cap Nhat Khong Thanh Cong");
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode("Du Lieu Khong Hop Le");
}
?>