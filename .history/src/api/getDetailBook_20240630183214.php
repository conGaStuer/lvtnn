<?php
include "config.php";

if (isset($_GET['id'])) {
    $maSach = $_GET['id']; // Assuming ID is an integer

    $sql_get = "SELECT s.maSach AS MaSach, s.chiTiet AS ChiTiet, nxbs.tenNXB AS NhaXuatBan, nxbs.maNXB AS MaNhaXuatBan,
    s.tenSach AS TenSach, s.hinhAnh AS HinhAnh, s.donGia AS DonGia, tg.tenTG AS TacGia, tg.maTG AS MaTacGia, nn.tenNN AS NgonNgu, 
    nn.maNN AS MaNgonNgu,  km.luongKM AS KhuyenMai,
    s.soLuong AS SoLuong,
    GROUP_CONCAT(DISTINCT dm.tenDM ORDER BY dm.tenDM SEPARATOR ', ') AS DanhMuc,
    GROUP_CONCAT(DISTINCT dm.maDM ORDER BY dm.maDM SEPARATOR ', ') AS MaDanhMuc
    FROM sach AS s
    INNER JOIN tg_sach AS ts ON s.maSach = ts.maSach
    INNER JOIN tac_gia AS tg ON ts.maTG = tg.maTG
    INNER JOIN nn_sach AS nns ON s.maSach = nns.maSach
    INNER JOIN ngon_ngu AS nn ON nns.maNN = nn.maNN
    INNER JOIN dm_sach AS dms ON s.maSach = dms.maSach
    INNER JOIN danh_muc AS dm ON dms.maDM = dm.maDM 
    INNER JOIN nha_xuat_ban AS nxbs ON s.maNXB = nxbs.maNXB
    INNER JOIN khuyen_mai AS km ON km.maKM = s.maKM
    WHERE s.maSach = '$maSach'";

    $result = $conn->query($sql_get);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Process DanhMuc to split into an array
        $categories = explode(', ', $row['DanhMuc']);
        $categoryIds = explode(', ', $row['MaDanhMuc']);

        // Add processed categories to the row
        $row['DanhMuc'] = $categories;
        $row['MaDanhMuc'] = $categoryIds;

        echo json_encode($row);
    } else {
        echo json_encode(array("mangaID" => "Can't find book!!"));
    }
} else {
    echo json_encode(array("message" => "Not found!!"));
}

$conn->close();
?>