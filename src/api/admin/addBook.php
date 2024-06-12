<?php

include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data from the request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract data from the JSON object
    $tenSach = $data['TenSach'];
    $donGia = $data['DonGia'];
    $soLuong = $data['SoLuong'];
    $chiTiet = $data['ChiTiet'];
    $hinhAnh = $data['HinhAnh'];
    $nhaXuatBan = $data['NhaXuatBan'];
    $khuyenMai = $data['KhuyenMai'];
    $danhMuc = $data['DanhMuc'];
    $tacGia = $data['TacGia'];
    $ngonNgu = $data['NgonNgu'];

    // Validate the existence of NhaXuatBan and KhuyenMai
    $sql_check_nxb = "SELECT * FROM nha_xuat_ban WHERE maNXB = '$nhaXuatBan'";
    $result_check_nxb = $conn->query($sql_check_nxb);
    if ($result_check_nxb->num_rows == 0) {
        echo json_encode("NhaXuatBan không tồn tại");
        $conn->close();
        exit();
    }

    $sql_check_km = "SELECT * FROM khuyen_mai WHERE maKM = '$khuyenMai'";
    $result_check_km = $conn->query($sql_check_km);
    if ($result_check_km->num_rows == 0) {
        echo json_encode("KhuyenMai không tồn tại");
        $conn->close();
        exit();
    }

    // Check for duplicate book name
    $sql_check_book_name = "SELECT * FROM sach WHERE tenSach = '$tenSach'";
    $result_book_name = $conn->query($sql_check_book_name);

    if ($result_book_name->num_rows > 0) {
        echo json_encode("Sách bị trùng tên");
    } else {
        // Insert new book into the database
        $sql_insert_new_book = "INSERT INTO sach (tenSach, donGia, soLuong, chiTiet, hinhAnh, maNXB, maKM)
                                VALUES ('$tenSach', '$donGia', '$soLuong', '$chiTiet', '$hinhAnh', '$nhaXuatBan', '$khuyenMai')";
        if ($conn->query($sql_insert_new_book) === true) {
            // Get the last inserted book ID
            $last_id = $conn->insert_id;

            // Insert danhMuc
            foreach ($danhMuc as $category) {
                $sql_insert_category = "INSERT INTO dm_sach (maSach, maDM) VALUES ('$last_id', '$category')";
                $conn->query($sql_insert_category);
            }

            // Insert tacGia
            foreach ($tacGia as $author) {
                $sql_insert_author = "INSERT INTO tg_sach (maSach, maTG) VALUES ('$last_id', '$author')";
                $conn->query($sql_insert_author);
            }

            // Insert ngonNgu
            $sql_insert_language = "INSERT INTO nn_sach (maSach, maNN) VALUES ('$last_id', '$ngonNgu')";
            $conn->query($sql_insert_language);

            echo json_encode("Them Thanh Cong");
        } else {
            echo json_encode("Thêm thất bại");
        }
    }
    $conn->close();
}
?>