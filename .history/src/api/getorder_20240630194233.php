<?php
// Include your database configuration
include "config.php";

// Retrieve POST data
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$status = $data['status'];

// SQL query to fetch orders with details
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
    GROUP_CONCAT(dm.tenDM ORDER BY ctdh.masach) AS DanhMuc,
    GROUP_CONCAT(km.luongKM ORDER BY ctdh.masach) AS KhuyenMai
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
        // Initialize an associative array to store books by their ID
        $booksById = [];

        // Iterate through each book detail retrieved
        foreach (explode(',', $row['MaSach']) as $index => $maSach) {
            // Check if the book ID already exists in $booksById
            if (!isset($booksById[$maSach])) {
                // Initialize the book entry if it doesn't exist
                $booksById[$maSach] = [
                    'MaSach' => $maSach,
                    'TenSach' => explode(',', $row['TenSach'])[$index],
                    'HinhAnh' => explode(',', $row['HinhAnh'])[$index],
                    'DonGia' => explode(',', $row['DonGia'])[$index],
                    'SoLuong' => explode(',', $row['SoLuong'])[$index],
                    'GiaDonHang' => explode(',', $row['GiaDonHang'])[$index],
                    'TacGia' => explode(',', $row['TacGia'])[$index],
                    'NgonNgu' => explode(',', $row['NgonNgu'])[$index],
                    'DanhMuc' => [], // Initialize an array to store categories
                    'KhuyenMai' => explode(',', $row['KhuyenMai'])[$index],
                    'NhaXuatBan' => explode(',', $row['NhaXuatBan'])[$index],
                ];
            }

            // Add the category to the book entry (if it doesn't already exist)
            $danhMuc = trim(explode(',', $row['DanhMuc'])[$index]);
            if (!in_array($danhMuc, $booksById[$maSach]['DanhMuc'])) {
                $booksById[$maSach]['DanhMuc'][] = $danhMuc;
            }
        }

        // Convert associative array to indexed array of books and add to orders array
        $orders[] = array_values($booksById);
    }

    // Output JSON encoded orders array
    echo json_encode($orders);
} else {
    echo json_encode([]);
}

$conn->close();
?>