<?php
// Database connection
include "../connection.php";
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    $image1 = $conn->real_escape_string($_POST['image1']);
    $image2 = $conn->real_escape_string($_POST['image2']);
    $image3 = $conn->real_escape_string($_POST['image3']);
    $image4 = $conn->real_escape_string($_POST['image4']);

    // SQL to insert product
    $sql = "INSERT INTO products (name, description, price, stock, category_id, image1, image2, image3, image4)
            VALUES ('$name', '$description', $price, $stock, $category_id, '$image1', '$image2', '$image3', '$image4')";

    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch categories for the dropdown
$categories = $conn->query("SELECT id, name FROM categories");

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        form {
            max-width: 600px;
            margin: auto;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Add New Product</h1>
    <form action="" method="POST">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="5" required></textarea>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" id="price" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required>

        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <option value="">-- Select Category --</option>
            <?php if ($categories->num_rows > 0): ?>
                <?php while ($row = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="">No categories available</option>
            <?php endif; ?>
        </select>

        <label for="image1">Image 1 URL:</label>
        <input type="text" name="image1" id="image1" required>

        <label for="image2">Image 2 URL:</label>
        <input type="text" name="image2" id="image2">

        <label for="image3">Image 3 URL:</label>
        <input type="text" name="image3" id="image3">

        <label for="image4">Image 4 URL:</label>
        <input type="text" name="image4" id="image4">

        <button type="submit">Add Product</button>
    </form>
</body>

</html>