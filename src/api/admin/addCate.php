<?php

include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $tenDM = $data['TenDanhMuc'];

    $sql_check_duplicate = "SELECT * FROM danh_muc WHERE tenDM = '$tenDM'";
    $result = $conn->query($sql_check_duplicate);

    if ($result->num_rows > 0) {
        echo json_encode("Danh muc da ton tai");
    } else {

        $sql_insert_cate = "INSERT INTO danh_muc (tenDM) VALUES ('$tenDM')";
        if ($conn->query($sql_insert_cate) === true) {
            echo json_encode("Them Thanh Cong");
        } else {
            echo json_encode("Them that bai");
        }
    }
}

$conn->close();
?>