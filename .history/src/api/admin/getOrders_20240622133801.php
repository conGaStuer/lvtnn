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
    GROUP_CONCAT(COALESCE(s.maSach, '')) AS MaSach, 
    GROUP_CONCAT(COALESCE(s.tenSach, '')) AS TenSach, 
    GROUP_CONCAT(COALESCE(s.hinhAnh, '')) AS HinhAnh,
    GROUP_CONCAT(COALESCE(s.donGia, 0)) AS DonGia, 
    GROUP_CONCAT(COALESCE(ctdh.SoLuong, 0)) AS SoLuong, 
    GROUP_CONCAT(COALESCE(ctdh.DonGia, 0)) AS GiaDonHang,
    GROUP_CONCAT(COALESCE(tg.tenTG, '')) AS TacGia, 
    GROUP_CONCAT(COALESCE(nn.tenNN, '')) AS NgonNgu, 
    GROUP_CONCAT(COALESCE(dm.tenDM, '')) AS DanhMuc
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
WHERE ddh.maND = '$userId' AND ddh.trangthai IN ('choduyet', 'danggiao', 'daduyet', 'dahuy', 'giaohangthanhcong')
GROUP BY ddh.maDon
";

$result = $conn->query($sql_get_orders);

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        // Chuyển các chuỗi thành mảng
        $maSach = explode(',', $row['MaSach']);
        $tenSach = explode(',', $row['TenSach']);
        $hinhAnh = explode(',', $row['HinhAnh']);
        $donGia = explode(',', $row['DonGia']);
        $soLuong = explode(',', $row['SoLuong']);
        $giaDonHang = explode(',', $row['GiaDonHang']);
        $tacGia = explode(',', $row['TacGia']);
        $ngonNgu = explode(',', $row['NgonNgu']);
        $danhMuc = explode(',', $row['DanhMuc']);

        // Gộp các sách có cùng mã đơn thành một mảng dữ liệu
        $combinedBooks = [];
        $numBooks = count($maSach);
        for ($i = 0; $i < $numBooks; $i++) {
            $book = [
                'MaSach' => $maSach[$i],
                'TenSach' => $tenSach[$i],
                'HinhAnh' => $hinhAnh[$i],
                'DonGia' => $donGia[$i],
                'SoLuong' => $soLuong[$i],
                'GiaDonHang' => $giaDonHang[$i],
                'TacGia' => $tacGia[$i],
                'NgonNgu' => $ngonNgu[$i],
                'DanhMuc' => $danhMuc[$i],
            ];
            $combinedBooks[] = $book;
        }

        // Tạo một mảng mới để chứa dữ liệu đã được xử lý
        $order = [
            'MaDon' => $row['MaDon'],
            'TrangThai' => $row['TrangThai'],
            'NgayDat' => $row['NgayDat'],
            'Sach' => $combinedBooks, // Sử dụng mảng đã gộp
        ];
        $orders[] = $order;
    }
    echo json_encode($orders);
} else {
    echo json_encode([]);
}

$conn->close();
?>