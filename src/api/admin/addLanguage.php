<?php

include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $tenNN = $data['TenNgonNgu'];

    $sql_check_duplicate = "SELECT * FROM ngon_ngu WHERE tenNN = '$tenNN'";
    $result = $conn->query($sql_check_duplicate);

    if ($result->num_rows > 0) {
        echo json_encode("Ngon ngu da ton tai");
    } else {

        $sql_insert_cate = "INSERT INTO ngon_ngu (tenNN) VALUES ('$tenNN')";
        if ($conn->query($sql_insert_cate) === true) {
            echo json_encode("Them Thanh Cong");
        } else {
            echo json_encode("Them that bai");
        }
    }
}

$conn->close();
?>