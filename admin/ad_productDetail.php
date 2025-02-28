<?php
// Include the database connection
require '../connection.php';
include 'ad_nav.php';
require 'auth.php';

// Check if a product_id is provided
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Fetch the product details
    $sql = "SELECT p.*, c.name AS category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the product exists
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>alert('Product not found!'); window.location.href = 'view_products.php';</script>";
        exit;
    }

    // Check if the form is submitted to add stock
    if (isset($_POST['add_stock'])) {
        $additional_stock = intval($_POST['additional_stock']);

        if ($additional_stock > 0) {
            // Update stock in the database
            $update_sql = "UPDATE products SET stock = stock + ? WHERE product_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ii", $additional_stock, $product_id);
            if ($update_stmt->execute()) {
                echo "<script>alert('Stock updated successfully!'); window.location.href = 'ad_productDetail.php?product_id=$product_id';</script>";
                exit;
            } else {
                echo "<script>alert('Failed to update stock. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Please enter a valid stock quantity to add.');</script>";
        }
    }
} else {
    echo "<script>alert('No product selected!'); window.location.href = 'view_products.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .product-details {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .product-images {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .product-images img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .product-info {
            margin-top: 20px;
            text-align: left;
        }

        .product-info p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .add-stock-form {
            margin-top: 30px;
        }

        .add-stock-form input {
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .add-stock-form button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .add-stock-form button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="product-details">
        <h1><?php echo htmlspecialchars($product['brand']); ?></h1>
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <div class="product-images">
            <img src="../uploads/<?php echo htmlspecialchars($product['image1']); ?>" alt="Image 1">
            <img src="../uploads/<?php echo htmlspecialchars($product['image2']); ?>" alt="Image 2">
            <img src="../uploads/<?php echo htmlspecialchars($product['image3']); ?>" alt="Image 3">
            <img src="../uploads/<?php echo htmlspecialchars($product['image4']); ?>" alt="Image 4">
        </div>
        <div class="product-info">
            <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
            <p><strong>Stock:</strong> <?php echo $product['stock'] > 0 ? $product['stock'] : 'Out of Stock'; ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        </div>

        <!-- Add stock form -->
        <div class="add-stock-form">
            <h3>Add Stock</h3>
            <form method="POST" action="">
                <input type="number" name="additional_stock" min="1" placeholder="Enter quantity" required>
                <button type="submit" name="add_stock">Add Stock</button>
            </form>
        </div>

        <a href="ad_proview.php" class="back-btn">Back to Products</a>
    </div>
</body>

</html>