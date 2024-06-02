<?php

include "config.php";

if (isset($_GET['authorId'])) {
    $authorId = $_GET['authorId'];
    $sql_get_author = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet, nxbs.tenNXB AS NhaXuatBan,
    s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia, tg.tenTG AS TacGia,tg.maTG AS  MaTacGia, nn.tenNN AS NgonNgu, dm.tenDM AS DanhMuc
   FROM sach AS s
   INNER JOIN tg_sach AS ts ON s.maSach = ts.maSach
   INNER JOIN tac_gia AS tg ON ts.maTG = tg.maTG
   INNER JOIN nn_sach AS nns ON s.maSach = nns.maSach
   INNER JOIN ngon_ngu AS nn ON nns.maNN = nn.maNN
   INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
   INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM 
   INNER JOIN nha_xuat_ban AS nxbs ON s.maNXB = nxbs.maNXB

   WHERE ts.maTG = '$authorId'";
    $books = [];

    $result = $conn->query($sql_get_author);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        echo json_encode($books);
    } else {
        json_encode(['Error' => "Invalid Book Author"]);

    }
    $conn->close();
}

