<?php
header('Content-Type: application/json');
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['data']) || !isset($data['mac'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request data'
    ]);
    exit;
}

$config = [
    "app_id" => 2553,
    "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
];

$received_mac = $data['mac'];
$calculated_mac = hash_hmac("sha256", $data['data'], $config['key2']);

if ($received_mac !== $calculated_mac) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid MAC'
    ]);
    exit;
}

$payload = json_decode($data['data'], true);

if ($payload['return_code'] != 1) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Payment failed'
    ]);
    exit;
}

$userId = $payload['app_user'];
$items = json_decode($payload['item'], true);

try {
    // Start transaction
    $conn->begin_transaction();

    // Create a new order
    $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) VALUES ('$userId', 'choduyet', CURDATE())";
    if ($conn->query($sql_create_order) === TRUE) {
        $orderId = $conn->insert_id;

        // Add event
        $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$orderId', 'choduyet', NOW())";
        if ($conn->query($sql_add_event) !== TRUE) {
            throw new Exception("Failed to add event: " . $conn->error);
        }

        // Add items to the order
        foreach ($items as $item) {
            $maSach = $item['MaSach'];
            $soLuong = $item['SoLuong'];
            $donGia = $item['DonGia'];

            $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
            if ($conn->query($sql_insert_item) !== TRUE) {
                throw new Exception("Failed to add item to order: " . $conn->error);
            }
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception("Failed to create order: " . $conn->error);
    }
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['status' => 'fail', 'message' => $e->getMessage()]);
}

$conn->close();
?>