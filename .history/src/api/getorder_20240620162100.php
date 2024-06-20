include "config.php";

// Lấy dữ liệu từ request POST
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$status = $data['status'];

// Truy vấn danh sách đơn hàng
$sql_get_orders = "
SELECT ddh.maDon AS MaDon, s.maSach AS MaSach, s.tenSach AS TenSach, s.hinhAnh AS HinhAnh,
s.donGia AS DonGia, km.luongKM AS KhuyenMai, GROUP_CONCAT(tg.tenTG) AS TacGia,
GROUP_CONCAT(nn.tenNN) AS NgonNgu, GROUP_CONCAT(dm.tenDM) AS DanhMuc,
ctdh.SoLuong AS SoLuong, ctdh.DonGia AS DonGia, ddh.trangthai AS TrangThai, ddh.maND AS MaND
FROM don_dat_hang ddh
INNER JOIN chi_tiet_don_hang ctdh ON ddh.madon = ctdh.madon
INNER JOIN sach s ON ctdh.MaSach = s.maSach
INNER JOIN tg_sach ts ON s.maSach = ts.maSach
INNER JOIN tac_gia tg ON ts.maTG = tg.maTG
INNER JOIN nn_sach nns ON s.maSach = nns.maSach
INNER JOIN ngon_ngu nn ON nns.maNN = nn.maNN
INNER JOIN dm_sach dms ON s.maSach = dms.maSach
INNER JOIN danh_muc dm ON dms.maDM = dm.maDM
INNER JOIN khuyen_mai km ON km.maKM = s.maKM
WHERE ddh.maND = '$userId' AND (ddh.trangthai = 'choduyet' OR ddh.trangthai = 'danggiao'
OR ddh.trangthai = 'daduyet' OR ddh.trangthai ='dahuy' OR ddh.trangthai ='giaohangthanhcong')
GROUP BY ddh.maDon, s.maSach, ctdh.SoLuong, ctdh.DonGia
";

$result = $conn->query($sql_get_orders);

if ($result->num_rows > 0) {
$orders = [];
while ($row = $result->fetch_assoc()) {
$orders[] = $row;
}
echo json_encode($orders);
} else {
echo json_encode([]);
}

$conn->close();
?>