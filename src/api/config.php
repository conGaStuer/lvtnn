<?php


header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ domain nào
header("Access-Control-Allow-Methods: POST, GET"); // Cho phép các phương thức POST, GET và OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Các header được phép


$server = "localhost";
$username = "root";
$password = "";
$database = "lvtn";


// Các cài đặt khác của bạn ở đây

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}