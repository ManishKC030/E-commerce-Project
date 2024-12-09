<?php
// Database connection
include("connection.php");

session_start();


// Check if a product_id is provided
if (isset($_GET['product_id'])) {
  $product_id = intval($_GET['product_id']);

  // Fetch the product details
  $sql = "SELECT p.*, c.name AS category_name 
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id
          WHERE p.product_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if the product exists
  if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
  } else {
    echo "<script>
    alert('Product not found!');
        window.location.href = 'index.php';
  </script>";
    exit;
  }
} else {
  echo "<script>
    alert('No product selected!');
    window.location.href = 'index.php';
  </script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($product['name']); ?> - Product Detail</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
  <link rel="stylesheet" href="stylesheet/single-product.css" />
</head>

<body>
  <!-- Include Navigation Bar -->
  <?php include('navbar.php'); ?>

  <div class="main-wrapper">
    <div class="container">
      <div class="product-div">
        <!-- Product Left Section (Images) -->
        <div class="product-div-left">
          <div class="img-container">
            <!-- Display Main Product Image -->
            <img src="uploads/<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
          </div>
          <div class="hover-container">
            <div>
              <img src="uploads/<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
            </div>
            <div>
              <img src="uploads/<?php echo htmlspecialchars($product['image2']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
            </div>
            <div>
              <img src="uploads/<?php echo htmlspecialchars($product['image3']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
            </div>
            <div>
              <img src="uploads/<?php echo htmlspecialchars($product['image4']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
            </div>

          </div>


        </div>

        <!-- Product Right Section (Details) -->
        <div class="product-div-right">
          <h1><?php echo htmlspecialchars($product['brand']); ?></h1>
          <br>
          <span class="product-name"><?php echo htmlspecialchars($product['name']); ?></span>
          <span class="price">Price: $<?php echo number_format($product['price'], 2); ?></span>
          <div class="rating">
            Rating:
            <span><i class="fa-solid fa-star"></i></span>
            <span><i class="fa-solid fa-star"></i></span>
            <span><i class="fa-solid fa-star"></i></span>
            <span><i class="fa-solid fa-star"></i></span>
            <span><i class="fas fa-star-half-alt" style="color: rgb(243, 181, 25);"></i></span>
          </div>
          <br />

          <!-- Size Selector -->
          <label for="sizes">Choose Size:</label>
          <select id="sizes">
            <option>6</option>
            <option>7</option>
            <option>8</option>
            <option>9</option>
            <option>10</option>
          </select>
          <a href="#" class="size-chart-link">View Size Chart</a>

          <!-- Product Description -->
          <p class="desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

          <!-- Action Buttons -->
          <div class="btn-groups">
            <button type="button" class="add-cart">
              <i class="fas fa-shopping-cart"></i> Add to cart
            </button>
            <button type="button" class="buy-now">
              <i class="fas fa-wallet"></i> Buy Now
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    const allHoverImages = document.querySelectorAll(
      ".hover-container div img"
    );
    const imgContainer = document.querySelector(".img-container");

    window.addEventListener("DOMContentLoaded", () => {
      allHoverImages[0].parentElement.classList.add("active");
    });

    allHoverImages.forEach((image) => {
      image.addEventListener("mouseover", () => {
        imgContainer.querySelector("img").src = image.src;
        resetActiveImg();
        image.parentElement.classList.add("active");
      });
    });

    function resetActiveImg() {
      allHoverImages.forEach((img) => {
        img.parentElement.classList.remove("active");
      });
    }
  </script>

  <!-- Include Footer -->
  <?php include('footer.php'); ?>

</body>

</html>