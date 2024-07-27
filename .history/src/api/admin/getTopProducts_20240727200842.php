<?php
include "../config.php";

$response = array();

$sql_top_products = "SELECT ctdh.masach as id, s.tenSach as name, SUM(ctdh.soluong) as unitsSold,
 SUM(ctdh.dongia * ctdh.soluong) as revenue ,
 s.hinhAnh as HinhAnh, s.soLuong as SoLuong
 FROM chi_tiet_don_hang ctdh 
 JOIN sach s ON ctdh.masach = s.maSach
  WHERE ctdh.madon IN (SELECT madon FROM don_dat_hang WHERE trangthai = 'giaohangthanhcong') 
  GROUP BY ctdh.masach ORDER BY unitsSold DESC LIMIT 5";

$result_top_products = $conn->query($sql_top_products);

if ($result_top_products->num_rows > 0) {
    while ($row = $result_top_products->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    $response = [];
}

echo json_encode($response);
$conn->close();
?>