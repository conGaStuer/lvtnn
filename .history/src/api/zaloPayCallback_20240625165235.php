<?php
include "config.php";
header('Content-Type: application/json');

// Read and validate callback data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['data']) || !isset($data['mac'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid callback data'
    ]);
    exit;
}

// Validate MAC
$expectedMac = hash_hmac("sha256", $data['data'], $config["key2"]);
if ($data['mac'] !== $expectedMac) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid MAC'
    ]);
    exit;
}

$callbackData = json_decode($data['data'], true);

// Process callback data
$appTransId = $callbackData['app_trans_id'];
$appUser = $callbackData['app_user'];
$amount = $callbackData['amount'];

// Update order status in database
$sql_update_order = "UPDATE don_dat_hang SET trangthai = 'dathanhtoan' WHERE maDH = '$appTransId'";
if ($conn->query($sql_update_order) === TRUE) {
    // Insert order event
    $sql_add_event = "INSERT INTO order_events (order_id, event, timestamp) VALUES ('$appTransId', 'dathanhtoan', NOW())";
    if ($conn->query($sql_add_event) !== TRUE) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add order event'
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Order updated successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update order status'
    ]);
}

$conn->close();
?>