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
        $commentId = $row['maDG'];
        // Tạo một key dựa trên mã đánh giá để nhóm các phản hồi cùng mã lại với nhau
        if (!isset($comments[$commentId])) {
            $comments[$commentId] = array(
                'noidung' => $row,
                'replyTo' => array()
            );
        } else {
            // Nếu đã có, thêm vào mảng các phản hồi
            $comments[$commentId]['replyTo'][] = $row;
        }
    }
}

// Chuyển đổi mảng kết quả thành dạng JSON
$response = array_values($comments);
echo json_encode($response);

$conn->close();
?>