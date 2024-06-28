<?php
include "config.php";

if (!isset($_GET['maDon'])) {
    die("Mã đơn hàng không hợp lệ");
}

$maDon = $_GET['maDon'];

// Fetch order details
$orderQuery = $conn->prepare("
    SELECT d.MaDon, d.NgayDat, n.tenKH, n.DiaChi, n.SDT, s.TenSach, c.SoLuong, c.DonGia
    FROM don_dat_hang d
    JOIN chi_tiet_don_hang c ON d.MaDon = c.MaDon
    JOIN sach s ON c.MaSach = s.MaSach
    JOIN nguoi_dung n ON d.MaND = n.MaND
    WHERE d.MaDon = ?
");
$orderQuery->bind_param("i", $maDon);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();

if ($orderResult->num_rows == 0) {
    die("Đơn hàng không tồn tại");
}

$order = $orderResult->fetch_all(MYSQLI_ASSOC);

// Generate invoice
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .invoice {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details table th,
        .invoice-details table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .invoice-details table th {
            background-color: #f2f2f2;
        }

        .invoice-footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice">
        <div class="invoice-header">
            <h1>HÓA ĐƠN</h1>
        </div>
        <div class="invoice-details">
            <p><strong>Mã đơn hàng:</strong> <?php echo $order[0]['MaDon']; ?></p>
            <p><strong>Ngày đặt:</strong> <?php echo $order[0]['NgayDat']; ?></p>
            <p><strong>Khách hàng:</strong> <?php echo $order[0]['TenNguoiDung']; ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo $order[0]['DiaChi']; ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo $order[0]['SDT']; ?></p>
        </div>
        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>Tên sách</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($order as $item) {
                        $subtotal = $item['SoLuong'] * $item['DonGia'];
                        $total += $subtotal;
                        echo "
                        <tr>
                            <td>{$item['TenSach']}</td>
                            <td>{$item['SoLuong']}</td>
                            <td>{$item['DonGia']}</td>
                            <td>$subtotal</td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="invoice-footer">
            <h3>Tổng tiền: <?php echo $total; ?> VND</h3>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>