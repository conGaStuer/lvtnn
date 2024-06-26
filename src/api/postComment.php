<?php

include ("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $maND = $data['userId'];
    $noidung = $data['content'];
    $maSach = $data['bookId'];
    $rating = $data['rating']; // Thêm trường rating

    // Debugging: Print the input values
    error_log("UserId: " . $maND);
    error_log("Content: " . $noidung);
    error_log("BookId: " . $maSach);
    error_log("Rating: " . $rating); // In ra giá trị rating

    // Check if the user ID exists in the nguoi_dung table
    $check_user_sql = "SELECT COUNT(*) FROM nguoi_dung WHERE maND = '$maND'";
    $result_user = $conn->query($check_user_sql);
    $user_exists = $result_user->fetch_row()[0];

    // Check if the book ID exists in the sach table
    $check_book_sql = "SELECT COUNT(*) FROM sach WHERE maSach = '$maSach'";
    $result_book = $conn->query($check_book_sql);
    $book_exists = $result_book->fetch_row()[0];

    if ($user_exists > 0 && $book_exists > 0) {
        $sql_comment = "INSERT INTO danh_gia(maND, noidung, ngayDG, maSach,rating) VALUES ('$maND', '$noidung', NOW(), '$maSach','$rating')";

        if ($conn->query($sql_comment) === TRUE) {
            echo json_encode("Danh gia thanh cong");
        } else {
            echo json_encode("Danh gia khong thanh cong: " . $conn->error);
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