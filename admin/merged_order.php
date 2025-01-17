<?php
include("../connection.php");
include("ad_nav.php");
require("auth.php");

// Fetch all orders along with user and their order items
$sql = "SELECT 
            o.order_id, 
            o.total_price, 
            o.status, 
            o.created_at, 
            u.username AS username, 
            u.email, 
            oi.product_id, 
            oi.quantity, 
            oi.price, 
            p.name AS product_name, 
            p.image1 
        FROM orders o
        INNER JOIN users u ON o.user_id = u.user_id
        INNER JOIN order_items oi ON o.order_id = oi.order_id
        INNER JOIN products p ON oi.product_id = p.product_id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
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
            margin: 20px 0;
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

        img {
            width: 250px;
            height: 130px;
            border-radius: 8px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        select,
        .btn {
            padding: 5px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .btn:hover {
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
    <h1>Manage Orders</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $current_order_id = null;
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <!-- Display order details only once per order -->
                        <?php if ($current_order_id !== $row['order_id']): ?>
                            <td rowspan="<?php echo $row['order_id'] === $current_order_id ? 0 : ''; ?>">
                                <?php echo $row['order_id']; ?>
                            </td>
                            <td rowspan="<?php echo $row['order_id'] === $current_order_id ? 0 : ''; ?>">
                                <?php echo htmlspecialchars($row['username']); ?>
                            </td>
                            <td rowspan="<?php echo $row['order_id'] === $current_order_id ? 0 : ''; ?>">
                                <?php echo htmlspecialchars($row['email']); ?>
                            </td>
                        <?php endif; ?>

                        <!-- Display product-specific details -->
                        <td><a href="order_productDetail.php?product_id=<?php echo $row['product_id']; ?>"><img src="../uploads/<?php echo htmlspecialchars($row['image1']); ?>" alt="Product"></a></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>

                        <?php if ($current_order_id !== $row['order_id']): ?>
                            <td rowspan="<?php echo $row['order_id'] === $current_order_id ? 0 : ''; ?>">
                                $<?php echo number_format($row['total_price'], 2); ?>
                            </td>
                            <td rowspan="<?php echo $row['order_id'] === $current_order_id ? 0 : ''; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </td>
                            <td rowspan="<?php echo $row['order_id'] === $current_order_id ? 0 : ''; ?>">
                                <?php echo $row['created_at']; ?>
                            </td>
                            <td rowspan="<?php echo $row['order_id'] === $current_order_id ? 0 : ''; ?>">
                                <form action="update_order_status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <select name="status" required>
                                        <option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $row['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="shipped" <?php echo $row['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $row['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $row['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            </td>
                        <?php endif; ?>

                        <!-- Update current_order_id to handle rowspan -->
                        <?php $current_order_id = $row['order_id']; ?>
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