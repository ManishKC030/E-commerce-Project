<!-- <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Detail</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    />
    <link rel="stylesheet" href="stylesheet/single-product.css" />
  </head>
  <body>
   Navigation Bar -->
    <!-- <?php
  // require 'navbar.php';
  ?>



    <div class="main-wrapper">
      <div class="container">
        <div class="product-div">
          <div class="product-div-left">
            <div class="img-container">
              <img src="assets/collabs.jpg" alt="shoes" />
            </div>
            <div class="hover-container">
              <div>
                <img src="assets/best-selling.jpg" alt="1" />
              </div>
              <div>
                <img src="assets/classics.jpg" alt="2" />
              </div>
              <div>
                <img src="assets/collabs.jpg" alt="3" />
              </div>
              <div>
                <img src="assets/classics.jpg" alt="4" />
              </div>
            </div>
          </div>

          <div class="product-div-right">
            <span class="product-name">Shoes NAme</span>
            <span class="price">Price: $$...</span>
            <div class="rating">
              Rating: &nbsp;
              <span><i class="fa-solid fa-star"></i></span>
              <span><i class="fa-solid fa-star"></i></span>
              <span><i class="fa-solid fa-star"></i></span>
              <span><i class="fa-solid fa-star"></i></span>
              <span
                ><i
                  style="color: rgb(243, 181, 25)"
                  class="fas fa-star-half-alt"
                ></i
              ></span>
            </div>
            <br />
            <label for="sizes">Choose Size:</label>
            <select id="sizes">
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
            </select>
            &nbsp;
            <a href="#" class="size-chart-link">View Size Chart</a>
            <p class="desc">
              Lorem ipsum, dolor sit amet consectetur adipisicing elit.
              Deserunt, recusandae fugiat ad iste repellat molestias perferendis
              magni numquam quas! Harum rerum deleniti fugiat suscipit ex at
              pariatur nostrum libero quos?Lorem ipsum dolor sit amet
              consectetur adipisicing elit. Veniam dignissimos, impedit tempora
              eius porro ullam harum accusamus in doloremque commodi nemo, qui
              totam iure quae accusantium molestias iste! Debitis, vel.
            </p>
            <div class="btn-groups">
              <button type="button" class="add-cart">
                <i class="fas fa-shopping-cart"></i>Add to cart
              </button>
              <button type="button" class="buy-now">
                <i class="fas fa-wallet"></i>buy Now
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

    Related Products
    <div class="related-products">
  <h2>You May Also Like</h2>
  <div class="related-product-item">
    <img src="assets/blazer.jpg" alt="Related Product 1">
    <p>Running Shoes</p>
    <p class="price">₹2,000</p>
  </div>
  <div class="related-product-item">
    <img src="assets/oldskool.jpg" alt="Related Product 2">
    <p>Casual Sneakers</p>
    <p class="price">₹1,800</p>
  </div>
  <div class="related-product-item">
    <img src="assets/Kids/Campus.jpg" alt="Related Product 3">
    <p>Sporty Trainers</p>
    <p class="price">₹2,200</p>
  </div>
</div> -->

<!-- <?php
  // require 'footer.php';
  ?>



  </body>
</html> 
-->




<?php
// Database connection
include("connection.php");

// Fetch product ID from URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    // Fetch product details from the database
    $query = "SELECT p.name, p.description, p.price, p.stock, c.name AS category_name, 
              p.image1, p.image2, p.image3, p.image4 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Invalid product ID!";
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
    <!-- Navigation Bar -->
   <?php 
   include('navbar.php');
   ?>
    <div class="main-wrapper">
        <div class="container">
            <div class="product-div">
                <div class="product-div-left">
                    <div class="img-container">
                        <img src="<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                    </div>
                    <div class="hover-container">
                        <?php for ($i = 1; $i <= 4; $i++): 
                            $image = $product["image$i"];
                            if (!empty($image)): ?>
                            <div>
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Image <?php echo $i; ?>" />
                            </div>
                        <?php endif; endfor; ?>
                    </div>
                </div>

                <div class="product-div-right">
                    <span class="product-name"><?php echo htmlspecialchars($product['name']); ?></span>
                    <span class="price">Price: $<?php echo number_format($product['price'], 2); ?></span>
                    <div class="rating">
                        Rating: 
                        <span><i class="fa-solid fa-star"></i></span>
                        <span><i class="fa-solid fa-star"></i></span>
                        <span><i class="fa-solid fa-star"></i></span>
                        <span><i class="fa-solid fa-star"></i></span>
                        <span><i class="fas fa-star-half-alt"></i></span>
                    </div>
                    <br />
                    <label for="sizes">Choose Size:</label>
                    <select id="sizes">
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                    </select>
                    <a href="#" class="size-chart-link">View Size Chart</a>
                    <p class="desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
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

    <!-- Footer -->
      
   <?php 
   include('footer.php');
   ?>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
