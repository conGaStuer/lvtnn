<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $maND = $data['maND'];
    $newPassword = $data['newPassword'];

    $sql = "UPDATE nguoi_dung SET matkhau = '$newPassword' WHERE maND = '$maND'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['changePassSuccess' => true]);
    } else {
        echo json_encode(['changePassSuccess' => false]);
    }
}
?>