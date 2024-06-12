<?php

include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data from the request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract data from the JSON object
    $maSach = $data['MaSach'];
    $tenSach = $data['TenSach'];
    $donGia = $data['DonGia'];
    $soLuong = $data['SoLuong'];
    $chiTiet = $data['ChiTiet'];
    $hinhAnh = $data['HinhAnh'];
    $nhaXuatBan = $data['NhaXuatBan'];
    $khuyenMai = $data['KhuyenMai'];
    $danhMuc = $data['DanhMuc'];
    $tacGia = $data['TacGia']; // Nhận giá trị là một chuỗi hoặc một mảng
    $ngonNgu = $data['NgonNgu']; // Nhận giá trị là một chuỗi hoặc một mảng

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
    $sql_check_book_name = "SELECT * FROM sach WHERE tenSach = '$tenSach' AND maSach != '$maSach'";
    $result_book_name = $conn->query($sql_check_book_name);

    if ($result_book_name->num_rows > 0) {
        echo json_encode("Sách bị trùng tên");
    } else {
        // Update book details in the database
        $sql_update_book = "UPDATE sach SET 
            tenSach = '$tenSach', 
            donGia = '$donGia', 
            soLuong = '$soLuong', 
            chiTiet = '$chiTiet', 
            hinhAnh = '$hinhAnh', 
            maNXB = '$nhaXuatBan', 
            maKM = '$khuyenMai'
            WHERE maSach = '$maSach'";

        if ($conn->query($sql_update_book) === true) {
            // Update danhMuc
            $conn->query("DELETE FROM dm_sach WHERE maSach = '$maSach'");
            if (is_array($danhMuc)) {
                foreach ($danhMuc as $category) {
                    $sql_insert_category = "INSERT INTO dm_sach (maSach, maDM) VALUES ('$maSach', '$category')";
                    $conn->query($sql_insert_category);
                }
            } else {
                $sql_insert_category = "INSERT INTO dm_sach (maSach, maDM) VALUES ('$maSach', '$danhMuc')";
                $conn->query($sql_insert_category);
            }

            // Update tacGia
            $conn->query("DELETE FROM tg_sach WHERE maSach = '$maSach'");
            if (is_array($tacGia)) {
                foreach ($tacGia as $author) {
                    $sql_insert_author = "INSERT INTO tg_sach (maSach, maTG) VALUES ('$maSach', '$author')";
                    $conn->query($sql_insert_author);
                }
            } else {
                $sql_insert_author = "INSERT INTO tg_sach (maSach, maTG) VALUES ('$maSach', '$tacGia')";
                $conn->query($sql_insert_author);
            }

            // Update ngonNgu
            $conn->query("DELETE FROM nn_sach WHERE maSach = '$maSach'");
            if (is_array($ngonNgu)) {
                foreach ($ngonNgu as $language) {
                    $sql_insert_language = "INSERT INTO nn_sach (maSach, maNN) VALUES ('$maSach', '$language')";
                    $conn->query($sql_insert_language);
                }
            } else {
                $sql_insert_language = "INSERT INTO nn_sach (maSach, maNN) VALUES ('$maSach', '$ngonNgu')";
                $conn->query($sql_insert_language);
            }

            echo json_encode("Cap Nhat Thanh Cong");
        } else {
            echo json_encode("Cập nhật thất bại");
        }
    }
    $conn->close();
}
?>