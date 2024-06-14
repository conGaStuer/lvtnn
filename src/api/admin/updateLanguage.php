<?php
include '../config.php'; // Update with your actual database connection script

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['MaNgonNgu']) && isset($input['TenNgonNgu'])) {
    $MaNgonNgu = $input['MaNgonNgu'];
    $TenNgonNgu = $input['TenNgonNgu'];

    $query = "UPDATE ngon_ngu SET tenNN = ? WHERE maNN = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $TenNgonNgu, $MaNgonNgu);

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