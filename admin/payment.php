<?php
// Include the database connection file
include '../connection.php';
require 'ad_nav.php';
include 'auth.php';

$admin_id = $_SESSION['admin_id'];
// Fetch payment data
try {

    $query = "
        SELECT 
            p.id AS payment_id,
            o.order_id AS order_id, 
            p.payment_method,
            p.status,
       
            p.created_at
        FROM 
            payment p
        JOIN 
            orders o ON p.order_id = o.order_id
        JOIN 
            products prod ON o.product_id = prod.product_id
        WHERE 
            prod.admin_id = ? 
        ORDER BY 
            p.created_at DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id); // Bind the admin_id parameter
    $stmt->execute();
    $result = $stmt->get_result();


    // Check if there are results
    $payments = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
    }
} catch (Exception $e) {
    die("Error fetching payment data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Payment Status</title>
    <style>
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
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
        }

        .cod {
            background-color: #007bff;
        }

        .pending {
            background-color: #ffc107;
        }

        .completed {
            background-color: #28a745;
        }

        .failed {
            background-color: #dc3545;
        }
    </style>
</head>

<body>
    <h1>Payment Status</h1>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>Payment Method</th>
                <th>Status</th>
             
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($payments)): ?>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                        <td>
                            <span class="status <?php echo htmlspecialchars($payment['status']); ?>">
                                <?php echo htmlspecialchars($payment['status']); ?>
                            </span>
                        </td>

                        <td><?php echo htmlspecialchars($payment['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No payments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>