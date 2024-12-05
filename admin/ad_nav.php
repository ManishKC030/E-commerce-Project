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
        <a href="#dashboard">Dashboard</a>
        <a href="#orders">Orders</a>
        <a href="#">Products</a>
        <a href="#">Notifications</a>
        <a href="#settings">Settings</a>
      </nav>

      <!-- My Account Dropdown -->
      <div class="account-menu">
        <button class="account-btn">My Account &#x25BC;</button>
        <div class="dropdown">
          <a href="ad_account.php">Profile</a>
          <a href="ad_logout.php">Logout</a>
        </div>
      </div>
    </header>

    <script>
      // Toggle My Account Dropdown
      const accountBtn = document.querySelector(".account-btn");
      const dropdown = document.querySelector(".dropdown");

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
</body>

</html>