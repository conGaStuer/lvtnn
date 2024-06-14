<?php
include '../config.php'; // Update with your actual database connection script

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['MaTacGia']) && isset($input['TenTacGia'])) {
    $MaTacGia = $input['MaTacGia'];
    $TenTacGia = $input['TenTacGia'];

    $query = "UPDATE tac_gia SET tenTG = ? WHERE maTG = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $TenTacGia, $MaTacGia);

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