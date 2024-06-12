<?php

include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve MaSach from the request body
    $data = json_decode(file_get_contents("php://input"), true);
    $maSach = $data['MaSach'];

    // Delete related records from child tables first
    $sql_delete_dm_sach = "DELETE FROM dm_sach WHERE maSach = '$maSach'";
    $sql_delete_nn_sach = "DELETE FROM nn_sach WHERE maSach = '$maSach'";
    $sql_delete_tg_sach = "DELETE FROM tg_sach WHERE maSach = '$maSach'";

    // Execute the delete queries for child tables
    $conn->query($sql_delete_dm_sach);
    $conn->query($sql_delete_nn_sach);
    $conn->query($sql_delete_tg_sach);

    // After deleting child records, delete the main record from the 'sach' table
    $sql_delete_book = "DELETE FROM sach WHERE maSach = '$maSach'";

    if ($conn->query($sql_delete_book) === true) {
        echo json_encode("Xóa sách thành công");
    } else {
        echo json_encode("Lỗi khi xóa sách: " . $conn->error);
    }

    $conn->close();
}
?>