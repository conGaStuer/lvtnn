<?php
session_start();
include (__DIR__ . "/config.php");

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $maSach = $data->maSach;
    $maND = $data->maND;
    if (isset($data->maSach)) {
        $stmt = "SELECT ctdh.madon FROM chi_tiet_don_hang ctdh
                                JOIN don_dat_hang ddh ON ddh.madon = ctdh.madon
                                WHERE ctdh.masach = '$maSach' AND ddh.maND = '$maND' AND ddh.trangthai = 'giohang'";
        $result = $conn->query($stmt);
        $orderIds = [];
        while ($row = $result->fetch_assoc()) {
            $orderIds[] = $row['madon'];
        }

        // If there are any matching orders with 'giohang' status, delete the items
        if (count($orderIds) > 0) {
            // Delete from `chi_tiet_don_hang`
            $orderIdsStr = implode(",", $orderIds);
            $stmt = $conn->prepare("DELETE FROM chi_tiet_don_hang WHERE masach = ? AND madon IN ($orderIdsStr)");
            $stmt->bind_param("s", $maSach);
            $stmt->execute();
            $stmt->close();

            $orderIdsStr1 = implode(",", $orderIds);
            $stmt1 = $conn->prepare("DELETE FROM don_dat_hang WHERE  madon IN ($orderIdsStr)");
            $stmt1->bind_param("s", $data->maSach);
            $stmt1->execute();
            $stmt1->close();

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
            echo json_encode(array("message" => "No matching items found in 'giohang' status."));
        }
    } else {
        echo json_encode(array("message" => "Invalid data provided."));
    }
}

$conn->close();
?>