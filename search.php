<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #555;
        }

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            margin-left: 10px;
            font-size: 16px;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
            transition: box-shadow 0.3s ease;
        }

        li:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        img {
            border-radius: 5px;
            margin-right: 15px;
        }

        strong {
            font-size: 18px;
            color: #333;
        }

        .price {
            color: #007bff;
            font-weight: bold;
        }

        .no-results {
            text-align: center;
            color: #777;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Search Products</h1>
        <form action="" method="GET">
            <input type="text" name="query" placeholder="Search for products..." value="<?php echo htmlspecialchars($query); ?>">
            <button type="submit">Search</button>
        </form>

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
    </div>
</body>

</html>