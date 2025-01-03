<?php
include '../connection.php';
include 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $shopName = $_POST['shop_name'];
  $shopAddress = $_POST['shop_address'];
  $aboutShop = $_POST['shop_about'];

  $shopLogo = '';
  if (!empty($_FILES['shop_image']['tmp_name'])) {
    $uploadDir = '../uploads/';
    $shopLogo = uniqid() . '-' . $_FILES['shop_image']['name'];
    $uploadFile = $uploadDir . $shopLogo;

    if (!move_uploaded_file($_FILES['shop_image']['tmp_name'], $uploadFile)) {
      echo "<script>alert('Error uploading image.');</script>";
      $shopLogo = '';
    }
  }

  if ($shopLogo) {
    $sql = "INSERT INTO admins (Shop_Name, Shop_Logo, Shop_Address, About_shop) VALUES ('$shopName', '$shopLogo', '$shopAddress', '$aboutShop')";

    if (mysqli_query($conn, $sql)) {
      echo "<script>alert('Shop created successfully!'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
      echo "<script>alert('Database error: " . mysqli_error($conn) . "');</script>";
    }
  }
}
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
    <form id="shop-form" action="" enctype="multipart/form-data">
      <img id="image-preview" src="" alt="" />
      <label for="shop-image">Upload Picture:</label>
      <input
        type="file"
        id="shop-image"
        accept="image/jpeg, image/png"
        onchange="previewImage(event)"
        required />

      <label for="shop-name">Shop Name:</label>
      <input
        type="text"
        id="shop-name"
        placeholder="Enter shop name"
        required />

      <label for="shop-address">Shop Address:</label>
      <input
        type="text"
        id="shop-address"
        placeholder="Enter shop address"
        required />

      <label for="shop-about">About Shop:</label>
      <textarea
        id="shop-about"
        placeholder="Write about the shop..."
        rows="5"
        required></textarea>

      <button type="submit">Create Shop</button>
    </form>


  </main>
  <script>
    // Function to preview the uploaded image
    function previewImage(event) {
      const file = event.target.files[0];
      const preview = document.getElementById("image-preview");

      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          preview.src = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        preview.src = "";
      }
    }

    // Reset form
    document.getElementById("shop-form").reset();
    document.getElementById("image-preview").src = "";
  </script>
</body>

</html>