<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $taikhoan = $data['taikhoan'];
    $tenKH = $data['tenKH'];
    $sdt = $data['sdt'];
    $diachi = $data['diachi'];
    $hinh = $data['hinh'];
    $anhbia = $data['anhbia'];

    $sql = "UPDATE nguoi_dung SET  tenKH = '$tenKH', sdt = '$sdt', diachi = '$diachi', anhbia = '$anhbia', hinh = '$hinh'
            WHERE taikhoan = '$taikhoan'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['updateProfileSuccess' => true]);
    } else {
        echo json_encode(['updateProfileSuccess' => false]);
    }
}
?>