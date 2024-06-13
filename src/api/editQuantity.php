<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $maSach = $data['maSach'];
    $newSoLuong = $data['soLuong'];

    // Get the current quantity from chi_tiet_don_hang
    $sql_get_current = "SELECT soluong FROM chi_tiet_don_hang WHERE masach = '$maSach'";
    $result_get_current = mysqli_query($conn, $sql_get_current);

    if ($result_get_current && mysqli_num_rows($result_get_current) > 0) {
        $row = mysqli_fetch_assoc($result_get_current);
        $currentSoLuong = $row['soluong'];

        $quantityDifference = $newSoLuong - $currentSoLuong;

        // Update quantity in chi_tiet_don_hang
        $sql_update_quantity = "UPDATE chi_tiet_don_hang SET soluong = '$newSoLuong' WHERE masach = '$maSach'";
        $result_update_quantity = mysqli_query($conn, $sql_update_quantity);

        if ($result_update_quantity) {
            // Update stock in sach table
            $sql_update_stock = "UPDATE sach SET soLuong = soLuong - '$quantityDifference' WHERE masach = '$maSach'";
            $result_update_stock = mysqli_query($conn, $sql_update_stock);

            if ($result_update_stock) {
                echo json_encode(["message" => "Quantity updated successfully."]);
            } else {
                echo json_encode(["message" => "Error updating stock."]);
            }
        } else {
            echo json_encode(["message" => "Error updating quantity."]);
        }
    } else {
        echo json_encode(["message" => "Item not found in cart."]);
    }

    mysqli_close($conn);
}
?>