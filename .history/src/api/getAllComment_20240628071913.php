<?php
include ("config.php");

$bookId = $_GET['bookId'];
$sql = "SELECT dg.*, nd.taikhoan, nd.hinh FROM danh_gia dg
 JOIN nguoi_dung nd ON dg.maND = nd.maND 
 WHERE dg.maSach = '$bookId' ";

$result = $conn->query($sql);

$comments = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $maDG = $row['maDG'];
        $reply_to = $row['reply_to'];

        // Kiểm tra xem có phải là câu trả lời hay không
        if ($reply_to === null) {
            // Đánh giá chính
            $comments[$maDG] = $row;
            $comments[$maDG]['replies'] = array();
        } else {
            // Câu trả lời
            if (isset($comments[$reply_to])) {
                $comments[$reply_to]['replies'][] = $row;
            } else {
                // Nếu không tìm thấy đánh giá cha (nên xử lý lỗi hoặc bỏ qua)
            }
        }
    }
}

// Chuyển đổi mảng kết quả sang mảng một chiều
$response = array_values($comments);

echo json_encode($response);
$conn->close();
?>