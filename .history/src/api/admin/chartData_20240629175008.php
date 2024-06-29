<?php
// Include database configuration
include "../config.php";

// Array to hold chart data
$chartData = [
    'revenue' => [],
    'orders' => []
];

// Query for revenue data
$sql_revenue = "SELECT MONTH(ngayLap) AS month, SUM(tongTien) AS revenue
                FROM don_dat_hang
                WHERE YEAR(ngayLap) = YEAR(CURDATE())
                GROUP BY MONTH(ngayLap)";
$result_revenue = $conn->query($sql_revenue);

if ($result_revenue->num_rows > 0) {
    while ($row = $result_revenue->fetch_assoc()) {
        $chartData['revenue'][] = [
            'month' => $row['month'],
            'revenue' => $row['revenue']
        ];
    }
}

// Query for orders data
$sql_orders = "SELECT MONTH(ngayLap) AS month, COUNT(*) AS orders
               FROM don_dat_hang
               WHERE YEAR(ngayLap) = YEAR(CURDATE())
               GROUP BY MONTH(ngayLap)";
$result_orders = $conn->query($sql_orders);

if ($result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        $chartData['orders'][] = [
            'month' => $row['month'],
            'orders' => $row['orders']
        ];
    }
}

// Output the chart data as JSON
echo json_encode($chartData);

// Close the database connection
$conn->close();
?>