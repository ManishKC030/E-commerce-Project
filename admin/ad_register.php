<?php
// Start the session to store user information
session_start();


include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['ad_name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $phone = $_POST['phone'];

  // Insert data into the database
  $sql = "INSERT INTO admins (ad_name, email, password, phone) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssi", $name, $email, $password, $phone);

  if ($stmt->execute()) {
    // Get the last inserted user ID and store it in the session
    $_SESSION['admin_id'] = $conn->insert_id; // Store user ID in session


    header("Location: admin_shop.php");
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Become a Seller - Sign Up</title>
  <link rel="stylesheet" href="ad_style/ad-login-register.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
    rel="stylesheet" />
</head>

<body>
  <div class="container">
    <!-- Left Section -->
    <div class="left">
      <h1>Become a Seller!</h1>
      <p>Manage your shop efficiently on ShoesHub Seller Centre.</p>
      <img src="ad_assets/register.png" alt="Shop Illustration" />
    </div>

    <!-- Right Section -->
    <div class="right">
      <h1 style="margin-top: 10px">Sign Up</h1>

      <form action="" method="post">
        <div class="form-group" style="margin-bottom: 15px">
          <label for="ad_name">Name</label>
          <input
            type="text"
            id="ad_name"
            name="ad_name"
            required
            placeholder="Enter your Full Name" />
        </div>
        <div class="form-group">
          <label for="email">Email ID</label>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your Email"
            required />
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input
            type="text"
            id="phone"
            name="phone"
            required
            placeholder="Enter your phone number" />
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
          <a href="ad_login.php">Already have an account? &nbsp;Login.</a>
        </div>
        <button type="submit">Sign UP</button>
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