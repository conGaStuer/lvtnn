<?php

include "config.php";
$userId = $_GET["userId"];

$sql_get = "SELECT ddh.maDon AS MaDon, ddh.trangthai AS TrangThai, ddh.ngayDat AS NgayDat, 
ctdh.masach AS MaSach, 
nxb.tenNXB AS NhaXuatBan,
GROUP_CONCAT(COALESCE(s.tenSach, '')) AS TenSach, 
GROUP_CONCAT(COALESCE(s.hinhAnh, '')) AS HinhAnh,
GROUP_CONCAT(COALESCE(s.donGia, 0)) AS DonGia, 
GROUP_CONCAT(COALESCE(ctdh.SoLuong, 0)) AS SoLuong, 
GROUP_CONCAT(COALESCE(ctdh.DonGia, 0)) AS GiaDonHang,
GROUP_CONCAT(COALESCE(tg.tenTG, '')) AS TacGia, 
GROUP_CONCAT(COALESCE(nn.tenNN, '')) AS NgonNgu, 
GROUP_CONCAT(COALESCE(dm.tenDM, '')) AS DanhMuc,
GROUP_CONCAT(COALESCE(km.luongKM, '')) AS KhuyenMai

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
WHERE ddh.maND = '$userId'
GROUP BY s.maSach

";
$result = $conn->query($sql_get);

if ($result->num_rows > 0) {
    $books = array();
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    echo json_encode($books);
} else {
    echo json_encode(array("error" => "Khong get duoc danh muc"));
}
?>