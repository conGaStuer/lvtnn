<?php
include "config.php";

// Lấy dữ liệu từ request POST
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$status = $data['status'];

// Truy vấn danh sách đơn hàng
$sql_get_orders = "
SELECT 
    ddh.maDon AS MaDon, 
    ddh.trangthai AS TrangThai, 
    ddh.ngayDat AS NgayDat, 
    GROUP_CONCAT(DISTINCT ctdh.masach) AS MaSach, 
    GROUP_CONCAT(DISTINCT nxb.tenNXB) AS NhaXuatBan,  
    GROUP_CONCAT(DISTINCT s.tenSach) AS TenSach, 
    GROUP_CONCAT(DISTINCT s.hinhAnh) AS HinhAnh,
    GROUP_CONCAT(DISTINCT s.donGia) AS DonGia, 
    GROUP_CONCAT(DISTINCT ctdh.SoLuong) AS SoLuong, 
    GROUP_CONCAT(DISTINCT ctdh.DonGia) AS GiaDonHang,
    GROUP_CONCAT(DISTINCT tg.tenTG) AS TacGia, 
    GROUP_CONCAT(DISTINCT nn.tenNN) AS NgonNgu, 
    GROUP_CONCAT(DISTINCT dm.tenDM) AS DanhMuc,
    GROUP_CONCAT(DISTINCT km.luongKM) AS KhuyenMai
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
INNER JOIN nha_xuat_ban nxb ON nxb.maNXB = s.maNXB
WHERE ddh.maND = '$userId' AND ddh.trangthai IN ('choduyet', 'danggiao', 'daduyet', 'dahuy', 'giaohangthanhcong')
GROUP BY ddh.maDon
";

$result = $conn->query($sql_get_orders);

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        // Explode concatenated fields into arrays
        $row['MaSach'] = explode(',', $row['MaSach']);
        $row['TenSach'] = explode(',', $row['TenSach']);
        $row['HinhAnh'] = explode(',', $row['HinhAnh']);
        $row['DonGia'] = explode(',', $row['DonGia']);
        $row['SoLuong'] = explode(',', $row['SoLuong']);
        $row['GiaDonHang'] = explode(',', $row['GiaDonHang']);
        $row['TacGia'] = explode(',', $row['TacGia']);
        $row['NgonNgu'] = explode(',', $row['NgonNgu']);
        $row['DanhMuc'] = explode(',', $row['DanhMuc']);
        $row['KhuyenMai'] = explode(',', $row['KhuyenMai']);

        $orders[] = $row;
    }
    echo json_encode($orders);
} else {
    echo json_encode([]);
}

$conn->close();
?>