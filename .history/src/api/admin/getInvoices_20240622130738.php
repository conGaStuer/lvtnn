<?php
// Include database configuration
include "../config.php";

// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Default filter values
$filterType = isset($data['filterType']) ? $data['filterType'] : 'all';
$filterValue = isset($data['filterValue']) ? $data['filterValue'] : null;

// SQL query to fetch invoices with optional date filtering and status filter
$sql_get_invoices = "
    SELECT ddh.maDon AS MaDon, 
           GROUP_CONCAT(s.maSach) AS MaSach, 
           GROUP_CONCAT(s.tenSach) AS TenSach, 
           GROUP_CONCAT(s.hinhAnh) AS HinhAnh, 
           SUM(ctdh.SoLuong) AS SoLuong, 
           SUM(ctdh.DonGia) AS TongTien,
           ddh.ngayDat AS NgayDat, 
           ddh.maND AS MaND, 
           u.tenNguoiDung AS TenNguoiDung, 
           u.sdt AS SDT,
           ddh.trangthai AS TrangThai
    FROM don_dat_hang ddh
    INNER JOIN chi_tiet_don_hang ctdh ON ddh.madon = ctdh.madon
    INNER JOIN sach s ON ctdh.MaSach = s.maSach
    INNER JOIN users u ON ddh.maND = u.maND
    WHERE ddh.trangthai = 'giaohangthanhcong'";

// Apply date filters based on filterType
switch ($filterType) {
    case 'day':
        if ($filterValue) {
            $sql_get_invoices .= " AND DATE(ddh.ngayDat) = ?";
        }
        break;
    case 'week':
        $sql_get_invoices .= " AND YEARWEEK(ddh.ngayDat, 1) = YEARWEEK(NOW(), 1)";
        break;
    case 'month':
        $sql_get_invoices .= " AND MONTH(ddh.ngayDat) = MONTH(NOW()) AND YEAR(ddh.ngayDat) = YEAR(NOW())";
        break;
    default:
        // 'all' - no additional filtering needed
        break;
}

$sql_get_invoices .= "
    GROUP BY ddh.maDon
    ORDER BY ddh.maDon DESC
";

$stmt = $conn->prepare($sql_get_invoices);

// Bind parameters for date filters
if ($filterType === 'day' && $filterValue) {
    $stmt->bind_param("s", $filterValue);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    if ($result->num_rows > 0) {
        $invoices = [];
        while ($row = $result->fetch_assoc()) {
            // Convert string to array for multi-value fields
            $row['MaSach'] = explode(',', $row['MaSach']);
            $row['TenSach'] = explode(',', $row['TenSach']);
            $row['HinhAnh'] = explode(',', $row['HinhAnh']);

            $invoices[] = $row;
        }
        echo json_encode($invoices);
    } else {
        echo json_encode([]); // No invoices found
    }
} else {
    echo json_encode(['error' => 'Error executing SQL query']); // Handle SQL execution error
}

$stmt->close();
$conn->close();
?>