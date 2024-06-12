<?php
include "../config.php";

$sql_get_categories = "SELECT maDM, tenDM FROM danh_muc";
$result_categories = $conn->query($sql_get_categories);

$categories = array();

if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = array(
            "maDM" => $row["maDM"],
            "tenDM" => $row["tenDM"]
        );
    }
    echo json_encode($categories);
} else {
    echo json_encode(array());
}

$conn->close();
?>