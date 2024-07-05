<!-- <?php
header('Content-Type: application/json');
include "config.php";

$result = [];

try {
    $key2 = "eG4r0GcoNtRGbO8";
    $postdata = file_get_contents('php://input');
    $postdatajson = json_decode($postdata, true);
    $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

    $requestmac = $postdatajson["mac"];
    if (strcmp($mac, $requestmac) != 0) {
        // callback không hợp lệ
        $result["return_code"] = -1;
        $result["return_message"] = "mac not equal";
    } else {
        // Process callback data
        $datajson = json_decode($postdatajson["data"], true);
        $app_trans_id = $datajson["app_trans_id"];
        $userId = $datajson["app_user"];
        $amount = $datajson["amount"];
        $items = $datajson["items"];

        $conn->begin_transaction();
        try {
            // Create new order
            $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
            if ($conn->query($sql_create_order) !== TRUE) {
                throw new Exception("Failed to create order");
            }

            // Get the ID of the newly created order
            $orderId = $conn->insert_id;

            // Add event to order_events table
            $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
            if ($conn->query($sql_add_event) !== TRUE) {
                throw new Exception("Failed to add event");
            }

            // Add each item to order_details table
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

            $conn->commit();
            $result["return_code"] = 1;
            $result["return_message"] = "Success";

        } catch (Exception $e) {
            $conn->rollback();
            $result["return_code"] = 0;
            $result["return_message"] = $e->getMessage();
        }
    }
} catch (Exception $e) {
    $result["return_code"] = 0;
    $result["return_message"] = $e->getMessage();
}

echo json_encode($result);
?> 
<?php
$config = [
    "key1" => "9phuAOYhan4urywHTh0ndEXiV3pKHr5Q",
];

function verifyCallback($data, $mac)
{
    global $config;

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    $signature = hash_hmac("sha256", $data, $config["key1"]);

    return $signature === $mac;
}

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input["data"]) && isset($input["mac"])) {
    $isValid = verifyCallback($input["data"], $input["mac"]);
    if ($isValid) {
        // Handle successful payment
        // Update order status, send email, etc.
        // Example: Update order status in database
        // $orderId = $input["data"]["orderId"];
        // $status = $input["data"]["status"];
        // UpdateOrderStatus($orderId, $status);
        echo json_encode([
            "return_code" => 1,
            "return_message" => "Success"
        ]);
    } else {
        echo json_encode([
            "return_code" => -1,
            "return_message" => "Invalid MAC"
        ]);
    }
} else {
    echo json_encode([
        "return_code" => -1,
        "return_message" => "Invalid data"
    ]);
}
?>