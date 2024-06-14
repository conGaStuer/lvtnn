<?php
include '../config.php'; // Update with your actual database connection script

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['MaNhaXuatBan']) && isset($input['TenNhaXuatBan'])) {
    $MaNhaXuatBan = $input['MaNhaXuatBan'];
    $TenNhaXuatBan = $input['TenNhaXuatBan'];

    $query = "UPDATE nha_xuat_ban SET tenNXB = ? WHERE maNXB = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $TenNhaXuatBan, $MaNhaXuatBan);

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