<?php

include "../config.php";

$sql_get = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet,
 s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia,  s.soLuong AS SoLuong,
    tg.maTG AS MaTacGia,tg.tenTG AS TacGia,

 GROUP_CONCAT(tg.tenTG) AS TacGia
FROM sach AS s
INNER JOIN tg_sach AS tgs ON s.maSach = tgs.maSach
INNER JOIN tac_gia AS tg ON tgs.maTG = tg.maTG
    
GROUP BY s.maSach";

$result = $conn->query($sql_get);
$books = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Splitting concatenated fields into arrays

        $row['TacGia'] = explode(',', $row['TacGia']);

        // Converting arrays to comma-separated strings

        $row['TacGia'] = implode(', ', $row['TacGia']);

        $books[] = $row;
    }
    echo json_encode($books);
} else {
    echo json_encode(array("error" => "Khong get duoc sach"));
}