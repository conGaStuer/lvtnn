<?php
// Include database configuration
include "../config.php";

// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Default filter values
$filterType = isset($data['filterType']) ? $data['filterType'] : 'all';
$filterValue = isset($data['filterValue']) ? $data['filterValue'] : null;

$response = array();

// Fetch total number of orders with filter
$sql_orders = "SELECT COUNT(*) as total_orders FROM don_dat_hang";
if ($filterType == 'day' && $filterValue) {
    $sql_orders .= " WHERE ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {
    $startOfWeek = date('Y-m-d', strtotime($filterValue));
    $endOfWeek = date('Y-m-d', strtotime($filterValue . ' +6 days'));
    $sql_orders .= " WHERE ngaydat BETWEEN '$startOfWeek' AND '$endOfWeek'";
} elseif ($filterType == 'month' && $filterValue) {
    $startOfMonth = date('Y-m-01', strtotime($filterValue));
    $endOfMonth = date('Y-m-t', strtotime($filterValue));
    $sql_orders .= " WHERE ngaydat BETWEEN '$startOfMonth' AND '$endOfMonth'";
}

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

echo json_encode($response);
?>