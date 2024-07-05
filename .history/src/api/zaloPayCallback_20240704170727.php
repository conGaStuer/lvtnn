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