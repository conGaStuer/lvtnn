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
$sql_orders = "SELECT COUNT(*) as total_orders FROM don_dat_hang WHERE trangthai = 'giaohangthanhcong'";
if ($filterType == 'date' && $filterValue) {
    $sql_orders .= " AND ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {
    $sql_orders .= " AND WEEKOFYEAR(ngaydat) = '$filterValue'";
} elseif ($filterType == 'month' && $filterValue) {
    $sql_orders .= " AND MONTH(ngaydat) = '$filterValue'";
}

$result_orders = $conn->query($sql_orders);

if ($result_orders->num_rows > 0) {
    $row_orders = $result_orders->fetch_assoc();
    $response['total_orders'] = $row_orders['total_orders'];
} else {
    $response['total_orders'] = 0;
}

// Fetch total revenue (sum of all orders)
$sql_revenue = "SELECT SUM(ctdh.dongia * ctdh.soluong) as total_revenue FROM chi_tiet_don_hang ctdh
JOIN don_dat_hang ddh on ctdh.madon = ddh.madon WHERE ddh.trangthai = 'giaohangthanhcong'";
if ($filterType == 'date' && $filterValue) {
    $sql_revenue .= " AND ddh.ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {
    $sql_revenue .= " AND WEEKOFYEAR(ddh.ngaydat) = '$filterValue'";
} elseif ($filterType == 'month' && $filterValue) {
    $sql_revenue .= " AND MONTH(ddh.ngaydat) = '$filterValue'";
}

$result_revenue = $conn->query($sql_revenue);

if ($result_revenue->num_rows > 0) {
    $row_revenue = $result_revenue->fetch_assoc();
    $response['total_revenue'] = $row_revenue['total_revenue'];
} else {
    $response['total_revenue'] = 0;
}

// Fetch total number of unique users who have made purchases
$sql_users = "SELECT COUNT(DISTINCT ddh.maND) as total_users FROM don_dat_hang ddh WHERE ddh.trangthai= 'giaohangthanhcong'";
$result_users = $conn->query($sql_users);

if ($result_users->num_rows > 0) {
    $row_users = $result_users->fetch_assoc();
    $response['total_users'] = $row_users['total_users'];
} else {
    $response['total_users'] = 0;
}

// Fetch total quantity of items sold
$sql_quan = "SELECT SUM(ctdh.soluong) as total_quan FROM chi_tiet_don_hang ctdh
JOIN don_dat_hang ddh on ddh.madon = ctdh.madon
WHERE ddh.trangthai = 'giaohangthanhcong'";
$result_quan = $conn->query($sql_quan);

if ($result_quan->num_rows > 0) {
    $row_quan = $result_quan->fetch_assoc();
    $response['total_quan'] = $row_quan['total_quan'];
} else {
    $response['total_quan'] = 0;
}

// Return response in JSON format
echo json_encode($response);

$conn->close();
?>