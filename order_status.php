<?php
// Include the database connection file
include 'connection.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


$user_id = $_SESSION['user_id'];

$sql = "SELECT 
            o.order_id,
            p.name AS product_name,
            p.brand,
            p.image1,
            o.quantity,
            o.total_price,
            o.status AS order_status,
            o.created_at AS order_date,
            pay.payment_method,
            pay.status AS payment_status
        FROM 
            orders o
        JOIN 
            products p ON o.product_id = p.product_id
        LEFT JOIN 
            payment pay ON o.order_id = pay.order_id
        WHERE 
            o.user_id = ?
        ORDER BY 
            o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the admin_id to the query
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        .status.pending {
            background-color: #f39c12;
        }

        .status.confirmed {
            background-color: #3498db;
        }

        .status.shipped {
            background-color: #9b59b6;
        }

        .status.delivered {
            background-color: #2ecc71;
        }

        .status.cancelled {
            background-color: #e74c3c;
        }

        .payment-status.cod {
            background-color: #3498db;
        }

        .payment-status.completed {
            background-color: #2ecc71;
        }

        .payment-status.failed {
            background-color: #e74c3c;
        }

        .payment-status.pending {
            background-color: #f39c12;
        }

        .payment-status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }
    </style>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <h1>Your Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Status</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['order_id']) ?></td>
                            <td>
                                <img src="uploads/<?= htmlspecialchars($row['image1']) ?>" alt="Product Image" style="width: 80px; height: auto; border-radius: 5px;">
                            </td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['brand']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td>$<?= htmlspecialchars($row['total_price']) ?></td>
                            <td>
                                <span class="status <?= htmlspecialchars($row['order_status']) ?>">
                                    <?= htmlspecialchars(ucwords($row['order_status'])) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $row['payment_method']))) ?></td>
                            <td>
                                <span class="payment-status <?= htmlspecialchars($row['payment_status']) ?>">
                                    <?= htmlspecialchars(ucwords($row['payment_status'])) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['order_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>