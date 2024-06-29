<?php
include "../config.php";
// Ensure POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $data = json_decode(file_get_contents("php://input"));
    $tenKH = $data->tenKH;
    $taikhoan = $data->taikhoan;
    $matkhau = $data->matkhau;

    $sql_check = "SELECT * FROM nguoi_dung WHERE taikhoan = '$taikhoan'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        echo json_encode(array("error" => "Trung ten dang nhap"));
    } else {

        $sql_insert = "INSERT INTO nguoi_dung ( taikhoan, matkhau, tenKH, ngayLapTaiKhoan,  maVaiTro)
        VALUES ( '$taikhoan', '$matkhau', '$tenKH',NOW() , 2)";
        $result_insert = $conn->query($sql_insert);
        if ($result_insert === true) {
            echo json_encode(array("success" => "Them thanh cong"));

        } else {
            echo json_encode(array("error" => "Khong them duoc"));

        }
    }
} else {

    echo json_encode(['error' => 'Method Not Allowed']);
}

?>