<?php
include("../connection.php");
include("ad_nav.php");
require("auth.php");

// Fetch all orders along with user and status information
$sql_orders = "SELECT o.*, u.username AS username, u.email 
               FROM orders o 
               INNER JOIN users u ON o.user_id = u.user_id 
               ORDER BY o.created_at DESC";
$result_orders = $conn->query($sql_orders);

// Handle order items display
$order_items = [];
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Fetch the order items
    $sql_items = "SELECT oi.*, p.name AS product_name, p.image1
                  FROM order_items oi
                  INNER JOIN products p ON oi.product_id = p.product_id
                  WHERE oi.order_id = ?";
    $stmt = $conn->prepare($sql_items);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_items = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007bff;
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
            color: rgb(0, 0, 255);
        }

        a:hover {
            text-decoration: underline;
        }

        img {
            width: 250px;
            height: 130px;
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        form {
            display: inline-block;
        }

        select, button {
            padding: 5px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: #fff;
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
</head>

<body>
    <h1>Orders Management</h1>

    <?php if ($result_orders->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="manage_orders.php?order_id=<?php echo $row['order_id']; ?>">View Items</a>
                            <form action="update_order_status.php" method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <select name="status" required>
                                    <option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $row['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="shipped" <?php echo $row['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $row['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $row['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <?php if (!empty($order_items) && $order_items->num_rows > 0): ?>
        <h2 style="text-align: center;">Order Items</h2>
        <table>
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
                <?php while ($item = $order_items->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../uploads/<?php echo htmlspecialchars($item['image1']); ?>" alt="Product Image"></td>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif (isset($_GET['order_id'])): ?>
        <p>No items found for this order.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>

</html>
