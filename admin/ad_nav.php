<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="ad_style/ad-nav.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
  </style>
</head>

<body>
  <nav>
    <header class="top-nav">
      <!-- Logo -->
      <div class="logo"><a href="ad_index.php" style="text-decoration: none;">
          <h1>ShoesHub - Seller</h1>
        </a>
      </div>

      <!-- Navigation Links -->
      <nav class="nav-links">
        
        <a href="ad_index.php">Dashboard</a>
        <a href="#orders">Orders</a>
        <div class="dropdown">
        <button class="dropbtn">Product</button>
          <div class="dropdown-content">
            <a href="ad_product.php">Add Product</a>
            <a href="ad_proview.php">View Product</a>
          </div>
        </div>
        <div class="dropdown">
        <button class="dropbtn">Categories</button>
          <div class="dropdown-content">
            <a href="ad_categories.php">Add Categories</a>
            <a href="#">View Categories</a>
          </div>
        </div>
        <a href="#">Messages</a>
        <a href="#settings">Settings</a>
      </nav>

      <!-- My Account Dropdown -->
      <div class="account-menu">
        <button class="account-btn">My Account &#x25BC;</button>
        <div class="account_dropdown">
          <a href="ad_account.php">Profile</a>
          <a href="ad_logout.php">Logout</a>
        </div>
      </div>
    </header>

    <script>
      // Toggle My Account Dropdown
      const accountBtn = document.querySelector(".account-btn");
      const dropdown = document.querySelector(".account_dropdown");

      accountBtn.addEventListener("click", () => {
        dropdown.style.display =
          dropdown.style.display === "block" ? "none" : "block";
      });

      // Close the dropdown when clicking outside
      document.addEventListener("click", (event) => {
        if (
          !accountBtn.contains(event.target) &&
          !dropdown.contains(event.target)
        ) {
          dropdown.style.display = "none";
        }
      });
    </script>
  </nav>
  <br><br><br><br>
</body>

</html>