<?php
include ("config.php");

$bookId = $_GET['bookId'];
$sql = "SELECT dg.*, nd.taikhoan, nd.hinh FROM danh_gia dg
 JOIN nguoi_dung nd ON dg.maND = nd.maND 
 WHERE dg.maSach = '$bookId' ORDER BY dg.ngayDG DESC";

$result = $conn->query($sql);

$comments = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

echo json_encode($comments);
$conn->close();
?>