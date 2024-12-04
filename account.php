<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, phone, created_at FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
} else {
  echo "User not found.";
  exit;
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShoesHub - My Account</title>
  <link rel="shortcut icon" href="icons/account.svg" type="image/x-icon">

  <style>
    .account {
      max-width: 600px;
      margin: 0 auto;
      text-align: center;
    }

    .details {
      border: 1px solid #ddd;
      padding: 20px;
      margin-top: 20px;
      border-radius: 8px;
      background-color: #f9f9f9;
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <?php
  require 'navbar.php';
  ?>



  <div class="account">
    <br><br><br>
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <br>
    <div class="details">
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
      <p><strong>Joined On:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div><br><br>
    <a href="logout.php">Logout</a>

  </div>
  <br><br><br><br><br>
  <br><br><br><br><br>
  <br><br><br><br><br>



  <?php
  require 'footer.php';
  ?>


</body>

</html>