<?php

include ("config.php");

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $sql = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet, nxbs.tenNXB AS NhaXuatBan,
    s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia, tg.tenTG AS TacGia,tg.maTG AS  MaTacGia, 
    nn.maNN AS MaNgonNgu,
    nn.tenNN AS NgonNgu, dm.tenDM AS DanhMuc,
    km.luongKM AS KhuyenMai
   FROM sach AS s
   INNER JOIN tg_sach AS ts ON s.maSach = ts.maSach
   INNER JOIN tac_gia AS tg ON ts.maTG = tg.maTG
   INNER JOIN nn_sach AS nns ON s.maSach = nns.maSach
   INNER JOIN ngon_ngu AS nn ON nns.maNN = nn.maNN
   INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
   INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM 
   INNER JOIN nha_xuat_ban AS nxbs ON s.maNXB = nxbs.maNXB
   INNER JOIN khuyen_mai AS km ON s.maKM = km.maKM

   WHERE  s.tenSach LIKE '%$keyword%' 
   or dm.tenDM  LIKE '%$keyword%'
   or nn.tenNN  LIKE '%$keyword%'
   or tg.tenTG LIKE '%$keyword%'
   or
   s.chiTiet LIKE '%$keyword%'
   ";
    $books = [];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        echo json_encode($books);
    } else {
        echo json_encode("Deo co du lieu");
    }

}