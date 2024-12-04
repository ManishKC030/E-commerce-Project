<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ad_login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$sql = "SELECT ad_name, email, phone, created_at FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    echo "Account not found.";
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
    <title>Become a Seller - My Account</title>
   

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

    <div class="account">
        <br><br><br>
        <h2>Welcome, <?php echo htmlspecialchars($admin['ad_name']); ?>!</h2>
        <br>
        <div class="details">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['ad_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($admin['phone']); ?></p>
            <p><strong>Joined On:</strong> <?php echo htmlspecialchars($admin['created_at']); ?></p>
        </div><br><br>
        <a href="ad_logout.php">Logout</a>

    </div>
    <br><br><br><br><br>
    <br><br><br><br><br>
    <br><br><br><br><br>




</body>

</html>