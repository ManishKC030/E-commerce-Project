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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShoesHub - My Account</title>
  <link rel="shortcut icon" href="icons/account.svg" type="image/x-icon">
  
  <style>
    /* General Page Styling */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f3f4f6;
    }

    /* Container */
    .account-container {
      max-width: 500px;
      margin: 80px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    /* Profile Heading */
    .account-container h2 {
      color: #333;
      font-size: 24px;
      margin-bottom: 10px;
    }

    /* Profile Details */
    .details {
      text-align: left;
      background: #f9f9f9;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .details p {
      font-size: 16px;
      color: #444;
      margin: 10px 0;
    }

    .details strong {
      font-weight: bold;
      color: #222;
    }

    /* Logout Button */
    .logout-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #ff4d4d;
      color: white;
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      border-radius: 5px;
      transition: 0.3s ease;
    }

    .logout-btn:hover {
      background-color: #cc0000;
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <?php require 'navbar.php'; ?>
  <br><br>

  <div class="account-container">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <div class="details">
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
      <p><strong>Joined On:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
  <br><br<br><br><br>

  <!-- Footer -->
  <?php require 'footer.php'; ?>

</body>

</html>
