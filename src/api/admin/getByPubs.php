<?php

include "../config.php";

$sql_get = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet,
 s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia,  s.soLuong AS SoLuong,
    nxbs.maNXB AS MaNhaXuatBan,nxbs.tenNXB AS NhaXuatBan
FROM sach AS s
   INNER JOIN nha_xuat_ban AS nxbs ON s.maNXB = nxbs.maNXB
";

$result = $conn->query($sql_get);
$books = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Splitting concatenated fields into array

        $books[] = $row;
    }
    echo json_encode($books);
} else {
    echo json_encode(array("error" => "Khong get duoc sach"));
}