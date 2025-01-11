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

// Form submission check
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $Shop_Name = $_POST['Shop_Name'];
    $Shop_Logo = $_POST['Shop_Logo'];
    $Shop_Address = $_POST['Shop_Address'];
    $About_shop = $_POST['About_shop'];

    // Prepare SQL statement to insert into admins table
    $sql = "UPDATE admins 
            SET Shop_Name = '$Shop_Name', Shop_Logo = '$Shop_Logo', Shop_Address = '$Shop_Address', About_shop = '$About_shop' 
            WHERE admin_id = '$admin_id'";

    // Execute query and check if successful
    if ($conn->query($sql) === TRUE) {
        echo "Shop details updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>