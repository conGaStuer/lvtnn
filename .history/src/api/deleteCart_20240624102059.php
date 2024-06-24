<?php
session_start();
include (__DIR__ . "/config.php");

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->maSach)) {
        $stmt = $conn->prepare("DELETE FROM chi_tiet_don_hang ctdh
        join don_dat_hang ddh on ddh.madon =  ctdh.madon
        WHERE masach = ? 
        and trangthai = 'giohang'
        ");
        $stmt->bind_param("s", $data->maSach);
        $stmt->execute();
        $stmt->close();
        $soLuong = $data->soLuong;
        $maSach = $data->maSach;
        $sql_update_stock = "UPDATE sach SET soLuong = soLuong + '$soLuong' WHERE masach = '$maSach'";
        $conn->query($sql_update_stock);
        echo json_encode(array("message" => "Item deleted successfully."));
    } else {
        echo json_encode(array("message" => "Invalid data provided."));
    }
}

$conn->close();
?>