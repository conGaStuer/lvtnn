<?php
// Include database configuration
include "../config.php";

// SQL query to fetch users and their total number of orders
$sql_get = "
    SELECT nguoi_dung.*, COUNT(don_dat_hang.madon) AS tongSoDonHang
    FROM nguoi_dung
    LEFT JOIN don_dat_hang ON nguoi_dung.maND = don_dat_hang.maND
    where maND = 1 or maND =2
    GROUP BY nguoi_dung.maND
";

$result = $conn->query($sql_get);
$users = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode(array("error" => "Không lấy được dữ liệu"));
}

$conn->close();
?>