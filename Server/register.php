<?php
// Start the session to store user information
session_start();


include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

// Insert data into the database
    $sql = "INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $password, $phone);

    if ($stmt->execute()) {
        // Get the last inserted user ID and store it in the session
        $_SESSION['user_id'] = $conn->insert_id; // Store user ID in session

        
        header("Location: account.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>