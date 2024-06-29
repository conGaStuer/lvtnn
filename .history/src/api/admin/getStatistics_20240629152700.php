<?php
include "../config.php";

// Array to hold the response data
$response = array();

// Fetch total number of orders
$sql_orders = "SELECT COUNT(*) as total_orders FROM don_dat_hang";
$result_orders = $conn->query($sql_orders);

if ($result_orders->num_rows > 0) {
    $row_orders = $result_orders->fetch_assoc();
    $response['total_orders'] = $row_orders['total_orders'];
} else {
    $response['total_orders'] = 0;
}



// Fetch total revenue (sum of all orders)
$sql_revenue = "SELECT SUM(chi_tiet_don_hang.donGia * chi_tiet_don_hang.soLuong) as total_revenue FROM chi_tiet_don_hang";
$result_revenue = $conn->query($sql_revenue);

if ($result_revenue->num_rows > 0) {
    $row_revenue = $result_revenue->fetch_assoc();
    $response['total_revenue'] = $row_revenue['total_revenue'];
} else {
    $response['total_revenue'] = 0;
}

// Return response in JSON format
echo json_encode($response);

$conn->close();
?>