<?php
include '../connection.php';
include 'auth.php';
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
    <form id="shop-form">
      <img id="image-preview" src="" alt="" />

      <label for="shop-image">Upload Picture:</label>
      <input
        type="file"
        id="shop-image"
        accept="image/*"
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