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
    <link rel="stylesheet" href="stylesheet/nav-footer.css" />
    
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
    <header>
        <div class="top-bar">
          <div class="left-nav">
            <ul>
              
              <li><a href="#">About Us</a></li>
              <li><a href="conatct.html">Contact Us: 061-587123 </a></li>
            </ul>
          </div>
          <div class="right-nav">
            <ul>
              
              <li><a href="store_location.html">Store Location</a></li>
              <li><a href="account.php">My Account</a></li>
              <li><a href="login.php">Login/Register</a></li>
            </ul>
          </div>
        </div>
  
        <!-- Main Navigation -->
        <nav class="main-nav">
          <a href="index.html" style="text-decoration: none;">  <h1 class="logo">ShoesHub</h1></a>
          <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="mens.html">Men</a></li>
            <li><a href="#">Women</a></li>
            <li><a href="kids.html">Kids</a></li>
            
            <li><a href="#">Cart</a></li>
            <li><a href="#">Wishlist</a></li>
          </ul>
        </nav>
      </header>


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



    <footer>
      <div class="footer-container">
          <div class="footer-left">
              <div class="footer-logo">
                  
                  <h3>ShoesHub</h3>
                  
              </div>
              <address>
Pokhara,Gandaki Pradesh,Nepal<br>
                  <a href=" example@shoeshub.com">example@shoeshub.com</a><br>
                  061-587123
              </address>
          </div>
          <div class="footer-column">
              <h4>Shopping & Categories</h4>
              <ul>
                  <li><a href="#">Men's Shopping</a></li>
                  <li><a href="#">Women's Shopping</a></li>
                  <li><a href="#">Kid's Shopping</a></li>
                  <li><a href='#'>Exclusive</a></li>
                </ul>
          </div>
          <div class="footer-column">
              <h4>Useful Links</h4>
              <ul>
                  <li><a href="#">Homepage</a></li>
                  <li><a href="#">Store Location</a></li>
                  
                  <li><a href="#">Contact Us</a></li>
                  <li><a href="#">About Us</a></li>
              </ul>
          </div>
          <div class="footer-column">
              <h4>Help & Information</h4>
              <ul>
                  <li><a href="#">Return Policy</a></li>
                  <li><a href="#">Order Tracking</a></li>
                  <li><a href="#">Shipping Charges</a></li>
                  <li><a href="#">Tracking ID</a></li>
              </ul>
          </div>
      </div>
      <div class="footer-bottom">
          <p>Copyright &copy; 2024 ShoesHub Co. Ltd. All Rights Reserved.</p>
          <p>Terms & Condition</p>
          <p>FAQ's</p>
          <div class="social-icons">
              <a href="#"><img src="icons/facebook-brands-solid.svg" alt="Facebook"></a>
              <a href="#"><img src="icons/x-twitter-brands-solid.svg" alt="X-Twitter"></a>
              <a href="#"><img src="icons/instagram (1).svg" alt="Instagram"></a>
          
          </div>
      </div>
  </footer>

</body>
</html>
