<?php
include "config.php";
header('Content-Type: application/json');

// Ensure the necessary parameters are present in the callback URL
if (!isset($_GET['zptranstoken']) || !isset($_GET['returncode']) || !isset($_GET['returnmessage'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid callback data'
    ]);
    exit;
}

$zptranstoken = $_GET['zptranstoken'];
$returncode = $_GET['returncode'];
$returnmessage = $_GET['returnmessage'];

if ($returncode != 1 || $returnmessage !== "Giao dịch thành công") {
    echo json_encode([
        'status' => 'error',
        'message' => 'Transaction was not successful'
    ]);
    exit;
}

try {
    // Extract transaction ID from zptranstoken or perform any other necessary validation
    // Example: $transactionId = extractTransactionId($zptranstoken);

    // Start transaction
    $conn->begin_transaction();

    // Insert order into database
    $sql_create_order = "INSERT INTO don_dat_hang (maND, trangthai, ngaydat) 
                         VALUES ('$userId', 'choduyet', CURDATE())";
    if ($conn->query($sql_create_order) !== TRUE) {
        throw new Exception("Failed to create order");
    }

    // Get the ID of the newly created order
    $orderId = $conn->insert_id;

    // Insert items into order_details table
    foreach ($data['items'] as $item) {
        $maSach = $item['MaSach'];
        $soLuong = $item['SoLuong'];
        $donGia = $item['DonGia'];

        $sql_insert_item = "INSERT INTO chi_tiet_don_hang (madon, masach, soluong, dongia) 
                            VALUES ('$orderId', '$maSach', '$soLuong', '$donGia')";
        if ($conn->query($sql_insert_item) !== TRUE) {
            throw new Exception("Failed to add item to order");
        }
    }

    // Commit transaction if all queries succeed
    $conn->commit();

    // Respond with success
    echo json_encode([
        'status' => 'success',
        'message' => 'Transaction successful'
    ]);

} catch (Exception $e) {
    // Rollback transaction on failure
    $conn->rollback();

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>