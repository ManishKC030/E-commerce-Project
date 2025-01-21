<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shoes_hub";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define categories mapping
$categories_map = [
    'best_selling' => 1, // Replace 1 with the actual ID of "Best Selling" in your `categories` table
    'new_arrival' => 2,  // Replace 2 with the actual ID of "New Arrival"
    'collabs' => 4       // Replace 4 with the actual ID of "Collabs"
];

// Get category from query parameter
$category_key = isset($_GET['category']) ? $_GET['category'] : '';
$sql = ""; // SQL query placeholder

// Handle category-based or type-based fetching
if ($category_key === 'classics') {
    // Fetch products where `type` is "Classics"
    $sql = "SELECT * FROM products WHERE type = 'Classics'";
} elseif (isset($categories_map[$category_key])) {
    // Fetch products based on `category_id`
    $category_id = $categories_map[$category_key];
    $sql = "SELECT p.*, c.name AS category_name FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.category_id = ?";
}

// Execute the query if a valid SQL statement is set
$result = null;
if (!empty($sql)) {
    if ($category_key === 'classics') {
        $result = $conn->query($sql);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<div class="products">
    <h2>
        <?php
        echo ucfirst(str_replace('_', ' ', $category_key));
        ?>
    </h2>
    <div class="product-grid">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>";
                echo "<img src='" . $row['image1'] . "' alt='" . htmlspecialchars($row['name'], ENT_QUOTES) . "'>";
                echo "<h3>" . htmlspecialchars($row['name'], ENT_QUOTES) . "</h3>";
                echo "<p>Brand: " . htmlspecialchars($row['brand'], ENT_QUOTES) . "</p>";
                echo "<p>Price: Rs. " . number_format($row['price'], 2) . "</p>";
                echo "<p>Stock: " . $row['stock'] . "</p>";
                echo "<a href='product_details.php?id=" . $row['product_id'] . "' class='details-button'>View Details</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No products found for this category.</p>";
        }
        ?>
    </div>
</div>

<?php
$conn->close();
?>