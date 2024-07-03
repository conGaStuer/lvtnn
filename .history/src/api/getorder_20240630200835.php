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
    ctdh.masach AS MaSach, 
    nxb.tenNXB AS NhaXuatBan,  
    s.tenSach AS TenSach, 
    s.hinhAnh AS HinhAnh,
    s.donGia AS DonGia, 
    ctdh.SoLuong AS SoLuong, 
    ctdh.DonGia AS GiaDonHang,
    tg.tenTG AS TacGia, 
    nn.tenNN AS NgonNgu, 
    dm.tenDM AS DanhMuc,
    km.luongKM AS KhuyenMai
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
ORDER BY ddh.maDon, ctdh.masach
";

$result = $conn->query($sql_get_orders);

if ($result->num_rows > 0) {
    $orders = [];
    $current_order_id = null;
    $current_order = null;

    while ($row = $result->fetch_assoc()) {
        // Check if this row is for a new order or the same order
        if ($row['MaDon'] !== $current_order_id) {
            // If it's a new order, push the current order (if exists) to the orders array
            if ($current_order !== null) {
                $orders[] = $current_order;
            }

            // Initialize a new order
            $current_order_id = $row['MaDon'];
            $current_order = [
                'MaDon' => $row['MaDon'],
                'TrangThai' => $row['TrangThai'],
                'NgayDat' => $row['NgayDat'],
                'ChiTiet' => []
            ];
        }

        // Add book details to the current order's ChiTiet array
        $current_order['ChiTiet'][] = [
            'MaSach' => $row['MaSach'],
            'NhaXuatBan' => $row['NhaXuatBan'],
            'TenSach' => $row['TenSach'],
            'HinhAnh' => $row['HinhAnh'],
            'DonGia' => $row['DonGia'],
            'SoLuong' => $row['SoLuong'],
            'GiaDonHang' => $row['GiaDonHang'],
            'TacGia' => $row['TacGia'],
            'NgonNgu' => $row['NgonNgu'],
            'DanhMuc' => $row['DanhMuc'],
            'KhuyenMai' => $row['KhuyenMai']
        ];
    }

    // Don't forget to push the last order into the orders array
    if ($current_order !== null) {
        $orders[] = $current_order;
    }

    echo json_encode($orders);
} else {
    echo json_encode([]);
}

$conn->close();
?>