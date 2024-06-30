<?php

include "config.php";

if (isset($_GET['publisherId'])) {
    $publisherId = $_GET['publisherId'];

    // SQL query to get books by the publisher, avoiding duplicates
    $sql_get_publisher = "
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
            GROUP_CONCAT(DISTINCT dm.tenDM) AS DanhMuc,
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
            nxbs.maNXB = ?
        GROUP BY 
            s.maSach, 
            s.chiTiet, 
            nxbs.tenNXB, 
            s.tenSach, 
            s.hinhAnh, 
            s.donGia, 
            km.luongKM
    ";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql_get_publisher);
    $stmt->bind_param("i", $publisherId);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        echo json_encode($books);
    } else {
        echo json_encode(['Error' => "No books found for this publisher."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['Error' => "Publisher ID not provided."]);
}
?>