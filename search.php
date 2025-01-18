<?php
// Include the database connection
include 'connection.php'; // Ensure this file connects to your database

// Get the search query from the form
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    // Prevent SQL injection by using prepared statements
    $sql = "SELECT p.product_id, p.name, p.description, p.price, p.image1, c.name AS category_name
          FROM products p
          JOIN categories c ON p.category_id = c.id
          WHERE p.name LIKE ? OR p.description LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        echo "<h1>Search Results for '$query':</h1>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>
              <a href='product_detail.php?product_id=" . $row['product_id'] . "'>
                <img src='uploads/" . htmlspecialchars($row['image1']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width:50px; height:50px;'>
                <strong>" . htmlspecialchars($row['name']) . "</strong> (" . htmlspecialchars($row['category_name']) . ")<br>
                Price: NPR " . number_format($row['price'], 2) . "
              </a>
            </li>";
        }
        echo "</ul>";
    } else {
        echo "<h1>No results found for '$query'.</h1>";
    }

    $stmt->close();
} else {
    echo "<h1>Please enter a search term.</h1>";
}

// Close the database connection
$conn->close();
