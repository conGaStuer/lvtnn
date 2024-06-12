<?php

include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $tenTG = $data['TenTacGia'];

    $sql_check_duplicate = "SELECT * FROM tac_gia WHERE tenTG = '$tenTG'";
    $result = $conn->query($sql_check_duplicate);

    if ($result->num_rows > 0) {
        echo json_encode("Tac gia da ton tai");
    } else {

        $sql_insert_cate = "INSERT INTO tac_gia (tenTG) VALUES ('$tenTG')";
        if ($conn->query($sql_insert_cate) === true) {
            echo json_encode("Them Thanh Cong");
        } else {
            echo json_encode("Them that bai");
        }
    }
}

$conn->close();
?>