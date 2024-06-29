<?php
// Include database configuration
include "../config.php";

// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Default filter values
$filterType = isset($data['filterType']) ? $data['filterType'] : 'all';
$filterValue = isset($data['filterValue']) ? $data['filterValue'] : null;

$response = array();

// Debugging: Log received filter type and value
error_log("Filter Type: " . $filterType);
error_log("Filter Value: " . $filterValue);

// Fetch total number of orders with filter
$sql_orders = "SELECT COUNT(*) as total_orders FROM don_dat_hang WHERE trangthai = 'giaohangthanhcong'";
if ($filterType == 'date' && $filterValue) {
    $sql_orders .= " AND ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {
    $sql_orders .= " AND WEEK(ngaydat)= '$filterValue' ";
} elseif ($filterType == 'month' && $filterValue) {

    $sql_orders .= " AND MONTH(ngaydat) = '$filterValue' ";
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
join don_dat_hang ddh on ctdh.madon = ddh.madon WHERE 
  ddh.trangthai = 'giaohangthanhcong'";
if ($filterType == 'date' && $filterValue) {
    $sql_revenue .= " AND ddh.ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {

    $sql_revenue .= " AND WEEK(ddh.ngaydat) - 1 = '$filterValue' ";
} elseif ($filterType == 'month' && $filterValue) {

    $sql_revenue .= " AND  MONTH(ddh.ngaydat) = '$filterValue' ";
}
$result_revenue = $conn->query($sql_revenue);

if ($result_revenue->num_rows > 0) {
    $row_revenue = $result_revenue->fetch_assoc();
    $response['total_revenue'] = $row_revenue['total_revenue'];
} else {
    $response['total_revenue'] = 0;
}

echo json_encode($response);
?>