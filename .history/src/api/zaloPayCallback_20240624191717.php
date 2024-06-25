<?php
header('Content-Type: application/json');

include "config.php";

$result = [];

try {
    $key2 = "eG4r0GcoNtRGbO8"; // Key2 từ ZaloPay
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

    $requestmac = $postdatajson["mac"];

    if (strcmp($mac, $requestmac) != 0) {
        $result["return_code"] = -1;
        $result["return_message"] = "mac not equal";
    } else {
        $datajson = json_decode($postdatajson["data"], true);
        $app_trans_id = $datajson["app_trans_id"];
        $userId = $datajson["app_user"];
        $amount = $datajson["amount"];
        $items = json_decode($datajson["item"], true);

        // Create a new order
        $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
        if ($conn->query($sql_create_order) === TRUE) {
            $orderId = $conn->insert_id;

            // Add event to order_events table
            $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
            if ($conn->query($sql_add_event) !== TRUE) {
                $result["return_code"] = 0;
                $result["return_message"] = "Failed to add event";
                echo json_encode($result);
                $conn->close();
                exit();
            }

            foreach ($items as $item) {
                $maSach = $item['MaSach'];
                $soLuong = $item['SoLuong'];
                $donGia = $item['DonGia'];

                // Add items to the new order
                $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
                if ($conn->query($sql_insert_item) !== TRUE) {
                    $result["return_code"] = 0;
                    $result["return_message"] = "Failed to add item to order";
                    echo json_encode($result);
                    $conn->close();
                    exit();
                }
            }

            $result["return_code"] = 1;
            $result["return_message"] = "success";
        } else {
            $result["return_code"] = 0;
            $result["return_message"] = "Failed to create order";
        }
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

echo json_encode($result);
$conn->close();
?>