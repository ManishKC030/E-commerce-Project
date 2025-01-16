<?php
require 'ad_nav.php';
include '../connection.php';
include 'auth.php';

// Get the current admin ID from session or authentication
$admin_id = $_SESSION['admin_id'] ?? 0; // Replace with your actual session variable

// Fetch data specific to the logged-in admin
$productCount = $conn->query("SELECT COUNT(*) AS count FROM products WHERE admin_id = $admin_id")->fetch_assoc()['count'];
$customerCount = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$categoryCount = $conn->query("SELECT COUNT(*) AS count FROM categories WHERE admin_id = $admin_id")->fetch_assoc()['count'];
$orderCount = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE admin_id = $admin_id")->fetch_assoc()['count'];
$newOrders = $conn->query("SELECT * FROM orders WHERE admin_id = $admin_id ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }

    .dashboard {
      max-width: 1200px;
      margin: auto;
      padding: 20px;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
    }

    .cards {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .card {
      flex: 1;
      padding: 20px;
      margin: 0 10px;
      color: white;
      text-align: center;
      border-radius: 8px;
    }

    .card.blue {
      background-color: #007bff;
    }

    .card.green {
      background-color: #28a745;
    }

    .card.orange {
      background-color: #fd7e14;
    }

    .card.red {
      background-color: #dc3545;
    }

    .card h2 {
      margin: 0;
      font-size: 24px;
    }

    .card p {
      margin: 10px 0;
      font-size: 18px;
    }

    .card a {
      color: white;
      text-decoration: none;
      font-size: 14px;
    }

    .orders {
      margin-bottom: 20px;
    }

    .orders h2 {
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th,
    table td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }
  </style>

</head>

<body>
  <div class="dashboard">
    <h1>Dashboard</h1>
    <div class="cards">
      <div class="card blue">
        <h2><?php echo $productCount; ?></h2>
        <p>Products</p>
        <a href="ad_proview.php">View Details</a>
      </div>
      <div class="card green">
        <h2><?php echo $customerCount; ?></h2>
        <p>Customers</p>
        <a href="customers.php">View Details</a>
      </div>
      <div class="card orange">
        <h2><?php echo $categoryCount; ?></h2>
        <p>Product Categories</p>
        <a href="view_categories.php">View Details</a>
      </div>
      <div class="card red">
        <h2><?php echo $orderCount; ?></h2>
        <p>Orders</p>
        <a href="#">View Details</a>
      </div>
    </div>
    <div class="orders">
      <h2>New Orders</h2>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($newOrders->num_rows > 0): ?>
            <?php while ($row = $newOrders->fetch_assoc()) : ?>
              <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['total_price']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="text-align: center;">No orders yet</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>

<?php
// Close connection
$conn->close();
?>