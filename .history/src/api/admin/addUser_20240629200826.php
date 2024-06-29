<?php
include "../config.php";
// Ensure POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $data = json_decode(file_get_contents("php://input"));
    $tenKH = $_POST['tenKH'];
    $taikhoan = $_POST['taikhoan'];
    $matkhau = $_POST['matkhau'];

    $sql_check = "SELECT * FROM nguoi_dung WHERE taikhoan = '$taikhoan'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        echo json_encode(array("error" => "Trung ten dang nhap"));
    } else {

        $sql_insert = "INSERT INTO nguoi_dung ( taikhoan, matkhau, tenKH,  maVaiTro)
        VALUES ( '$username', '$password', '$tenKH', 2)";
        $result_insert = $conn->query($sql_insert);
        if ($result_insert === true) {
            $_SESSION['user'] = $username;


        } else {
            echo json_encode(array("error" => "Khong them duoc"));

        }
    }
} else {
    // Handle invalid request method (not POST)
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}

?>