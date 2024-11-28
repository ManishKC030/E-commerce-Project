

<?php

// Start session to store user information
session_start();

// Include the database connection
include 'connection.php';


// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Email and Password are required!";
    }
    else
    {

        $sql = "SELECT user_id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

   // Check if user exists
   if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    

       // Verify password
       if ($password === $user['password']) {
        // If password is correct, store user ID in session
        $_SESSION['user_id'] = $user['user_id'];
        
        // Redirect to the account page after successful login
        header("Location: account.php");
        exit;
    } else {
        $message = "Invalid password.";
    }
} else {
    $message = "No account found with that email.";
}
    

$stmt->close();
$conn->close();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="stylesheet/login.css">
  
    
</head>
<body>
  
    <div class="login-container">
        <h2>Login</h2>
                <!-- Display error message -->
        <?php if (!empty($message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form id="loginForm" action="login.php" method="post" >
            <div class="form-group">
                <label for="email">Email</label>

                <div class="userArea">
                    
                <input type="email" id="email" name="email" required  placeholder="Enter your  email">
            </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            <div class="show-password-container">
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label>
            </div><br>
            <button type="submit" class="btn">Login</button>
            <p class="signup-link">Don't have an Account? <a href="register.php">Register</a></p>
        </form>
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
