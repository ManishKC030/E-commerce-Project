<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ShoesHub - Shoes that fits your style </style></title>
    <link rel="stylesheet" href="stylesheet/contact.css">
  </head>
  <body>
   
  <?php
 require 'navbar.php';

 session_start();


 require 'connection.php';
 
// Initialize variables
$name = '';
$email = '';
$message = '';
$successMessage = '';
$errorMessage = '';

// Fetch user data if logged in

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = htmlspecialchars($user['username']);
        $email = htmlspecialchars($user['email']);
    }

    $stmt->close();
}
else{
   // For guest users, name and email will remain empty
   $user_id = null;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '$name';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '$email';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contacts (user_id, name, email, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isss", $user_id, $name, $email, $message);
        if ($stmt->execute()) {
            $successMessage = "Your message has been sent successfully!";
            $message = ''; // Clear the message field after successful submission
        } else {
            $errorMessage = "Error: Could not send your message. Please try again later.";
        }
        $stmt->close();
    } else {
        $errorMessage = "All fields are required.";
    }
}

$conn->close();
?>

    <div class="container">

      <div class="contact-info">
        <h1>Contact With Us</h1>
        <p>Let's Get In Touch</p>
        
        </div>

        <?php if (!empty($successMessage)): ?>
    <div class="success-message"><?php echo $successMessage; ?></div>
  <?php endif; ?>
  <?php if (!empty($errorMessage)): ?>
    <div class="error-message"><?php echo $errorMessage; ?></div>
  <?php endif; ?>


       <form action="" method="post">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?php echo $name; ?>"  <?php echo isset($_SESSION['user_id']) ? 'readonly' : ''; ?>  required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>"  <?php echo isset($_SESSION['user_id']) ? 'readonly' : ''; ?>  required>

    <label for="message">Message</label>
    <textarea id="message" name="message" rows="6" ><?php echo htmlspecialchars($message); ?></textarea>

    <button type="submit">Send Message</button>
  </form>


        <div class="below">You can also&nbsp;
            <p>Email Us: example@shoeshub.com</p>
        </div>
    </div><br><br>
 

    <?php
 require 'footer.php';
  ?>   

</body>
</html>
