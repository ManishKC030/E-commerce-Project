<?php
// Start the session to store user information
session_start();


include 'connection.php';
include 'validation.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];


    $errors = validateInput($name, $email, $password, $phone);
    // Insert data into the database

    if (empty($errors)) {


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
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="shortcut icon" href="icons/registered.svg" type="image/x-icon">
    <link rel="stylesheet" href="stylesheet/login-register.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
        rel="stylesheet" />

</head>

<body>

    <div class="container">
        <!-- Left Section -->
        <div class="left">
            <h1>Welcome to ShoesHub!</h1>
            <p>Step Into Style with Our Latest Footwear Collection</p>
            <img src="assets/register.png" alt="Shop Illustration" />
        </div>

        <!-- Right Section -->
        <div class="right">
            <h1 style="margin-top: 10px">Sign Up</h1>

            <form action="" method="post">
                <div class="form-group" style="margin-bottom: 15px">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter your Full Name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" required placeholder="Enter your phone number">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Set Up your password">
                </div>
                <div class="options">
                    <label>
                        <input type="checkbox" id="showPassword" /> Show Password
                    </label>
                    <a href="login.php">Already have an account? &nbsp;Login.</a>
                </div>
                <button type="submit">Sign UP</button>
            </form>
        </div>
    </div>


    <script>
        // JavaScript to toggle password visibility
        document.getElementById('showPassword').addEventListener('change', function() {
            var passwordField = document.getElementById('password');
            if (this.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        });
    </script>
</body>

</html>