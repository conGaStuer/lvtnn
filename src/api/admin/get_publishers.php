<?php
include "../config.php";

$sql_get_publishers = "SELECT maNXB, tenNXB FROM nha_xuat_ban";
$result_publishers = $conn->query($sql_get_publishers);

$publishers = array();

if ($result_publishers->num_rows > 0) {
    while ($row = $result_publishers->fetch_assoc()) {
        $publishers[] = array(
            "maNXB" => $row["maNXB"],
            "tenNXB" => $row["tenNXB"]
        );
    }
    echo json_encode($publishers);
} else {
    echo json_encode(array());
}

$conn->close();
?>