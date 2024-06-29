<?php
// Include database configuration
include "../config.php";

// Array to hold chart data
$chartData = [
    'revenue' => [],
    'orders' => []
];

// Query for revenue data
$sql_revenue = "SELECT MONTH(ngaydat) AS month, SUM(ctdh.dongia * ctdh.soluong) as total_revenue
                FROM don_dat_hang ddh join chi_tiet_don_hang ctdh
                on ddh.madon = ctdh.madon
                WHERE YEAR(ngaydat) = YEAR(CURDATE())
                GROUP BY MONTH(ngaydat)";
$result_revenue = $conn->query($sql_revenue);

if ($result_revenue->num_rows > 0) {
    while ($row = $result_revenue->fetch_assoc()) {
        $chartData['revenue'][] = [
            'month' => $row['month'],
            'total_revenue' => $row['total_revenue'],
        ];
    }
}

// Query for orders data
$sql_orders = "SELECT MONTH(ngaydat) AS month, COUNT(*) AS orders
               FROM don_dat_hang
               WHERE YEAR(ngaydat) = YEAR(CURDATE())
               GROUP BY MONTH(ngaydat)";
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