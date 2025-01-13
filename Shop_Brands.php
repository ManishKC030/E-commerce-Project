<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop By Brand</title>
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

    <?php

    require 'connection.php'; // Ensure this file contains your database connection setup
    include "navbar.php";

    // Get the selected brand from the URL
    $brand = isset($_GET['brand']) ? $_GET['brand'] : '';

    // Fetch prodcts for the selected brand
    if ($brand) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE brand = ?");
        $stmt->bind_param("s", $brand);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h1>Products for Brand: " . htmlspecialchars($brand) . "</h1>";

        if ($result->num_rows > 0) {
            echo "<div class='product-container'>";
            while ($row = $result->fetch_assoc()) {
    ?>

                <div class="product">
                    <img src="uploads/<?php echo htmlspecialchars($row['image1']); ?>" alt="Product Image">
                    <div class="product-info">
                        <p class="product-brand"><?php echo htmlspecialchars($row['brand']); ?></p>
                        <p class="product-name"><?php echo htmlspecialchars($row['name']); ?></p>

                        <p class="product-price">Price: $<?php echo number_format($row['price'], 2); ?></p>
                        <p class="product-stock">Stock: <?php echo $row['stock'] > 0 ? $row['stock'] : 'Out of Stock'; ?></p>
                        <a href="product_detail.php?product_id=<?php echo $row['product_id']; ?>" class="view-btn">View Details</a>
                    </div>
                </div>
    <?php
            }
        } else {
            echo "<p class='no-products'>No products found.</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Please select a brand to view products.</p>";
    }

    $conn->close();
    ?>

</body>

</html>