<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'D:\ComposerSetup\vendor\autoload.php';
include "config.php";
$data = json_decode(file_get_contents("php://input"));

function generateOTP()
{
    return rand(100000, 999999);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $data->email;
    $userId = $data->userId;

    // Check if the email is already used by another user
    $stmt_check_email = $conn->prepare("SELECT * FROM nguoi_dung WHERE email = ?");
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result = $stmt_check_email->get_result();
    if ($result->num_rows > 0) {
        // Email is already in use
        echo json_encode(['success' => false, 'message' => 'Email này đã được sử dụng cho tài khoản khác.']);
        exit;
    }

    $otp = generateOTP();

    // Insert OTP into the database
    $stmt = $conn->prepare("INSERT INTO otp_codes (user_id, otp_code, email) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $otp, $email);
    $stmt->execute();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dh52003670@student.stu.edu.vn';
        $mail->Password = '123456Aa!';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('dh52003670@student.stu.edu.vn', 'Book Store');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = 'Your OTP code is ' . $otp;

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Mã OTP đã được gửi đi.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Không thể gửi email OTP.']);
    }
}
?>