 <?php
        // PHP Code Starts Here
        include 'connection.php'; // Ensure this file connects to your database

        $query = isset($_GET['query']) ? trim($_GET['query']) : '';

        if (!empty($query)) {
            $sql = "SELECT p.*, c.name AS category_name
                  FROM products p
                  JOIN categories c ON p.category_id = c.id
                  WHERE p.name LIKE ? OR p.type LIKE ?";

            $stmt = $conn->prepare($sql);
            $searchTerm = "%" . $query . "%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>
                          <a href='product_detail.php?product_id=" . $row['product_id'] . "'>
                              <img src='uploads/" . htmlspecialchars($row['image1']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width:50px; height:50px;'>
                              <div>
                                  <strong>" . htmlspecialchars($row['name']) . "</strong> (" . htmlspecialchars($row['category_name']) . ")<br>
                                  <span class='price'>Price: NPR " . number_format($row['price'], 2) . "</span>
                              </div>
                          </a>
                      </li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='no-results'>No results found for '" . htmlspecialchars($query) . "'.</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='no-results'>Please enter a search term.</p>";
        }

        $conn->close();
        ?>