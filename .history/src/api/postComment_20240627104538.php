<?php

include ("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $maND = $data['MaKH'];
    $noidung = $data['noidung'];
    $maSach = $data['MaSach'];
    $rating = $data['rating']; // Thêm trường rating
    $replyTo = isset($data['replyTo']) ? $data['replyTo'] : null; // ID của bình luận gốc nếu có
    $isStaff = isset($data['isStaff']) ? $data['isStaff'] : false; // Kiểm tra vai trò của người dùng

    // Debugging: Print the input values
    error_log("UserId: " . $maND);
    error_log("Content: " . $noidung);
    error_log("BookId: " . $maSach);
    error_log("Rating: " . $rating); // In ra giá trị rating
    error_log("ReplyTo: " . $replyTo);
    error_log("IsStaff: " . $isStaff);

    // Check if the user ID exists in the nguoi_dung table
    $check_user_sql = "SELECT COUNT(*) FROM nguoi_dung WHERE maND = '$maND'";
    $result_user = $conn->query($check_user_sql);
    $user_exists = $result_user->fetch_row()[0];

    // Check if the book ID exists in the sach table
    $check_book_sql = "SELECT COUNT(*) FROM sach WHERE maSach = '$maSach'";
    $result_book = $conn->query($check_book_sql);
    $book_exists = $result_book->fetch_row()[0];

    if ($user_exists > 0 && $book_exists > 0) {
        // If replying to a comment (replyTo is not null), ensure the comment exists
        if ($replyTo) {
            $check_comment_sql = "SELECT COUNT(*) FROM danh_gia WHERE maDG = '$replyTo'";
            $result_comment = $conn->query($check_comment_sql);
            $comment_exists = $result_comment->fetch_row()[0];

            if ($comment_exists == 0) {
                echo json_encode("ReplyTo comment does not exist.");
                exit();
            }
        }

        $sql_comment = "INSERT INTO danh_gia(maND, noidung, ngayDG, maSach, rating, reply_to, is_staff) 
                        VALUES ('$maND', '$noidung', NOW(), '$maSach', '$rating', " . ($replyTo ? "'$replyTo'" : "NULL") . ", '$isStaff')";

        if ($conn->query($sql_comment) === TRUE) {
            echo json_encode("Đánh giá thành công");
        } else {
            echo json_encode("Đánh giá không thành công: " . $conn->error);
        }
    } else {
        if ($user_exists == 0) {
            echo json_encode("User ID does not exist.");
        }
        if ($book_exists == 0) {
            echo json_encode("Book ID does not exist.");
        }
    }
}

$conn->close();
?>