<?php
include "config.php";

$sql_get = "SELECT DISTINCT dm.tenDM AS DanhMuc
FROM sach AS s
INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM";

$result = $conn->query($sql_get);

if ($result->num_rows > 0) {
    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    echo json_encode($categories);
} else {
    echo json_encode(array("error" => "Khong get duoc danh muc"));
}
?>