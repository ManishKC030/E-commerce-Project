<?php
include("../connection.php");
include("ad_nav.php");
require("auth.php");


// Check if order_id is provided
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Fetch the order items
    $sql = "SELECT oi.*, p.name AS product_name, p.image1
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "<script>alert('No order selected!'); window.location.href = 'view_orders.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }

        img {
            width: 250px;
            height: 130px;
            overflow: hidden;
            display: inline-block;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            object-fit: cover;
        }


        h1 {
            text-align: center;
            margin-top: 20px;
            color: rgb(0, 0, 0);
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: rgb(197, 141, 10);
            color: #fff;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e9ecef;
        }

        a {

            text-decoration: none;
            color: rgb(0, 0, 0);
        }

        a:hover {
            text-decoration: underline;
        }

        form {
            display: inline-block;
        }

        select {
            padding: 5px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin: 20px;
            color: #6c757d;
        }
    </style>
    </style>
</head>

<body>
    <h1>Order Items</h1>
    <a href="view_orders.php">Back to Orders</a>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../uploads/<?php echo htmlspecialchars($row['image1']); ?>" alt="product image"></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td>$<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No items found for this order.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>

</html>