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
        <a href="ad_proview.php" class="back-btn">Back to Products</a>
    </div>
</body>

</html>