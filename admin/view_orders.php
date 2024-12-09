<?php
include("../connection.php");
include("ad_nav.php");
require("auth.php");


// Fetch all orders along with user and status information
$sql = "SELECT o.*, username AS username, u.email 
        FROM orders o 
        INNER JOIN users u ON o.user_id = u.user_id 
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>

</head>

<body>
    <h1>Orders Management</h1>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
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
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="view_order_items.php?order_id=<?php echo $row['order_id']; ?>">View Details</a>
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

    <?php $conn->close(); ?>
</body>

</html>