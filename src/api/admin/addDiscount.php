<?php

include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $luongKM = $data['LuongKM'];


    $sql_check_duplicate = "SELECT * FROM khuyen_mai WHERE luongKM = '$luongKM'";
    $result = $conn->query($sql_check_duplicate);

    if ($result->num_rows > 0) {
        echo json_encode("Luong khuyen mai da ton tai");
    } else {
        $sql_insert_cate = "INSERT INTO khuyen_mai (luongKM) VALUES ('$luongKM')";
        if ($conn->query($sql_insert_cate) === true) {
            echo json_encode("Them Thanh Cong");
        } else {
            echo json_encode("Them that bai");
        }
    }
}

$conn->close();
?>