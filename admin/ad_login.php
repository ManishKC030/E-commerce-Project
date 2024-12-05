<?php

// Start session to store user information
session_start();

// Include the database connection
include '../connection.php';


// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    $message = "Email and Password are required!";
  } else {

    $sql = "SELECT admin_id, password FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
      $admin = $result->fetch_assoc();


      // Verify password
      if ($password === $admin['password']) {
        // If password is correct, store user ID in session
        $_SESSION['admin_id'] = $admin['admin_id'];

        // Redirect to the account page after successful login
        header("Location: ad_account.php");
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Become a Seller - Login</title>
  <link rel="stylesheet" href="ad_style/ad-login-register.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
    rel="stylesheet" />
</head>

<body>
  <div class="container">
    <!-- Left Section -->
    <div class="left">
      <h1>Welcome Back!</h1>
      <p>Manage your shop efficiently on ShoesHub Seller Centre.</p>
      <img src="ad_assets/login.png" alt="Shop Illustration" />
    </div>

    <!-- Right Section -->
    <div class="right">
      <h1>LogIn</h1>

      <?php if (!empty($message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <form action="" method="post">
        <div class="form-group">
          <label for="email">Email ID</label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your Email"
            required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="••••••••••"
            required />
        </div>
        <div class="options">
          <label>
            <input type="checkbox" id="viewPassword" /> Show Password
          </label>
          <a href="ad_register.php">Dont' have an account? &nbsp;Register Here.</a>
        </div>
        <button type="submit">LOGIN</button>
      </form>
    </div>
  </div>

  <script>
    // JavaScript to toggle password visibility
    document
      .getElementById("viewPassword")
      .addEventListener("change", function() {
        var passwordField = document.getElementById("password");
        if (this.checked) {
          passwordField.type = "text";
        } else {
          passwordField.type = "password";
        }
      });
  </script>
</body>

</html>