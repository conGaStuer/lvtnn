<?php
include "../config.php";

// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

$filterType = isset($data['filterType']) ? $data['filterType'] : 'all';
$filterValue = isset($data['filterValue']) ? $data['filterValue'] : null;

// Base SQL query
$sql_get_invoices = "
    SELECT ddh.maDon AS MaDon, ddh.ngayDat AS NgayDat, ddh.tongTien AS TongTien, 
           nd.maND AS MaND, nd.tenNguoiDung AS TenNguoiDung, nd.SDT AS SDT
    FROM don_dat_hang ddh
    INNER JOIN nguoi_dung nd ON ddh.maND = nd.maND
    WHERE 1=1
";

// Adding filters based on the filter type
if ($filterType === 'day' && $filterValue) {
    $sql_get_invoices .= " AND DATE(ddh.ngayDat) = ?";
} elseif ($filterType === 'week' && $filterValue) {
    $startOfWeek = date('Y-m-d', strtotime($filterValue));
    $endOfWeek = date('Y-m-d', strtotime($filterValue . ' +6 days'));
    $sql_get_invoices .= " AND DATE(ddh.ngayDat) BETWEEN ? AND ?";
} elseif ($filterType === 'month' && $filterValue) {
    $sql_get_invoices .= " AND DATE_FORMAT(ddh.ngayDat, '%Y-%m') = ?";
}

$sql_get_invoices .= " ORDER BY ddh.maDon DESC";

$stmt = $conn->prepare($sql_get_invoices);

if ($filterType === 'day' && $filterValue) {
    $stmt->bind_param("s", $filterValue);
} elseif ($filterType === 'week' && $filterValue) {
    $stmt->bind_param("ss", $startOfWeek, $endOfWeek);
} elseif ($filterType === 'month' && $filterValue) {
    $stmt->bind_param("s", $filterValue);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $invoices = [];
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }
    echo json_encode($invoices);
} else {
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>