<?php
include "config.php";

// Lấy dữ liệu từ request POST
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$status = $data['status'];

// Truy vấn danh sách đơn hàng
$sql_get_orders = "
SELECT ddh.maDon AS MaDon, ddh.trangthai AS TrangThai, ddh.ngayDat AS NgayDat,
       s.maSach AS MaSach, s.tenSach AS TenSach, s.hinhAnh AS HinhAnh,
       s.donGia AS DonGia, ctdh.SoLuong AS SoLuong,
       tg.tenTG AS TacGia, nn.tenNN AS NgonNgu, dm.tenDM AS DanhMuc,
       km.luongKM AS KhuyenMai

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