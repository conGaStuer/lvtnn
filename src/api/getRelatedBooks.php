<?php

include "config.php";

if (isset($_GET['category']) && isset($_GET['id'])) {
    $currentBookId = $_GET['id'];

    // Lấy danh mục của sách hiện tại
    $sql_get_categories = "SELECT dm.tenDM FROM sach AS s
                           INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
                           INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM
                           WHERE s.maSach = '$currentBookId'";

    $categories_result = $conn->query($sql_get_categories);

    if ($categories_result->num_rows > 0) {
        $categories = array();
        while ($category_row = $categories_result->fetch_assoc()) {
            $categories[] = $category_row['tenDM'];
        }

        // Chuyển danh sách các danh mục thành chuỗi phân cách bằng dấu ','
        $category_string = "'" . implode("','", $categories) . "'";

        // Truy vấn sách liên quan dựa trên danh mục của sách hiện tại
        $sql_get_related = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet, nxbs.tenNXB AS NhaXuatBan,
                            s.tenSach AS TenSach, s.hinhAnh AS HinhAnh, s.donGia AS DonGia, tg.tenTG AS TacGia,
                            nn.tenNN AS NgonNgu, GROUP_CONCAT(DISTINCT dm.tenDM) AS DanhMuc,
    km.luongKM AS KhuyenMai
                            FROM sach AS s
                            INNER JOIN tg_sach AS ts ON s.maSach = ts.maSach
                            INNER JOIN tac_gia AS tg ON ts.maTG = tg.maTG
                            INNER JOIN nn_sach AS nns ON s.maSach = nns.maSach
                            INNER JOIN ngon_ngu AS nn ON nns.maNN = nn.maNN
                            INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
                            INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM 
                            INNER JOIN nha_xuat_ban AS nxbs ON s.maNXB = nxbs.maNXB
                               INNER JOIN khuyen_mai AS km ON s.maKM = km.maKM

                            WHERE dm.tenDM IN ($category_string) AND s.maSach != '$currentBookId'
                            GROUP BY s.maSach
                            LIMIT 4";

        $result = $conn->query($sql_get_related);
        $relatedBooks = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $relatedBooks[] = $row;
            }
            echo json_encode($relatedBooks);
        } else {
            echo json_encode(array("message" => "No related books found!"));
        }
    } else {
        echo json_encode(array("message" => "No categories found for the current book!"));
    }
} else {
    echo json_encode(array("message" => "Category or book ID not specified!"));
}

$conn->close();
?>