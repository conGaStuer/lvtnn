<?php
include "../config.php";

$filterType = $_POST['filterType'];
$filterValue = $_POST['filterValue'];

$sql = "SELECT MaDon, NgayDat, SUM(SoLuong * DonGia) AS TongTien FROM don_dat_hang d
        JOIN chi_tiet_don_hang c ON d.MaDon = c.MaDon
        WHERE 1=1";

if ($filterType == "day") {
    $sql .= " AND DATE(NgayDat) = '$filterValue'";
} elseif ($filterType == "week") {
    $sql .= " AND WEEK(NgayDat, 1) = WEEK('$filterValue', 1)";
} elseif ($filterType == "month") {
    $sql .= " AND MONTH(NgayDat) = MONTH('$filterValue') AND YEAR(NgayDat) = YEAR('$filterValue')";
}

$sql .= " GROUP BY MaDon, NgayDat ORDER BY NgayDat DESC";

$result = $conn->query($sql);

$invoices = [];

while ($row = $result->fetch_assoc()) {
    $invoices[] = $row;
}

echo json_encode($invoices);

$conn->close();
?>