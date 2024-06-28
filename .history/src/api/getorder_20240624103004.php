<?php
include "config.php";

// Lấy dữ liệu từ request POST
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$status = $data['status'];

// Truy vấn danh sách đơn hàng
$sql_get_orders = "
SELECT ddh.maDon AS MaDon, ddh.trangthai AS TrangThai, ddh.ngayDat AS NgayDat, 
GROUP_CONCAT(COALESCE(s.maSach, '')) AS MaSach, 
GROUP_CONCAT(COALESCE(s.tenSach, '')) AS TenSach, 
GROUP_CONCAT(COALESCE(s.hinhAnh, '')) AS HinhAnh,
GROUP_CONCAT(COALESCE(s.donGia, 0)) AS DonGia, 
GROUP_CONCAT(COALESCE(ctdh.SoLuong, 0)) AS SoLuong, 
GROUP_CONCAT(COALESCE(ctdh.DonGia, 0)) AS GiaDonHang,
GROUP_CONCAT(COALESCE(tg.tenTG, '')) AS TacGia, 
GROUP_CONCAT(COALESCE(nn.tenNN, '')) AS NgonNgu, 
GROUP_CONCAT(COALESCE(dm.tenDM, '')) AS DanhMuc,
GROUP_CONCAT(COALESCE(km.luongKM, '')) AS KhuyenMai

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
WHERE ddh.maND = '$userId' AND ddh.trangthai IN ('choduyet', 'danggiao', 'daduyet', 'dahuy', 'giaohangthanhcong')
GROUP BY ddh.maDon
";

$result = $conn->query($sql_get_orders);

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        // Ensure fields are converted to arrays if they contain comma-separated values
        $row['TacGia'] = explode(',', $row['TacGia']);
        $row['NgonNgu'] = explode(',', $row['NgonNgu']);
        $orders[] = $row;
    }
    echo json_encode($orders);
} else {
    echo json_encode([]);
}

$conn->close();
?>