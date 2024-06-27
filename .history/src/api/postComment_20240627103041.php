<?php
header('Content-Type: application/json');
include ("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ request POST
    $data = json_decode(file_get_contents("php://input"), true);
    $maND = $data['MaKH']; // Lấy mã người dùng từ dữ liệu POST
    $noidung = $data['noidung']; // Lấy nội dung bình luận từ dữ liệu POST
    $maSach = $data['MaSach']; // Lấy mã sách từ dữ liệu POST
    $rating = $data['rating']; // Lấy đánh giá từ dữ liệu POST

    // Debugging: In ra các giá trị từ request POST vào log
    error_log("UserID: " . $maND);
    error_log("Content: " . $noidung);
    error_log("BookID: " . $maSach);
    error_log("Rating: " . $rating);

    // Kiểm tra xem mã người dùng có tồn tại trong bảng nguoi_dung không
    $check_user_sql = "SELECT COUNT(*) FROM nguoi_dung WHERE maND = '$maND'";
    $result_user = $conn->query($check_user_sql);
    $user_exists = $result_user->fetch_row()[0];

    // Kiểm tra xem mã sách có tồn tại trong bảng sach không
    $check_book_sql = "SELECT COUNT(*) FROM sach WHERE maSach = '$maSach'";
    $result_book = $conn->query($check_book_sql);
    $book_exists = $result_book->fetch_row()[0];

    // Nếu cả mã người dùng và mã sách đều tồn tại trong cơ sở dữ liệu
    if ($user_exists > 0 && $book_exists > 0) {
        // Tạo truy vấn SQL để thêm bình luận vào bảng danh_gia
        $sql_comment = "INSERT INTO danh_gia (maND, noidung, ngayDG, maSach, rating) VALUES ('$maND', '$noidung', NOW(), '$maSach', '$rating')";

        // Thực thi truy vấn và kiểm tra kết quả
        if ($conn->query($sql_comment) === TRUE) {
            echo json_encode("Đánh giá thành công");
        } else {
            echo json_encode("Đánh giá không thành công: " . $conn->error);
        }
    } else {
        // Nếu mã người dùng không tồn tại
        if ($user_exists == 0) {
            echo json_encode("Mã người dùng không tồn tại.");
        }
        // Nếu mã sách không tồn tại
        if ($book_exists == 0) {
            echo json_encode("Mã sách không tồn tại.");
        }
    }
}

$conn->close();
?>