<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop By Brand</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 200px;
            text-align: center;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-card h2 {
            font-size: 18px;
            margin: 10px 0;
        }

        .product-card p {
            color: #666;
        }

        .product-card a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 8px 15px;
            border-radius: 4px;
            display: inline-block;
        }

        .product-card a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>



    <?php
    require 'connection.php'; // Ensure this file contains your database connection setup

    // Get the selected brand from the URL
    $brand = isset($_GET['brand']) ? $_GET['brand'] : '';

    // Fetch products for the selected brand
    if ($brand) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE brand = ?");
        $stmt->bind_param("s", $brand);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h1>Products for Brand: " . htmlspecialchars($brand) . "</h1>";

        if ($result->num_rows > 0) {
            echo "<div class='products-grid'>";
            while ($row = $result->fetch_assoc()) {
                echo "
            <div class='product-card'>
            <img src='uploads/" . htmlspecialchars($row['image1']) . "' alt='" . htmlspecialchars($row['name']) . "'>
            <h2>" . htmlspecialchars($row['name']) . "</h2>
            <p>Price: $" . htmlspecialchars($row['price']) . "</p>
            <a href='product_detail.php?product_id=" . $row['product_id'] . "'>View Details</a>
            </div>";
            }
            echo "</div>";
        } else {
            echo "<p>No products found for this brand.</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Please select a brand to view products.</p>";
    }

    $conn->close();
    ?>

</body>

</html>