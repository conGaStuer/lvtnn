<?php
session_start();
include (__DIR__ . "/config.php");

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->maSach)) {
        // First, get the `madon` for the given `maSach` and `trangthai`
        $stmt = $conn->prepare("SELECT ctdh.madon FROM chi_tiet_don_hang ctdh
                                JOIN don_dat_hang ddh ON ddh.madon = ctdh.madon
                                WHERE ctdh.masach = ? AND ddh.trangthai = 'giohang'");
        $stmt->bind_param("s", $data->maSach);
        $stmt->execute();
        $result = $stmt->get_result();
        $orderIds = [];
        while ($row = $result->fetch_assoc()) {
            $orderIds[] = $row['madon'];
        }
        $stmt->close();

        // If there are any matching orders, delete the items
        if (count($orderIds) > 0) {
            // Delete from `chi_tiet_don_hang`
            $orderIdsStr = implode(",", $orderIds);
            $stmt = $conn->prepare("DELETE FROM chi_tiet_don_hang WHERE masach = ? AND madon IN ($orderIdsStr)");
            $stmt->bind_param("s", $data->maSach);
            $stmt->execute();
            $stmt->close();

            // Update the stock in `sach`
            $soLuong = $data->soLuong;
            $maSach = $data->maSach;
            $sql_update_stock = "UPDATE sach SET soLuong = soLuong + ? WHERE masach = ?";
            $stmt = $conn->prepare($sql_update_stock);
            $stmt->bind_param("is", $soLuong, $maSach);
            $stmt->execute();
            $stmt->close();

            echo json_encode(array("message" => "Item deleted successfully."));
        } else {
            echo json_encode(array("message" => "No matching orders found."));
        }
    } else {
        echo json_encode(array("message" => "Invalid data provided."));
    }
}

$conn->close();
?>