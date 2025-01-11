<?php
// Start the session
session_start();

// Database connection 
require "../connection.php";

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['admin_id'])) {
    header("Location: ad_login.php");
    exit();
}

// Get the logged-in admin_id from the session
$admin_id = $_SESSION['admin_id'];

// Fetch admin details from the database
$query = "SELECT * FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Fetch the products associated with this admin (if any)
$product_query = "SELECT * FROM products WHERE admin_id = ?";
$product_stmt = $conn->prepare($product_query);
$product_stmt->bind_param("i", $admin_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

// Close the statements
$stmt->close();
$product_stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Profile</title>
<style>
    /* Basic Styles for Profile Page */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}

header {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    text-align: center;
}

header nav {
    margin-top: 10px;
}

header nav a {
    color: white;
    margin: 0 15px;
    text-decoration: none;
}

main {
    padding: 20px;
    max-width: 800px;
    margin: 20px auto;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.profile-details {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.profile-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-right: 20px;
}

h2 {
    color: #333;
}

h3 {
    color: #333;
    margin-top: 40px;
}

.product-list {
    list-style: none;
    padding: 0;
}

.product-list li {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.product-image {
    width: 100px;
    height: 100px;
    margin-right: 20px;
}

</style>
</head>
<body>
    <header>
        <h1>Admin Profile</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    
    <main>
        <h2>Profile Details</h2>

        <!-- Display Admin Profile Info -->
        <div class="profile-details">
            <img src="<?php echo $admin['Shop_Logo']; ?>" alt="Shop Logo" class="profile-image">
            <p><strong>Name:</strong> <?php echo $admin['Name']; ?></p>
            <p><strong>Email:</strong> <?php echo $admin['Email']; ?></p>
            <p><strong>Shop Name:</strong> <?php echo $admin['Shop_Name']; ?></p>
            <p><strong>Shop Address:</strong> <?php echo $admin['Shop_Address']; ?></p>
            <p><strong>About Shop:</strong> <?php echo $admin['About_shop']; ?></p>
        </div>

        <!-- Display Products -->
        <h3>Products</h3>
        <?php if ($product_result->num_rows > 0): ?>
            <ul class="product-list">
                <?php while ($product = $product_result->fetch_assoc()): ?>
                    <li>
                        <img src="<?php echo $product['product_image']; ?>" alt="Product Image" class="product-image">
                        <p><strong>Name:</strong> <?php echo $product['product_name']; ?></p>
                        <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                        <p><strong>Description:</strong> <?php echo $product['description']; ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No products found for this shop.</p>
        <?php endif; ?>

    </main>
</body>
</html>
