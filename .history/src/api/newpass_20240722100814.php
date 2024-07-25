<?php
require "./config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require "D:/PHPMailer-PHPMailer-2128d99/src/Exception.php";
require "D:/PHPMailer-PHPMailer-2128d99/src/PHPMailer.php";
require "D:/PHPMailer-PHPMailer-2128d99/src/SMTP.php";

// Function to generate a random password
function generateRandomPassword($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $password .= $characters[$index];
    }
    return $password;
}

// Get email from POST request
$data = json_decode(file_get_contents("php://input"));
$email = $data->email ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($email)) {
    // Check if the email exists in the database
    $sql_check = "SELECT * FROM nguoi_dung WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Generate a new random password
        $newPassword = generateRandomPassword();

        // Update the user's password in the database
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql_update_email = "UPDATE nguoi_dung SET matkhau = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update_email);
        $stmt_update->bind_param("ss", $hashedPassword, $email);

        if ($stmt_update->execute()) {
            // Send the email with the new password
            $mail = new PHPMailer(true);

            try {
                // SMTP server configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'dh52003670@student.stu.edu.vn';
                $mail->Password = '123456Aa!'; // Use an app password for Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email configuration
                $mail->setFrom('dh52003670@student.stu.edu.vn', 'Your Name');
                $mail->addAddress($email);
                $mail->Subject = 'New Password';
                $mail->Body = 'Your new password: ' . $newPassword;

                // Send email
                if ($mail->send()) {
                    echo json_encode(['message' => 'Mật khẩu mới đã được gửi qua email.']);
                } else {
                    echo json_encode(['error' => 'Không thể gửi email.']);
                }
            } catch (Exception $e) {
                echo json_encode(['error' => 'Mailer Error: ' . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['error' => 'Không thể cập nhật mật khẩu.']);
        }
    } else {
        echo json_encode(['error' => 'Email không tồn tại.']);
    }
} else {
    echo json_encode(['error' => 'Yêu cầu không hợp lệ.']);
}
?>