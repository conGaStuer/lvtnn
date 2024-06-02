<?php

include "config.php";

if (isset($_GET['category']) && isset($_GET['id'])) {
    $category = $_GET['category'];
    $currentBookId = $_GET['id'];
    $sql_get_related = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet, nxbs.tenNXB AS NhaXuatBan,
    s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia, tg.tenTG AS TacGia, nn.tenNN AS NgonNgu, dm.tenDM AS DanhMuc
   FROM sach AS s
   INNER JOIN tg_sach AS ts ON s.maSach = ts.maSach
   INNER JOIN tac_gia AS tg ON ts.maTG = tg.maTG
   INNER JOIN nn_sach AS nns ON s.maSach = nns.maSach
   INNER JOIN ngon_ngu AS nn ON nns.maNN = nn.maNN
   INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
   INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM 
   INNER JOIN nha_xuat_ban AS nxbs ON s.maNXB = nxbs.maNXB

   WHERE dm.tenDM = '$category' AND s.maSach != '$currentBookId'
 LIMIT 4";

    $result = $conn->query($sql_get_related);
    $relatedBooks = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $relatedBooks[] = $row;
        }
        echo json_encode($relatedBooks);
    } else {
        echo json_encode(array("message" => "No related books found!"));
    }
} else {
    echo json_encode(array("message" => "Category not specified!"));
}

$conn->close();
?>