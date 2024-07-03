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
    ctdh.maSach AS MaSach,
    s.tenSach AS TenSach,
    s.hinhAnh AS HinhAnh,
    ctdh.donGia AS DonGia,
    ctdh.soLuong AS SoLuong,
    ctdh.donGia * ctdh.soLuong AS GiaDonHang,
    GROUP_CONCAT(DISTINCT dm.tenDM ORDER BY dm.tenDM) AS DanhMuc,
    tg.tenTG AS TacGia,
    nn.tenNN AS NgonNgu,
    km.luongKM AS KhuyenMai,
    nxb.tenNXB AS NhaXuatBan
FROM don_dat_hang ddh
INNER JOIN chi_tiet_don_hang ctdh ON ddh.maDon = ctdh.maDon
INNER JOIN sach s ON ctdh.maSach = s.maSach
INNER JOIN tg_sach ts ON s.maSach = ts.maSach
INNER JOIN tac_gia tg ON ts.maTG = tg.maTG
INNER JOIN nn_sach nns ON s.maSach = nns.maSach
INNER JOIN ngon_ngu nn ON nns.maNN = nn.maNN
INNER JOIN dm_sach dms ON s.maSach = dms.maSach
INNER JOIN danh_muc dm ON dms.maDM = dm.maDM
INNER JOIN khuyen_mai km ON km.maKM = s.maKM
INNER JOIN nha_xuat_ban nxb ON nxb.maNXB = s.maNXB
WHERE ddh.maND = '$userId' AND ddh.trangthai = '$status'
GROUP BY ddh.maDon, ctdh.maSach
";

$result = $conn->query($sql_get_orders);

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        // Explode concatenated fields into arrays
        $row['DanhMuc'] = explode(',', $row['DanhMuc']);

        $orderIndex = array_search($row['MaDon'], array_column($orders, 'MaDon'));
        if ($orderIndex === false) {
            $orders[] = [
                'MaDon' => $row['MaDon'],
                'TrangThai' => $row['TrangThai'],
                'NgayDat' => $row['NgayDat'],
                'Items' => [
                    [
                        'MaSach' => $row['MaSach'],
                        'TenSach' => $row['TenSach'],
                        'HinhAnh' => $row['HinhAnh'],
                        'DonGia' => $row['DonGia'],
                        'SoLuong' => $row['SoLuong'],
                        'GiaDonHang' => $row['GiaDonHang'],
                        'DanhMuc' => implode(', ', array_unique($row['DanhMuc'])),
                        'TacGia' => $row['TacGia'],
                        'NgonNgu' => $row['NgonNgu'],
                        'KhuyenMai' => $row['KhuyenMai'],
                        'NhaXuatBan' => $row['NhaXuatBan']
                    ]
                ]
            ];
        } else {
            $orders[$orderIndex]['Items'][] = [
                'MaSach' => $row['MaSach'],
                'TenSach' => $row['TenSach'],
                'HinhAnh' => $row['HinhAnh'],
                'DonGia' => $row['DonGia'],
                'SoLuong' => $row['SoLuong'],
                'GiaDonHang' => $row['GiaDonHang'],
                'DanhMuc' => implode(', ', array_unique($row['DanhMuc'])),
                'TacGia' => $row['TacGia'],
                'NgonNgu' => $row['NgonNgu'],
                'KhuyenMai' => $row['KhuyenMai'],
                'NhaXuatBan' => $row['NhaXuatBan']
            ];
        }
    }
    echo json_encode($orders);
} else {
    echo json_encode([]);
}

$conn->close();
?>