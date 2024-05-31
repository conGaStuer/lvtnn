<?php
// verifyOTP.php

include "config.php";
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $data->email;
    $otp = $data->otp;
    $userId = $data->userId;

    // Retrieve OTP and email from the otp_codes table
    $stmt = $conn->prepare("SELECT otp_code, email FROM otp_codes WHERE email = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result(); // Store the result set
    $stmt->bind_result($stored_otp, $stored_email);
    $stmt->fetch();

    if ($otp == $stored_otp) {
        // Update the email in the `nguoi_dung` table
        $update = "UPDATE nguoi_dung SET email = ? WHERE taikhoan = ?";
        $stmt2 = $conn->prepare($update);
        $stmt2->bind_param("ss", $stored_email, $userId);
        if ($stmt2->execute()) {
            echo json_encode(['updateProfileSuccess' => true]);
        } else {
            echo json_encode(['updateProfileSuccess' => false]);
        }
        // Close statement 2
        $stmt2->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP']);
    }
    // Close statement 1
    $stmt->close();
}
?>