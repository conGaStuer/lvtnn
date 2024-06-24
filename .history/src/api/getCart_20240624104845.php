<?php

include "config.php";
$userId = $_GET["userId"];

$sql_get = "
SELECT 
    s.maSach AS MaSach, 
    s.chiTiet AS ChiTiet,
    s.tenSach AS TenSach,
    s.hinhAnh AS HinhAnh, 
    ctdh.dongia AS DonGia, 
    ctdh.soLuong AS SoLuong,
    km.luongKM AS KhuyenMai,
    nxbs.tenNXB AS NhaXuatBan,
    dm.maDM AS MaDanhMuc,
    tg.maTG as MaTacGia,
    nn.maNN as MaNgonNgu,
    nxbs.maNXB as MaNhaXuatBan,
    km.maKM as MaKhuyenMai,
    GROUP_CONCAT(DISTINCT tg.tenTG ORDER BY tg.tenTG SEPARATOR ', ') AS TacGia,
    GROUP_CONCAT(DISTINCT nn.tenNN ORDER BY nn.tenNN SEPARATOR ', ') AS NgonNgu,
    GROUP_CONCAT(DISTINCT dm.tenTG ORDER BY tg.tenTG SEPARATOR ', ') AS TacGia
FROM sach as s
INNER JOIN chi_tiet_don_hang AS ctdh ON s.maSach = ctdh.maSach
INNER JOIN don_dat_hang as ddh ON ctdh.madon = ddh.madon
INNER JOIN tg_sach AS ts ON s.maSach = ts.maSach
INNER JOIN tac_gia AS tg ON ts.maTG = tg.maTG
INNER JOIN nn_sach AS nns ON s.maSach = nns.maSach
INNER JOIN ngon_ngu AS nn ON nns.maNN = nn.maNN
INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM
INNER JOIN khuyen_mai AS km ON km.maKM = s.maKM
INNER JOIN nha_xuat_ban AS nxbs ON nxbs.maNXB = s.maNXB
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