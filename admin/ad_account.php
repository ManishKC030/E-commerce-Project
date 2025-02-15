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

// Close the statements
$stmt->close();

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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .profile-details {
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-image {

            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .profile-info {
            text-align: left;
            margin-bottom: 15px;
            font-size: 16px;
            color: #555;
        }

        .profile-info strong {
            font-weight: bold;
        }

        .profile-details img {
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
        }
    </style>
</head>

<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($admin['ad_name']); ?>!</h1>
        <nav>
            <a href="ad_index.php">Dashboard</a>
            <a href="ad_logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2 style="text-align: center;">Profile Details</h2>

        <!-- Display Admin Profile Info -->
        <div class="profile-details">
            <img src="../uploads/<?php echo htmlspecialchars($admin['Shop_Logo']); ?>" alt="Shop Logo" class="profile-image">

            <div class="profile-info">
                <p><strong>Shop Name:</strong> <?php echo htmlspecialchars($admin['Shop_Name']); ?></p>
                <p><strong>Shop Location:</strong> <?php echo htmlspecialchars($admin['Shop_Address']); ?></p>
                <p><strong>About Shop:</strong> <?php echo htmlspecialchars($admin['About_shop']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['ad_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($admin['phone']); ?></p>
                <p><strong>Joined On:</strong> <?php echo htmlspecialchars($admin['created_at']); ?></p>
            </div>
        </div>

    </main>
</body>

</html>