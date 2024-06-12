<?php

include "../config.php";

$sql_get = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet,
 s.tenSach AS TenSach ,s.hinhAnh AS HinhAnh, s.donGia AS DonGia,  s.soLuong AS SoLuong,
    dm.maDM AS MaDanhMuc,dm.tenDM AS DanhMuc,

 GROUP_CONCAT(dm.tenDM) AS DanhMuc
FROM sach AS s
INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM

GROUP BY s.maSach";

$result = $conn->query($sql_get);
$books = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Splitting concatenated fields into arrays

        $row['DanhMuc'] = explode(',', $row['DanhMuc']);

        // Converting arrays to comma-separated strings

        $row['DanhMuc'] = implode(', ', $row['DanhMuc']);

        $books[] = $row;
    }
    echo json_encode($books);
} else {
    echo json_encode(array("error" => "Khong get duoc sach"));
}