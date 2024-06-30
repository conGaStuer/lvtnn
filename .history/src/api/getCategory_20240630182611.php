<?php
include "config.php";

if (isset($_GET['categoryName'])) {
    $categoryName = $_GET['categoryName'];

    // SQL query to get books by category name, avoiding duplicates
    $sql_get_category = "
        SELECT 
            s.maSach AS MaSach, 
            s.chiTiet AS ChiTiet, 
            nxbs.tenNXB AS NhaXuatBan,
            s.tenSach AS TenSach,
            s.hinhAnh AS HinhAnh, 
            s.donGia AS DonGia, 
            GROUP_CONCAT(DISTINCT tg.tenTG) AS TacGia,
            GROUP_CONCAT(DISTINCT tg.maTG) AS MaTacGia,
            GROUP_CONCAT(DISTINCT nn.tenNN) AS NgonNgu, 
            dm.tenDM AS DanhMuc,
            dm.maDM AS MaDanhMuc,
            km.luongKM AS KhuyenMai
        FROM 
            sach AS s
        INNER JOIN 
            tg_sach AS ts ON s.maSach = ts.maSach
        INNER JOIN 
            tac_gia AS tg ON ts.maTG = tg.maTG
        INNER JOIN 
            nn_sach AS nns ON s.maSach = nns.maSach
        INNER JOIN 
            ngon_ngu AS nn ON nns.maNN = nn.maNN
        INNER JOIN 
            dm_sach AS dms ON s.maSach = dms.maSach
        INNER JOIN 
            danh_muc AS dm ON dms.maDM = dm.maDM 
        INNER JOIN 
            nha_xuat_ban AS nxbs ON s.maNXB = nxbs.maNXB
        INNER JOIN 
            khuyen_mai AS km ON s.maKM = km.maKM
        WHERE 
            dm.tenDM = ?
        GROUP BY 
            s.maSach, 
            s.chiTiet, 
            nxbs.tenNXB, 
            s.tenSach, 
            s.hinhAnh, 
            s.donGia, 
            dm.tenDM, 
            dm.maDM, 
            km.luongKM
    ";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql_get_category);
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        echo json_encode($books);
    } else {
        echo json_encode(array("error" => "Không có sách nào thuộc danh mục này"));
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array("error" => "Không có danh mục được chọn"));
}
?>