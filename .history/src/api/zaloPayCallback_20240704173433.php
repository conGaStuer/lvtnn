<?php
// PHP code for /callback endpoint
include "config.php";
header('Content-Type: application/json');
// Configuration (same as above)
$config = [
    'app_id' => '2553',
    'key1' => 'PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL',
    'key2' => 'kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz',
];

// Function to verify MAC
function verifyMAC($dataStr, $reqMac, $key)
{
    $mac = hash_hmac('sha256', $dataStr, $key);
    return $mac === $reqMac;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST data
    $postData = file_get_contents('php://input');

    // Decode JSON
    $data = json_decode($postData, true);

    // Extract data fields
    $dataStr = $data['data'];
    $reqMac = $data['mac'];

    // Verify MAC
    if (verifyMAC($dataStr, $reqMac, $config['key2'])) {
        // MAC verified, process payment success
        $dataJson = json_decode($dataStr, true);
        // Update order status or perform other actions
        echo json_encode(['return_code' => 1, 'return_message' => 'success']);
    } else {
        // MAC not verified, handle invalid callback
        echo json_encode(['return_code' => -1, 'return_message' => 'mac not equal']);
    }
}
?>