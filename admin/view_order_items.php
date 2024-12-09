<?php
include("../connection.php");
include("ad_nav.php");
require("auth.php");


// Check if order_id is provided
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Fetch the order items
    $sql = "SELECT oi.*, p.name AS product_name 
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
    <link rel="stylesheet" href="stylesheet/admin.css">
</head>

<body>
    <h1>Order Items</h1>
    <a href="view_orders.php">Back to Orders</a>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
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
