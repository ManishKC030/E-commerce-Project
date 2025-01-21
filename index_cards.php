<?php
// Database connection
include 'connection.php';
// Get category from query parameter
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch products based on category
$sql = "";
if ($category === 'best_selling') {
    $sql = "SELECT * FROM products WHERE category = 'Best Selling'";
} elseif ($category === 'new_arrival') {
    $sql = "SELECT * FROM products WHERE category = 'New Arrival'";
} elseif ($category === 'classics') {
    $sql = "SELECT * FROM products WHERE category = 'Classics'";
} elseif ($category === 'collabs') {
    $sql = "SELECT * FROM products WHERE category = 'Collabs'";
}

$result = $conn->query($sql);
?>

<div class="products">
    <h2>
        <?php
        echo ucfirst(str_replace('_', ' ', $category));
        ?>
    </h2>
    <div class="product-grid">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>";
                echo "<img src='" . $row['image_url'] . "' alt='" . $row['product_name'] . "'>";
                echo "<h3>" . $row['product_name'] . "</h3>";
                echo "<p>Price: Rs. " . $row['price'] . "</p>";
                echo "<a href='product_details.php?id=" . $row['id'] . "' class='details-button'>View Details</a>";
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