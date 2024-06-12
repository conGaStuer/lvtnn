<?php
include "../config.php";

$sql_get_languages = "SELECT maNN, tenNN FROM ngon_ngu";
$result_languages = $conn->query($sql_get_languages);

$languages = array();

if ($result_languages->num_rows > 0) {
    while ($row = $result_languages->fetch_assoc()) {
        $languages[] = array(
            "maNN" => $row["maNN"],
            "tenNN" => $row["tenNN"]
        );
    }
    echo json_encode($languages);
} else {
    echo json_encode(array());
}

$conn->close();
?>