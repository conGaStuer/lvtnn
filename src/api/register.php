<?php
session_start();
require "./config.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username;
    $password = $data->password;
    $sql_check = "SELECT * FROM nguoi_dung WHERE taikhoan = '$username'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        echo json_encode(array("error" => "Trung ten dang nhap"));


    } else {

        $sql_insert = "INSERT INTO nguoi_dung (maND, taikhoan, matkhau, tenKH, sdt, email, hinh, diachi, anhbia, maVaiTro)
        VALUES ('', '$username', '$password', '', '', '', '', '', '', 1)";
        $result_insert = $conn->query($sql_insert);
        if ($result_insert === true) {
            $_SESSION['user'] = $username;
            $user_id = $conn->insert_id;
            $sql_user = "SELECT * FROM nguoi_dung WHERE maND = $user_id";
            $result_user = $conn->query($sql_user);
            $user = $result_user->fetch_assoc();
            echo json_encode($user);
        } else {
            echo json_encode(array("error" => "Khong them duoc"));

        }
    }
}

$conn->close();
?>