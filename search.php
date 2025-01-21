<?php
// PHP Code Starts Here
include 'connection.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : ''; // Ensure $query is defined
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';         // Ensure $sort is defined
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap");

        .search {
            width: 90%;

            margin: 20px auto;

            text-align: center;
        }

        .search h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .product-container2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* Make products stack vertically */
            gap: 20px;
            /* Add space between each product */
        }

        .product2 {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 15px;
            position: relative;
        }

        .product2:hover {
            transform: translateY(-5px);
        }

        .cart-button {
            position: absolute;
            bottom: 17px;
            right: 10px;
            background-color: rgb(91, 92, 92);
            /* Green background */
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
            text-decoration: none;
        }

        .product2:hover .cart-button {
            opacity: 1;
            /* Make the button visible on hover */
        }

        .cart-button:hover {
            background-color: rgb(31, 30, 30);

        }

        .search_img {
            width: 250px;
            height: 30vh;
            object-fit: cover;
            flex-shrink: 0;
        }

        .product2-info {
            padding: 15px;
            flex: 1;
            text-align: left;

            flex-direction: column;
            justify-content: center;
            /* Center align the text vertically */
        }

        .product-brand {
            font-family: "Sour Gummy", serif;
            font-size: 22px;
            font-weight: 690;
        }

        .product2-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .product2-description {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .product2-price {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }

        .product2-type {
            font-size: 14px;
            color: #777;
        }

        .pro2Detail {
            display: flex;
            text-decoration: none;
            /* Removes link underline */
            color: inherit;
            /* Inherits text color */
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .product-container2 {
                grid-template-columns: 1fr;
                /* Switch to one product per row on small screens */
            }

            .product2 {
                flex-direction: column;
                /* Stack items vertically on smaller screens */
                align-items: center;
                /* Center align the items */
                text-align: center;
                /* Center align text */
            }

            .best_img {
                width: 100%;
                /* Make image take full width */
                height: auto;
                /* Maintain aspect ratio */
            }

            .product2-info {
                padding: 10px;
            }
        }

        /* Styling for the filter dropdown */
        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-right: 10px;
            color: #333;
            font-family: "Sour Gummy", serif;
        }

        select {
            font-size: 16px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: "Sour Gummy", serif;
            position: relative;
        }

        select:hover {
            border-color: #888;
            background-color: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        select:focus {
            outline: none;
            border-color: #333;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        /* Dropdown hover effect */
        select option {
            padding: 10px;
            background-color: #fff;
            color: #333;
        }

        select option:hover {
            background-color: #f0f0f0;
            color: #000;
        }

        /* Add some space between the label and dropdown */
        form label {
            margin-right: 10px;
        }

        /* Center the entire search result section */
        .search {
            text-align: center;
            padding: 20px;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            select {
                width: 100%;
                font-size: 14px;
            }

            form {
                flex-direction: column;
                align-items: stretch;
            }

            form label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="search">
        <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
        <form method="GET" action="search.php">
            <input type="hidden" name="query" value="<?php echo htmlspecialchars($query); ?>">
            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="">Default</option>
                <option value="price_asc" <?php if ($sort == 'price_asc') echo 'selected'; ?>>Price: Low to High</option>
                <option value="price_desc" <?php if ($sort == 'price_desc') echo 'selected'; ?>>Price: High to Low</option>
                <option value="name_asc" <?php if ($sort == 'name_asc') echo 'selected'; ?>>Name: A-Z</option>
                <option value="name_desc" <?php if ($sort == 'name_desc') echo 'selected'; ?>>Name: Z-A</option>
                <option value="stock_asc" <?php if ($sort == 'stock_asc') echo 'selected'; ?>>Stock: Low to High</option>
                <option value="stock_desc" <?php if ($sort == 'stock_desc') echo 'selected'; ?>>Stock: High to Low</option>
            </select>
        </form>

        <div class="product-container2">
            <?php
            if (!empty($query)) {
                $sql = "SELECT p.*, c.name AS category_name
                        FROM products p
                        JOIN categories c ON p.category_id = c.id
                        WHERE p.name LIKE ? OR p.type LIKE ?";

                // Adjust SQL based on sorting option
                switch ($sort) {
                    case 'price_asc':
                        $sql .= " ORDER BY p.price ASC";
                        break;
                    case 'price_desc':
                        $sql .= " ORDER BY p.price DESC";
                        break;
                    case 'name_asc':
                        $sql .= " ORDER BY p.name ASC";
                        break;
                    case 'name_desc':
                        $sql .= " ORDER BY p.name DESC";
                        break;
                    case 'stock_asc':
                        $sql .= " ORDER BY p.stock ASC";
                        break;
                    case 'stock_desc':
                        $sql .= " ORDER BY p.stock DESC";
                        break;
                }

                $stmt = $conn->prepare($sql);
                $searchTerm = "%" . $query . "%";
                $stmt->bind_param("ss", $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($product = $result->fetch_assoc()) {
                        echo '
                        <div class="product2">
                            <a href="product_detail.php?product_id=' . htmlspecialchars($product['product_id']) . '" class="pro2Detail">
                                <img class="search_img" src="uploads/' . htmlspecialchars($product['image1']) . '" alt="' . htmlspecialchars($product['name']) . '">
                                <div class="product2-info">
                                    <p class="product-brand">' . htmlspecialchars($product['brand']) . '</p>
                                    <p class="product2-name">' . htmlspecialchars($product['name']) . '</p>
                                    <p class="product2-type">' . htmlspecialchars($product['type']) . '</p>
                                    <p class="product2-description">' . htmlspecialchars($product['short_desc']) . '</p>
                                    <p class="product2-price">$ ' . number_format($product['price'], 2) . '</p>
                                    <p>Stock: ' . htmlspecialchars($product['stock']) . '</p>
                                </div>
                            </a>
                            <a href="cart.php?add_to_cart=1&product_id=' . htmlspecialchars($product['product_id']) . '&quantity=1" class="cart-button">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </a>
                        </div>';
                    }
                } else {
                    echo "<p class='no-results'>No results found for '" . htmlspecialchars($query) . "'.</p>";
                }

                $stmt->close();
            } else {
                echo "<p class='no-results'>Please enter a search term.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>