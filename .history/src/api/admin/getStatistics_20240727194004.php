<?php
include "../config.php";

// Array to hold the response data
$response = array();

// Fetch the list of orders
$sql_orders = "SELECT madon, ngaydat 
FROM don_dat_hang WHERE trangthai = 'giaohangthanhcong'";
$result_orders = $conn->query($sql_orders);

$response['orders'] = array();
if ($result_orders->num_rows > 0) {
    while ($row_orders = $result_orders->fetch_assoc()) {
        $response['orders'][] = array(
            'madon' => $row_orders['madon'],
            'ngaydat' => $row_orders['ngaydat']
        );
    }
    $response['total_orders'] = count($response['orders']);
} else {
    $response['total_orders'] = 0;
    $response['orders'] = [];
}

// Fetch the list of order details for revenue calculation
$sql_revenue_details = "SELECT ddh.madon, ddh.ngaydat, SUM(ctdh.dongia * ctdh.soluong) as revenue 
FROM chi_tiet_don_hang ctdh
JOIN don_dat_hang ddh ON ddh.madon = ctdh.madon
WHERE ddh.trangthai = 'giaohangthanhcong'
GROUP BY ddh.madon, ddh.ngaydat";
$result_revenue = $conn->query($sql_revenue_details);

$response['revenue_details'] = array();
if ($result_revenue->num_rows > 0) {
    while ($row_revenue = $result_revenue->fetch_assoc()) {
        $response['revenue_details'][] = array(
            'madon' => $row_revenue['madon'],
            'ngaydat' => $row_revenue['ngaydat'],
            'revenue' => $row_revenue['revenue']
        );
    }
} else {
    $response['revenue_details'] = [];
}

// Fetch the list of users who have placed orders
$sql_users_details = "SELECT DISTINCT ddh.maND 
FROM don_dat_hang ddh
WHERE ddh.trangthai = 'giaohangthanhcong'";
$result_users = $conn->query($sql_users_details);

$response['user_details'] = array();
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

// Fetch the list of item quantities sold
$sql_quantity_details = "SELECT ddh.madon, ddh.ngaydat, SUM(ctdh.soLuong) as quantity 
FROM chi_tiet_don_hang ctdh
JOIN don_dat_hang ddh ON ddh.madon = ctdh.madon
WHERE ddh.trangthai = 'giaohangthanhcong'
GROUP BY ddh.madon, ddh.ngaydat";
$result_quan = $conn->query($sql_quantity_details);

$response['quantity_details'] = array();
if ($result_quan->num_rows > 0) {
    while ($row_quan = $result_quan->fetch_assoc()) {
        $response['quantity_details'][] = array(
            'madon' => $row_quan['madon'],
            'ngaydat' => $row_quan['ngaydat'],
            'quantity' => $row_quan['quantity']
        );
    }
} else {
    $response['quantity_details'] = [];
}

// Return response in JSON format
echo json_encode($response);

$conn->close();
?>