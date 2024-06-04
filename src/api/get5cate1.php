<?php
// Kết nối đến cơ sở dữ liệu
include ("config.php");

// Mảng để lưu trữ dữ liệu
$data = array();

// Truy vấn để lấy mã danh mục từ 1 đến 5
$sql = "SELECT id FROM categories LIMIT 5"; // Thay categories bằng tên bảng lưu thể loại sách của bạn

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Lặp qua các dòng dữ liệu và thêm vào mảng
    while ($row = $result->fetch_assoc()) {
        $data[] = $row["id"];
    }
}

// Đóng kết nối
$conn->close();

// Trả về dữ liệu dưới dạng JSON
echo json_encode($data);
?>