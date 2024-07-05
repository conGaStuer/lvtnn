<?php
header('Content-Type: application/json');
include "config.php";

$log_file = 'debug_log.txt';
$log_data = '';

// Ensure the log file is writable
if (!is_writable($log_file)) {
    error_log('Log file is not writable: ' . $log_file);
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error'
    ]);
    exit;
}

// Log function for better readability
function log_message($message)
{
    global $log_file, $log_data;
    $log_data .= $message . PHP_EOL;
    file_put_contents($log_file, $log_data, FILE_APPEND);
}

$raw_post_data = file_get_contents("php://input");

// Log raw POST data
log_message("Raw POST data: " . $raw_post_data);

$data = json_decode($raw_post_data, true);

// Log decoded data
log_message("Decoded data: " . print_r($data, true));

if (!isset($data['data']) || !isset($data['mac'])) {
    log_message("Invalid request data");
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

// Log MAC comparison
log_message("Received MAC: $received_mac");
log_message("Calculated MAC: $calculated_mac");

if ($received_mac !== $calculated_mac) {
    log_message("Invalid MAC");
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid MAC'
    ]);
    exit;
}

$payload = json_decode($data['data'], true);

// Log payload
log_message("Payload: " . print_r($payload, true));

if ($payload['return_code'] != 1) {
    log_message("Payment failed");
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
        log_message("Order created successfully");
        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception("Failed to create order: " . $conn->error);
    }
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    log_message("Error: " . $e->getMessage());
    echo json_encode(['status' => 'fail', 'message' => $e->getMessage()]);
}

$conn->close();
?>