<?php
// editQuantity.php

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $maSach = $data['maSach'];
    $soLuong = $data['soLuong'];

    // Update quantity in chi_tiet_don_hang
    $sql = "UPDATE chi_tiet_don_hang SET soluong = ? WHERE masach = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $soLuong, $maSach);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Quantity updated successfully."]);
    } else {
        echo json_encode(["message" => "Error updating quantity."]);
    }

    $stmt->close();
    $conn->close();
}
?>