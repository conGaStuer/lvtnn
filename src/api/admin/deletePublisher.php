<?php
// deletePublisher.php

// Include database connection
require_once '../config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $data = json_decode(file_get_contents("php://input"), true);
    $maNXB = $data['maNXB'];

    $sql_del2 = "DELETE FROM nha_xuat_ban where maNXB = '$maNXB'";
    $res1 = $conn->query($sql_del2);



    echo json_encode(array("status" => "success"));
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

// Close the database connection
$conn->close();
?>