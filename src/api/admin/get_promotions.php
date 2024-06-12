<?php
include "../config.php";

$sql_get_promotions = "SELECT maKM, luongKM FROM khuyen_mai";
$result_promotions = $conn->query($sql_get_promotions);

$promotions = array();

if ($result_promotions->num_rows > 0) {
    while ($row = $result_promotions->fetch_assoc()) {
        $promotions[] = array(
            "maKM" => $row["maKM"],
            "luongKM" => $row["luongKM"]
        );
    }
    echo json_encode($promotions);
} else {
    echo json_encode(array());
}

$conn->close();
?>