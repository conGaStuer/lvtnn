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
    $token = bin2hex(random_bytes(16)); // Generate a random token

    // Check if userId is set and not null
    if ($userId !== null) {
        $otp = generateOTP();

        $stmt = $conn->prepare("INSERT INTO otp_codes (user_id, otp_code, email) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $otp, $email);
        $stmt->execute();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dh52003670@student.stu.edu.vn';
            $mail->Password = '0854219725Bb';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('dh52003670@student.stu.edu.vn', 'Book Store');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = 'Your OTP code is ' . $otp;

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'OTP sent']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Mail could not be sent.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID is null.']);
    }
}
?>