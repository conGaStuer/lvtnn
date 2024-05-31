<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->userId)) {
    $email = $data->email;
    $userId = $data->userId;

    $sql_check_email = "SELECT * FROM nguoi_dung WHERE email = '$email' AND maND != '$userId'";
    $result_check_email = $conn->query($sql_check_email);

    if ($result_check_email->num_rows > 0) {
        echo json_encode(["emailExists" => true]);
    } else {
        echo json_encode(["emailExists" => false]);
    }

    $conn->close();
} else {
    echo json_encode(["error" => "Invalid input"]);
}
?>