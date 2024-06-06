<?php

include "../config.php";

$sql_get = "
SELECT km.maKM AS MaKhuyenMai, km.luongKM AS LuongKhuyenMai, 
GROUP_CONCAT(s.maSach) AS MaSach, GROUP_CONCAT(s.tenSach SEPARATOR ', ') AS TenSach
FROM khuyen_mai AS km
LEFT JOIN sach AS s ON s.maKM = km.maKM
GROUP BY km.maKM";

$result = $conn->query($sql_get);
$discounts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $discounts[] = $row;
    }
    echo json_encode($discounts);
} else {
    echo json_encode(array("error" => "Khong lay duoc du lieu khuyen mai"));
}
?>