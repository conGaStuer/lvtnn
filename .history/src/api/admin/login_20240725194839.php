<?php
session_start();
require "../config.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username;
    $password = $data->password;

    $sql_login = "SELECT * FROM nguoi_dung WHERE taikhoan = '$username' AND matkhau = '$password'";
    $result = $conn->query($sql_login);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['maVaiTro'] == 2 || $user['maVaiTro'] == 3) {
            $_SESSION['user'] = $username;
            echo json_encode($user);
        } else {
            echo json_encode(array("error" => "Unauthorized access"));
        }
    } else {
        echo json_encode(array("error" => "Invalid username or password"));
    }
}

$conn->close();

?>