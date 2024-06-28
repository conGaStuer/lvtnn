<?php
include "../config.php";

// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

$filterType = isset($data['filterType']) ? $data['filterType'] : 'all';
$filterValue = isset($data['filterValue']) ? $data['filterValue'] : null;

// SQL query to fetch invoices with optional date filtering and status filter
$sql_get_invoices = "
    SELECT ddh.maDon AS MaDon, s.maSach AS MaSach, s.tenSach AS TenSach, s.hinhAnh AS HinhAnh, 
           s.donGia AS DonGia, km.luongKM AS KhuyenMai, GROUP_CONCAT(tg.tenTG SEPARATOR ', ') AS TacGia, 
           GROUP_CONCAT(nn.tenNN SEPARATOR ', ') AS NgonNgu, GROUP_CONCAT(dm.tenDM SEPARATOR ', ') AS DanhMuc,
           ctdh.SoLuong AS SoLuong, ctdh.DonGia AS DonGia, ddh.trangthai AS TrangThai, ddh.maND AS MaND,
           ddh.ngayDat AS NgayDat
    FROM don_dat_hang ddh
    INNER JOIN chi_tiet_don_hang ctdh ON ddh.madon = ctdh.madon
    INNER JOIN sach s ON ctdh.MaSach = s.maSach
    LEFT JOIN tg_sach ts ON s.maSach = ts.maSach
    LEFT JOIN tac_gia tg ON ts.maTG = tg.maTG
    LEFT JOIN nn_sach nns ON s.maSach = nns.maSach
    LEFT JOIN ngon_ngu nn ON nns.maNN = nn.maNN
    LEFT JOIN dm_sach dms ON s.maSach = dms.maSach
    LEFT JOIN danh_muc dm ON dms.maDM = dm.maDM
    LEFT JOIN khuyen_mai km ON km.maKM = s.maKM
    WHERE 1=1
";

// Filter by status 'giaohangthanhcong'
$sql_get_invoices .= " AND ddh.trangthai = 'giaohangthanhcong'";

// Apply date filters based on filterType
switch ($filterType) {
    case 'day':
        if ($filterValue) {
            $sql_get_invoices .= " AND ddh.ngayDat = ?";
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
    GROUP BY ddh.maDon, s.maSach, ctdh.SoLuong, ctdh.DonGia
    ORDER BY ddh.maDon DESC
";

$stmt = $conn->prepare($sql_get_invoices);

// Bind parameters for date filters
if ($filterType === 'day' && $filterValue) {
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