<?php

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $user = $data->user;

    $sql = "SELECT * from nguoi_dung WHERE maND = '$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(["user" => $user]);
    } else {
        echo json_encode(array("error" => "Invalid username or password"));

    }
}