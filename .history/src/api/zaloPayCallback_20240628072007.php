<?php
header('Content-Type: application/json');
include "config.php";

$result = [];

try {
    $key2 = "eG4r0GcoNtRGbO8";
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

    $requestmac = $postdatajson["mac"];
    if (strcmp($mac, $requestmac) !== 0) {
        $result["return_code"] = -1;
        $result["return_message"] = "Invalid MAC";
    } else {
        $datajson = json_decode($postdatajson["data"], true);
        $app_trans_id = $datajson["app_trans_id"];

        $userId = $datajson["app_user"];
        $amount = $datajson["amount"];
        $items = $datajson["items"];


        try {
            $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
            if ($conn->query($sql_create_order) !== TRUE) {
                throw new Exception("Failed to create order");
            }

            $orderId = $conn->insert_id;

            $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
            if ($conn->query($sql_add_event) !== TRUE) {
                throw new Exception("Failed to add event");
            }

            foreach ($items as $item) {
                $maSach = $item['MaSach'];
                $soLuong = $item['SoLuong'];
                $donGia = $item['DonGia'];

                $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) 
                VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
                if ($conn->query($sql_insert_item) !== TRUE) {
                    throw new Exception("Failed to add item to order");
                }
            }



            $result["return_code"] = 1;
            $result["return_message"] = "Success";
        } catch (Exception $e) {
            $result["return_code"] = 0;
            $result["return_message"] = $e->getMessage();
        }

        $conn->close();
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

echo json_encode($result);
?>