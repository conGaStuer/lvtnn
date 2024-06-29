<?php

include "../config.php";

$sql_get = "SELECT * FROM nguoi_dung";

$result = $conn->query($sql_get);
$users = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode(array("error" => "Khong get duoc sach"));
}
