<?php
// Start session
session_start();

// Database connection
require "../connection.php";

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ad_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$query = "SELECT * FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shop_name = $_POST['shop_name'];
    $shop_address = $_POST['shop_address'];
    $about_shop = $_POST['about_shop'];
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // File upload handling
    if (!empty($_FILES['shop_logo']['name'])) {
        $target_dir = "../uploads/";
        $shop_logo = basename($_FILES["shop_logo"]["name"]);
        $target_file = $target_dir . $shop_logo;

        // Move uploaded file
        if (move_uploaded_file($_FILES["shop_logo"]["tmp_name"], $target_file)) {
            // Update query with shop logo
            $update_query = "UPDATE admins SET Shop_Name=?, Shop_Address=?, About_shop=?, ad_name=?, email=?, phone=?, Shop_Logo=? WHERE admin_id=?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssssssi", $shop_name, $shop_address, $about_shop, $admin_name, $email, $phone, $shop_logo, $admin_id);
        } else {
            echo "<script>alert('Error uploading file');</script>";
        }
    } else {
        // Update query without shop logo
        $update_query = "UPDATE admins SET Shop_Name=?, Shop_Address=?, About_shop=?, ad_name=?, email=?, phone=? WHERE admin_id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssssi", $shop_name, $shop_address, $about_shop, $admin_name, $email, $phone, $admin_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully'); window.location.href='admin_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        /* Same styling as profile page */
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .form-container {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .current-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .current-logo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <header>
        <h1>Update Profile</h1>
        <nav>
            <a href="admin_profile.php">Back to Profile</a>
            <a href="ad_logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Edit Your Profile</h2>
        <div class="current-logo">
            <p>Current Shop Logo:</p>
            <img src="../uploads/<?php echo htmlspecialchars($admin['Shop_Logo']); ?>" alt="Shop Logo">
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="form-container">
            <div class="form-group">
                <label for="shop_name">Shop Name:</label>
                <input type="text" id="shop_name" name="shop_name" value="<?php echo htmlspecialchars($admin['Shop_Name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="shop_address">Shop Location:</label>
                <input type="text" id="shop_address" name="shop_address" value="<?php echo htmlspecialchars($admin['Shop_Address']); ?>" required>
            </div>

            <div class="form-group">
                <label for="about_shop">About Shop:</label>
                <textarea id="about_shop" name="about_shop" rows="3" required><?php echo htmlspecialchars($admin['About_shop']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="admin_name">Owner Name:</label>
                <input type="text" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($admin['ad_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($admin['phone']); ?>" required>
            </div>

            <div class="form-group">
                <label for="shop_logo">Upload New Shop Logo (Optional):</label>
                <input type="file" id="shop_logo" name="shop_logo">
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </main>
</body>

</html>
