<?php
include "../config.php";

$sql_get_author = "SELECT maTG, tenTG FROM tac_gia";
$result_author = $conn->query($sql_get_author);

$author = array();

if ($result_author->num_rows > 0) {
    while ($row = $result_author->fetch_assoc()) {
        $author[] = array(
            "maTG" => $row["maTG"],
            "tenTG" => $row["tenTG"]
        );
    }
    echo json_encode($author);
} else {
    echo json_encode(array());
}

$conn->close();
?>