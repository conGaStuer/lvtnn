<?php
// Include database configuration
include "../config.php";

// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Default filter values
$filterType = isset($data['filterType']) ? $data['filterType'] : 'all';
$filterValue = isset($data['filterValue']) ? $data['filterValue'] : null;

$response = array();
$response['orders'] = array();
$response['quantity_details'] = array();
$response['revenue_details'] = array();
$response['user_details'] = array();

// Fetch the list of orders with filter
$sql_orders = "SELECT madon, ngaydat, maND 
FROM don_dat_hang WHERE trangthai = 'giaohangthanhcong'";
if ($filterType == 'date' && $filterValue) {
    $sql_orders .= " AND ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {
    $sql_orders .= " AND WEEKOFYEAR(ngaydat) = '$filterValue'";
} elseif ($filterType == 'month' && $filterValue) {
    $sql_orders .= " AND MONTH(ngaydat) = '$filterValue'";
}

$result_orders = $conn->query($sql_orders);

if ($result_orders->num_rows > 0) {
    while ($row_orders = $result_orders->fetch_assoc()) {
        $response['orders'][] = array(
            'madon' => $row_orders['madon'],
            'ngaydat' => $row_orders['ngaydat'],
            'maND' => $row_orders['maND']
        );
    }
    $response['total_orders'] = count($response['orders']);
} else {
    $response['total_orders'] = 0;
    $response['orders'] = [];
}

// Fetch quantity details with filter
$sql_quantity = "SELECT ddh.madon, ddh.ngaydat, SUM(ctdh.soluong) as quantity 
FROM chi_tiet_don_hang ctdh
JOIN don_dat_hang ddh ON ddh.madon = ctdh.madon
WHERE ddh.trangthai = 'giaohangthanhcong'";
if ($filterType == 'date' && $filterValue) {
    $sql_quantity .= " AND ddh.ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {
    $sql_quantity .= " AND WEEKOFYEAR(ddh.ngaydat) = '$filterValue'";
} elseif ($filterType == 'month' && $filterValue) {
    $sql_quantity .= " AND MONTH(ddh.ngaydat) = '$filterValue'";
}
$sql_quantity .= " GROUP BY ddh.madon, ddh.ngaydat";

$result_quantity = $conn->query($sql_quantity);

if ($result_quantity->num_rows > 0) {
    while ($row_quantity = $result_quantity->fetch_assoc()) {
        $response['quantity_details'][] = array(
            'madon' => $row_quantity['madon'],
            'ngaydat' => $row_quantity['ngaydat'],
            'quantity' => $row_quantity['quantity']
        );
    }
    $response['total_quantity'] = array_sum(array_column($response['quantity_details'], 'quantity'));
} else {
    $response['total_quantity'] = 0;
    $response['quantity_details'] = [];
}

// Fetch revenue details with filter
$sql_revenue = "SELECT ddh.madon, ddh.ngaydat, SUM(ctdh.dongia * ctdh.soluong) as revenue 
FROM chi_tiet_don_hang ctdh
JOIN don_dat_hang ddh ON ddh.madon = ctdh.madon
WHERE ddh.trangthai = 'giaohangthanhcong'";
if ($filterType == 'date' && $filterValue) {
    $sql_revenue .= " AND ddh.ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {
    $sql_revenue .= " AND WEEKOFYEAR(ddh.ngaydat) = '$filterValue'";
} elseif ($filterType == 'month' && $filterValue) {
    $sql_revenue .= " AND MONTH(ddh.ngaydat) = '$filterValue'";
}
$sql_revenue .= " GROUP BY ddh.madon, ddh.ngaydat";

$result_revenue = $conn->query($sql_revenue);

if ($result_revenue->num_rows > 0) {
    while ($row_revenue = $result_revenue->fetch_assoc()) {
        $response['revenue_details'][] = array(
            'madon' => $row_revenue['madon'],
            'ngaydat' => $row_revenue['ngaydat'],
            'revenue' => $row_revenue['revenue']
        );
    }
    $response['total_revenue'] = array_sum(array_column($response['revenue_details'], 'revenue'));
} else {
    $response['total_revenue'] = 0;
    $response['revenue_details'] = [];
}

// Fetch total number of unique users with filter
$sql_users = "SELECT DISTINCT maND FROM don_dat_hang WHERE trangthai = 'giaohangthanhcong'";
if ($filterType == 'date' && $filterValue) {
    $sql_users .= " AND ngaydat = '$filterValue'";
} elseif ($filterType == 'week' && $filterValue) {
    $sql_users .= " AND WEEKOFYEAR(ngaydat) = '$filterValue'";
} elseif ($filterType == 'month' && $filterValue) {
    $sql_users .= " AND MONTH(ngaydat) = '$filterValue'";
}

$result_users = $conn->query($sql_users);

if ($result_users->num_rows > 0) {
    while ($row_users = $result_users->fetch_assoc()) {
        $response['user_details'][] = array(
            'maND' => $row_users['maND']
        );
    }
    $response['total_users'] = count($response['user_details']);
} else {
    $response['total_users'] = 0;
    $response['user_details'] = [];
}

// Return response in JSON format
echo json_encode($response);

$conn->close();
?>