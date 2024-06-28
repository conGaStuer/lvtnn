<?php
include "config.php";

// Lấy dữ liệu từ request POST
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$status = $data['status'];

// Truy vấn danh sách đơn hàng
$sql_get_orders = "
SELECT ddh.maDon AS MaDon, ddh.ngayDat AS NgayDat, ddh.trangthai AS TrangThai, 
SUM(ctdh.SoLuong * ctdh.DonGia) AS TongTien, 
GROUP_CONCAT(CONCAT(
  '{\"MaSach\": \"', s.maSach, '\", ',
  '\"TenSach\": \"', s.tenSach, '\", ',
  '\"HinhAnh\": \"', s.hinhAnh, '\", ',
  '\"DonGia\": ', ctdh.DonGia, ', ',
  '\"SoLuong\": ', ctdh.SoLuong, ', ',
  '\"TacGia\": \"', IFNULL(GROUP_CONCAT(DISTINCT tg.tenTG SEPARATOR ', '), ''), '\", ',
  '\"NgonNgu\": \"', IFNULL(GROUP_CONCAT(DISTINCT nn.tenNN SEPARATOR ', '), ''), '\", ',
  '\"DanhMuc\": \"', IFNULL(GROUP_CONCAT(DISTINCT dm.tenDM SEPARATOR ', '), ''), '\"}'
) SEPARATOR '|') AS items
FROM don_dat_hang ddh
INNER JOIN chi_tiet_don_hang ctdh ON ddh.madon = ctdh.madon
INNER JOIN sach s ON ctdh.MaSach = s.maSach
LEFT JOIN tg_sach ts ON s.maSach = ts.maSach
LEFT JOIN tac_gia tg ON ts.maTG = tg.maTG
LEFT JOIN nn_sach nns ON s.maSach = nns.maSach
LEFT JOIN ngon_ngu nn ON nns.maNN = nn.maNN
LEFT JOIN dm_sach dms ON s.maSach = dms.maSach
LEFT JOIN danh_muc dm ON dms.maDM = dm.maDM
WHERE ddh.maND = ? AND (ddh.trangthai = 'choduyet' OR ddh.trangthai = 'danggiao'
OR ddh.trangthai = 'daduyet' OR ddh.trangthai ='dahuy' OR ddh.trangthai ='giaohangthanhcong')
GROUP BY ddh.maDon
";

$stmt = $conn->prepare($sql_get_orders);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $row['items'] = array_map('json_decode', explode('|', $row['items']));
        $orders[] = $row;
    }
    echo json_encode($orders);
} else {
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>