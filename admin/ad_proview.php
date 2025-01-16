<?php
// Include the database connection
require '../connection.php';
include 'ad_nav.php';
require 'auth.php';

// Ensure the admin is logged in and get the admin ID
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href = 'login.php';</script>";
    exit;
}

// Handle deletion if the delete request is sent
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Sanitize the input

    // Delete the product only if it belongs to the logged-in admin
    $delete_sql = "DELETE FROM products WHERE product_id = ? AND admin_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $delete_id, $admin_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully!'); window.location.href = 'ad_proview.php';</script>";
    } else {
        echo "<script>alert('Error deleting product!'); window.location.href = 'ad_proview.php';</script>";
    }
    $stmt->close();
}

// Fetch all products for the logged-in admin
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.admin_id = ?
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .product-container {
            margin-left: 29px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .product {
            width: 290px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
        }

        .product-Brand {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .product-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .product-category {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }

        .product-price {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin-top: 10px;
        }

        .product-stock {
            font-size: 14px;
            color: green;
            margin-top: 5px;
        }

        .delete-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #ff4d4d;
            color: white;
            width: 100px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #ff1a1a;
        }

        .view-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            width: 100px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .view-btn:hover {
            background-color: #0056b3;
        }

        .no-products {
            text-align: center;
            font-size: 18px;
            margin: 20px;
            color: #777;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">Products</h1>
    <div class="product-container">
        <?php
        if ($result->num_rows > 0) {
            // Output each product
            while ($row = $result->fetch_assoc()) {
        ?>
                <div class="product">
                    <img src="../uploads/<?php echo htmlspecialchars($row['image1']); ?>" alt="Product Image">
                    <div class="product-info">
                        <p class="product-brand"><?php echo htmlspecialchars($row['brand']); ?></p>
                        <p class="product-name"><?php echo htmlspecialchars($row['name']); ?></p>
                        <p class="product-category">Category: <?php echo htmlspecialchars($row['category_name']); ?></p>
                        <p class="product-price">Price: $<?php echo number_format($row['price'], 2); ?></p>
                        <p class="product-stock">Stock: <?php echo $row['stock'] > 0 ? $row['stock'] : 'Out of Stock'; ?></p>

                        <!-- Pass product_id as delete_id -->
                        <a class="delete-btn" href="ad_proview.php?delete_id=<?php echo $row['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        <a href="ad_productDetail.php?product_id=<?php echo $row['product_id']; ?>" class="view-btn">View Details</a>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='no-products'>No products found.</p>";
        }
        ?>
    </div>
</body>

</html>