<?php
// PHP code for /payment endpoint
include "config.php";
header('Content-Type: application/json');
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Configuration
$config = [
    'app_id' => '2553',
    'key1' => 'PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL',
    'key2' => 'kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz',
    'endpoint' => 'https://sb-openapi.zalopay.vn/v2/create',
];

// Create a Guzzle client instance
$client = new Client();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate app_trans_id
    $transID = mt_rand(100000, 999999);
    $app_trans_id = date('ymd') . '_' . $transID;

    // Prepare data array
    $data = [
        'app_id' => $config['app_id'],
        'app_trans_id' => $app_trans_id,
        'app_user' => 'user123',
        'app_time' => round(microtime(true) * 1000), // milliseconds
        'item' => json_encode([]),
        'amount' => 50000,
        'embed_data' => json_encode(['redirecturl' => 'https://phongthuytaman.com']),
        'callback_url' => 'https://b074-1-53-37-194.ngrok-free.app/callback', // Your ngrok callback URL
        'description' => 'Lazada - Payment for the order #' . $transID,
    ];

    // Generate MAC
    $dataStr = $data['app_id'] . '|' . $data['app_trans_id'] . '|' . $data['app_user'] . '|' . $data['amount'] . '|' . $data['app_time'] . '|' . $data['embed_data'] . '|' . $data['item'];
    $mac = hash_hmac('sha256', $dataStr, $config['key1']);
    $data['mac'] = $mac;

    try {
        // Send POST request
        $response = $client->post($config['endpoint'], [
            'form_params' => $data,
        ]);

        // Return ZaloPay response
        echo $response->getBody();
    } catch (RequestException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>