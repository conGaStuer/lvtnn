<?php

include "../config.php";

$sql_get = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet,
 s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia,  s.soLuong AS SoLuong,
    nn.maNN AS MaNgonNgu,nn.tenNN AS NgonNgu,

 GROUP_CONCAT(nn.tenNN) AS NgonNgu
FROM sach AS s
INNER JOIN nn_sach AS nns ON s.maSach = nns.maSach
INNER JOIN ngon_ngu AS nn ON nns.maNN = nn.maNN

GROUP BY s.maSach";

$result = $conn->query($sql_get);
$books = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Splitting concatenated fields into arrays

        $row['NgonNgu'] = explode(',', $row['NgonNgu']);

        // Converting arrays to comma-separated strings

        $row['NgonNgu'] = implode(', ', $row['NgonNgu']);

        $books[] = $row;
    }
    echo json_encode($books);
} else {
    echo json_encode(array("error" => "Khong get duoc sach"));
}