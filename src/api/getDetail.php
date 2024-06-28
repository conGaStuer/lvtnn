<?php
include "config.php";

// Lấy dữ liệu từ request GET
$orderId = $_GET['orderId'];

// Truy vấn chi tiết tiến trình đơn hàng
$sql_get_order_events = "
SELECT event, timestamp
FROM order_events
WHERE order_id = ?
ORDER BY timestamp ASC
";

$stmt = $conn->prepare($sql_get_order_events);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$result = $stmt->get_result();

$orderEvents = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderEvents[] = $row;
    }
}

echo json_encode($orderEvents);

$stmt->close();
$conn->close();
?>