$sql_get_orders = "
SELECT ddh.maDon AS MaDon, ddh.ngaydat AS NgayDat, ddh.trangthai AS TrangThai,
nd.diachi AS DiaChi, nd.email as Email, nd.tenKH as TenKH, nd.sdt as SoDienThoai,
GROUP_CONCAT(s.maSach) AS MaSach, GROUP_CONCAT(s.tenSach) AS TenSach,
GROUP_CONCAT(ctdh.SoLuong) AS SoLuong, GROUP_CONCAT(ctdh.DonGia) AS DonGia,
GROUP_CONCAT(s.hinhAnh) AS HinhAnh
FROM don_dat_hang ddh
INNER JOIN chi_tiet_don_hang ctdh ON ddh.madon = ctdh.madon
INNER JOIN sach s ON ctdh.MaSach = s.maSach
INNER JOIN nguoi_dung nd ON nd.maND = ddh.maND
WHERE ddh.trangthai IN ('choduyet', 'danggiao', 'daduyet', 'dahuy', 'giaohangthanhcong')
GROUP BY ddh.maDon
";

$result = $conn->query($sql_get_orders);

if ($result->num_rows > 0) {
$orders = [];
while ($row = $result->fetch_assoc()) {
// Convert comma-separated string to array
$row['MaSach'] = explode(',', $row['MaSach']);
$row['TenSach'] = explode(',', $row['TenSach']);
$row['SoLuong'] = explode(',', $row['SoLuong']);
$row['DonGia'] = explode(',', $row['DonGia']);
$row['HinhAnh'] = explode(',', $row['HinhAnh']);
$orders[] = $row;
}
echo json_encode($orders);
} else {
echo json_encode([]);
}

$conn->close();