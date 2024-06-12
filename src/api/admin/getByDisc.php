<?php

include "../config.php";

$sql_get = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet,
 s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia,  s.soLuong AS SoLuong,
    km.maKM AS MaKhuyenMai,km.luongKM AS KhuyenMai
FROM sach AS s
   INNER JOIN khuyen_mai AS km ON s.maKM = km.maKM
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