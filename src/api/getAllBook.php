<?php

include "config.php";

$sql_get = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet,
 s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia, 
 GROUP_CONCAT(tg.tenTG) AS TacGia, 
 GROUP_CONCAT(nn.tenNN) AS NgonNgu, 
 GROUP_CONCAT(dm.tenDM) AS DanhMuc
FROM sach AS s
INNER JOIN tg_sach AS ts ON s.maSach = ts.maSach
INNER JOIN tac_gia AS tg ON ts.maTG = tg.maTG
INNER JOIN nn_sach AS nns ON s.maSach = nns.maSach
INNER JOIN ngon_ngu AS nn ON nns.maNN = nn.maNN
INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM
GROUP BY s.maSach";

$result = $conn->query($sql_get);
$books = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Splitting concatenated fields into arrays
        $row['TacGia'] = explode(',', $row['TacGia']);
        $row['NgonNgu'] = explode(',', $row['NgonNgu']);
        $row['DanhMuc'] = explode(',', $row['DanhMuc']);

        // Converting arrays to comma-separated strings
        $row['TacGia'] = implode(', ', $row['TacGia']);
        $row['NgonNgu'] = implode(', ', $row['NgonNgu']);
        $row['DanhMuc'] = implode(', ', $row['DanhMuc']);

        $books[] = $row;
    }
    echo json_encode($books);
} else {
    echo json_encode(array("error" => "Khong get duoc sach"));
}
