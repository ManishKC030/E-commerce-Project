<?php
// Database connection
include "../connection.php";
include "ad_nav.php";
include "auth.php";

// Initialize variables
$brand = '';
$name = '';
$type = '';
$short_desc = '';
$description = '';
$price = '';
$stock = '';
$category_id = '';
$image1 = '';
$image2 = '';
$image3 = '';
$image4 = '';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand']);
    $name = trim($_POST['name']);
    $short_desc = trim($_POST['short_desc']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : NULL;


    // Validate inputs
    if (empty($name) || empty($price) || empty($stock)) {
        $error = "Name, Price, and Stock fields are required.";
    } elseif (!is_numeric($price) || !is_numeric($stock)) {
        $error = "Price and Stock must be numeric values.";
    } else {
        $image1 = isset($_FILES['image1']['name']) ? $_FILES['image1']['name'] : '';
        $image2 = isset($_FILES['image2']['name']) ? $_FILES['image2']['name'] : '';
        $image3 = isset($_FILES['image3']['name']) ? $_FILES['image3']['name'] : '';
        $image4 = isset($_FILES['image4']['name']) ? $_FILES['image4']['name'] : '';
    }





    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO products ( brand, name, type, short_desc, description, price, stock, category_id, image1, image2, image3, image4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdiissss", $brand, $name, $type, $short_desc, $description, $price, $stock, $category_id, $image1, $image2, $image3, $image4);

    if ($stmt->execute()) {
        $success = "Product added successfully!";
        // Clear form inputs
        $brand = $name = $short_desc = $type = $description = $price = $stock = $category_id = '';
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
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

        }

        h1 {
            margin-left: 25px;
        }

        .form-group {
            margin-left: 20px;
            margin-bottom: 15px;
            width: 900px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 20px;
        }

        .btn:hover {
            background-color: #218838;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>
    <h1>Add New Product</h1>

    <?php if ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="brand">Brand Name:</label>
            <input type="text" name="brand" id="brand" required>
        </div>

        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="type">Shoes Type:</label>
            <input type="text" name="type" id="type" required>
        </div>

        <div class="form-group">
            <label for="short_desc">Short Description:</label>
            <input type="text" name="short_desc" id="short_desc" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>
        </div>
        <div class="form-group">

            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" required>
        </div>
        <div class="form-group">
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
        </div>

        <div class="form-group">
            <label for="image1">Image 1 URL:</label>
            <input type="file" accept="image/jpeg, image/png" name="image1" id="image1">
        </div>

        <div class="form-group">
            <label for="image2">Image 2 URL:</label>
            <input type="file" accept="image/jpeg, image/png" name="image2" id="image2">
        </div>

        <div class="form-group">
            <label for="image3">Image 3 URL:</label>
            <input type="file" accept="image/jpeg, image/png" name="image3" id="image3">
        </div>

        <div class="form-group">
            <label for="image4">Image 4 URL:</label>
            <input type="file" accept="image/jpeg, image/png" name="image4" id="image4">
        </div>
        <br>
        <button type="submit" class="btn">Add Product</button>
    </form>
</body>

</html>