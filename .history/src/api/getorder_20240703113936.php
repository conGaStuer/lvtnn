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
    GROUP_CONCAT(ctdh.masach ORDER BY ctdh.masach) AS MaSach, 
    GROUP_CONCAT(nxb.tenNXB ORDER BY ctdh.masach) AS NhaXuatBan,  
    GROUP_CONCAT(s.tenSach ORDER BY ctdh.masach) AS TenSach, 
    GROUP_CONCAT(s.hinhAnh ORDER BY ctdh.masach) AS HinhAnh,
    GROUP_CONCAT(s.donGia ORDER BY ctdh.masach) AS DonGia, 
    GROUP_CONCAT(ctdh.SoLuong ORDER BY ctdh.masach) AS SoLuong, 
    GROUP_CONCAT(ctdh.DonGia ORDER BY ctdh.masach) AS GiaDonHang,
    GROUP_CONCAT(tg.tenTG ORDER BY ctdh.masach) AS TacGia, 
    GROUP_CONCAT(nn.tenNN ORDER BY ctdh.masach) AS NgonNgu, 
    GROUP_CONCAT(km.luongKM ORDER BY ctdh.masach) AS KhuyenMai,
    GROUP_CONCAT(DISTINCT dm.tenDM ORDER BY dm.tenDM) AS DanhMuc
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
GROUP BY ddh.maDon, ctdh.masach
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
        $row['KhuyenMai'] = explode(',', $row['KhuyenMai']);
        $row['NhaXuatBan'] = explode(',', $row['NhaXuatBan']);

        // Convert DanhMuc (categories) into array and aggregate by book
        $row['DanhMuc'] = array_map('trim', explode(',', $row['DanhMuc']));
        $danhMucByBook = [];
        foreach ($row['MaSach'] as $key => $maSach) {
            if (!isset($danhMucByBook[$maSach])) {
                $danhMucByBook[$maSach] = [];
            }
            $danhMucByBook[$maSach][] = $row['DanhMuc'][$key];
        }

        // Process categories into a single entry per book
        foreach ($danhMucByBook as $maSach => $categories) {
            $row['DanhMuc'][$key] = implode(', ', array_unique($categories));
        }

        // Trim whitespace from other array values
        $row['MaSach'] = array_map('trim', $row['MaSach']);
        $row['TenSach'] = array_map('trim', $row['TenSach']);
        $row['HinhAnh'] = array_map('trim', $row['HinhAnh']);
        $row['TacGia'] = array_map('trim', $row['TacGia']);
        $row['NgonNgu'] = array_map('trim', $row['NgonNgu']);
        $row['KhuyenMai'] = array_map('trim', $row['KhuyenMai']);
        $row['NhaXuatBan'] = array_map('trim', $row['NhaXuatBan']);

        // Convert numeric strings to integers where necessary
        $row['SoLuong'] = array_map('intval', $row['SoLuong']);
        $row['GiaDonHang'] = array_map('intval', $row['GiaDonHang']);

        $orders[] = $row;
    }
    echo json_encode($orders);
} else {
    echo json_encode([]);
}

$conn->close();
?>