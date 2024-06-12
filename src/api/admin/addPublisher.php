<?php

include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $tenNXB = $data['TenNhaXuatBan'];

    $sql_check_duplicate = "SELECT * FROM nha_xuat_ban WHERE tenNXB = '$tenNXB'";
    $result = $conn->query($sql_check_duplicate);

    if ($result->num_rows > 0) {
        echo json_encode("Nha xuat ban da ton tai");
    } else {
        $sql_insert_cate = "INSERT INTO nha_xuat_ban (tenNXB) VALUES ('$tenNXB')";
        if ($conn->query($sql_insert_cate) === true) {
            echo json_encode("Them Thanh Cong");
        } else {
            echo json_encode("Them that bai");
        }
    }
}

$conn->close();
?>