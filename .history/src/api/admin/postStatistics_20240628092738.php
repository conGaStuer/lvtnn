<?php
// Include database configuration
include "../config.php";

// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Default filter values
$filterType = isset($data['filterType']) ? $data['filterType'] : 'all';
$filterValue = isset($data['filterValue']) ? $data['filterValue'] : null;

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



switch ($filterType) {
    case 'day':
        if ($filterValue) {
            $sql_orders .= " AND DATE(ddh.ngaydat) = ?";
        }
        break;
    case 'week':
        $sql_orders .= " AND YEARWEEK(ddh.ngaydat, 1) = YEARWEEK(NOW(), 1)";
        break;
    case 'month':
        $sql_orders .= " AND MONTH(ddh.ngaydat) = MONTH(NOW()) AND YEAR(ddh.ngaydat) = YEAR(NOW())";
        break;
    default:
        // 'all' - no additional filtering needed
        break;
}

$sql_orders .= "
    GROUP BY ddh.madon
    ORDER BY ddh.madon DESC
";

$stmt = $conn->prepare($sql_orders);

// Bind parameters for date filters, if applicable
if ($filterType === 'day' && $filterValue) {
    $stmt->bind_param("s", $filterValue);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    echo json_encode($response);

} else {
    echo json_encode(['error' => 'Error executing SQL query']); // Handle SQL execution error
}