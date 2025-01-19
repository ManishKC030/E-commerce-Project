<?php
// PHP Code Starts Here
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        /* Your CSS styling (unchanged for brevity) */
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="search">
        <h2>Search Results</h2>
        <form method="GET" action="search.php">
            <input type="hidden" name="query" value="<?php echo htmlspecialchars($query); ?>">
            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="">Default</option>
                <option value="price_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') echo 'selected'; ?>>Price: Low to High</option>
                <option value="price_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') echo 'selected'; ?>>Price: High to Low</option>
                <option value="name_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') echo 'selected'; ?>>Name: A-Z</option>
                <option value="name_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') echo 'selected'; ?>>Name: Z-A</option>
                <option value="stock_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'stock_asc') echo 'selected'; ?>>Stock: Low to High</option>
                <option value="stock_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'stock_desc') echo 'selected'; ?>>Stock: High to Low</option>
            </select>
        </form>

        <div class="product-container2">
            <?php
            $query = isset($_GET['query']) ? trim($_GET['query']) : '';
            $sort = isset($_GET['sort']) ? $_GET['sort'] : '';

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
                            <a href="cart.php?product_id=' . htmlspecialchars($product['product_id']) . '" class="cart-button">
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
