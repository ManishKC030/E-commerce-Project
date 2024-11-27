<?php

// Start session to store user information
session_start();

// Include the database connection
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

   // Check if user exists
   if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    

       // Verify password
       if (password_verify($password, $user['password'])) {
        // If password is correct, store user ID in session
        $_SESSION['user_id'] = $user['id'];
        
        // Redirect to the account page after successful login
        header("Location: account.php");
        exit;
    } else {
        $message = "Invalid password.";
    }
} else {
    $message = "No user found with that email.";
}

$stmt->close();
$conn->close();
}
?>