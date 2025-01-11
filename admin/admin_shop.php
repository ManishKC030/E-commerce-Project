<?php
// Start the session
session_start();

// Database connection 
require "../connection.php";

$error = '';
$success = '';
$Shop_Logo = '';


// Check if the user is logged in and has a valid session
if (!isset($_SESSION['admin_id'])) {
    header("Location: ad_login.php");
    exit();
}

// Get the logged-in admin_id from the session
$admin_id = $_SESSION['admin_id'];

// Form submission check
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $Shop_Name = $_POST['Shop_Name'];
    $Shop_Address = $_POST['Shop_Address'];
    $About_shop = $_POST['About_shop'];

    //validate inputs
    if (empty($Shop_Name) || empty($Shop_Address) || empty($About_shop)) {
        $error = 'Please fill in all fields';
    } else {
        $Shop_Logo = isset($_FILES['Shop_Logo']['name']) ? $_FILES['Shop_Logo']['name'] : '';
    }


    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("UPDATE admins SET Shop_Name = ?, Shop_Logo = ?, Shop_Address = ?, About_shop = ? WHERE admin_id = ?");
    $stmt->bind_param("ssssi", $Shop_Name, $Shop_Logo, $Shop_Address, $About_shop, $admin_id); // s=string, i=integer

    // Execute query and check if successful
    if ($stmt->execute()) {
        header("Location: ad_index.php"); // Redirection after success
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Create Shop</title>
    <link rel="stylesheet" href="ad_style/admin_shop.css" />
</head>

<body>
    <header>
        <h1>Admin Panel - Create Shop</h1>
    </header>
    <main>
        <h2>Create a New Shop</h2>

        <!-- Display success or error messages -->
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Form for shop creation -->
        <form id="shop-form" action="admin_shop.php" method="post" enctype="multipart/form-data">


            <div id="image-preview-container">
                <img id="image-preview" src="" alt="">
            </div>
            <!-- Shop Logo Upload -->
            <label for="Shop-Logo">Upload Picture:</label>
            <input
                type="file"
                name="Shop_Logo"
                accept="image/*"
                required
                onchange="previewImage(event)" />


            <!-- Shop Name Input -->
            <label for="Shop-Name">Shop Name:</label>
            <input
                type="text"
                name="Shop_Name"
                id="shop-name"
                placeholder="Enter shop name"
                value="<?php echo isset($Shop_Name) ? $Shop_Name : ''; ?>"
                required />

            <!-- Shop Address Input -->
            <label for="Shop-Address">Shop Address:</label>
            <input
                type="text"
                name="Shop_Address"
                id="shop-address"
                placeholder="Enter shop address"
                value="<?php echo isset($Shop_Address) ? $Shop_Address : ''; ?>"
                required />

            <!-- About Shop Textarea -->
            <label for="About-shop">About Shop:</label>
            <textarea
                name="About_shop"
                id="shop-about"
                placeholder="Write about the shop..."
                rows="5"
                required><?php echo isset($About_shop) ? $About_shop : ''; ?></textarea>

            <!-- Submit Button -->
            <button type="submit">Submit</button>
        </form>
    </main>

    <script>
        // Function to preview the uploaded image
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById("image-preview");
            const previewContainer = document.getElementById("image-preview-container");

            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.src = reader.result; // Set image source to the uploaded file
                    preview.style.display = "block"; // Show the preview image
                    previewContainer.style.display = "block"; // Ensure the preview container is shown
                };
                reader.readAsDataURL(file); // Read the file as a data URL for preview
            } else {
                preview.style.display = "none"; // Hide the preview if no file is selected
                previewContainer.style.display = "none"; // Hide the preview container if no file is selected
            }
        }
    </script>
</body>

</html>