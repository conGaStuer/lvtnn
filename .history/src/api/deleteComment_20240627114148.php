<?php

header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ domain nào
header("Access-Control-Allow-Methods: POST, GET"); // Cho phép các phương thức POST, GET và OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Các header được phép

// Kết nối tới cơ sở dữ liệu MySQL
$server = "localhost";
$username = "root";
$password = "";
$database = "lvtn";

$conn = new mysqli($server, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý request POST từ front-end
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra nếu commentId được cung cấp
if (!empty($data->commentId)) {
    $commentId = $data->commentId;

    // Xây dựng câu truy vấn xóa bình luận từ bảng danh_gia
    $query = "DELETE FROM danh_gia WHERE maDG = ?";

    // Chuẩn bị và thực thi câu truy vấn sử dụng MySQLi
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $commentId); // i là kiểu dữ liệu integer

    if ($stmt->execute()) {
        // Trả về thành công nếu xóa thành công
        http_response_code(200);
        echo json_encode(array("message" => "Bình luận đã được xóa thành công"));
    } else {
        // Trả về lỗi nếu xóa thất bại
        http_response_code(500);
        echo json_encode(array("message" => "Đã xảy ra lỗi khi xóa bình luận"));
    }
} else {
    // Trả về lỗi nếu commentId không được cung cấp
    http_response_code(400);
    echo json_encode(array("message" => "Thiếu thông tin commentId"));
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>