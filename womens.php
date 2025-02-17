<?php
require 'connection.php';

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids: Buy new sheos for your kids</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <link rel="stylesheet" href="stylesheet/nav-footer.css">
    <link rel="stylesheet" href="stylesheet/mens.css">
</head>

<body>
    <!-- Navigation Bar -->
    <?php
    include 'navbar.php';
    ?>

    <section id="page-header">





    </section>



    <div class="new-arrivals">
        <h2>Womens' New Arrivals</h2>
        <div class="product-container">

            <?php
            // Fetch Mens' New Arrival products
            $query = "
            SELECT * FROM products 
            WHERE category_id = (
                SELECT id FROM categories WHERE name = 'WOMENS New Arrival'
            )
            ORDER BY created_at DESC LIMIT 10
        ";

            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {


                    echo '
                <div class="product"><a href="product_detail.php?product_id=' . htmlspecialchars($product['product_id']) . '"  class = "proDetail">
                <img src="uploads/' . htmlspecialchars($product['image1']) . '" alt="' . htmlspecialchars($product['name']) . '">
                <div class="product-info">
                <p class="product-brand">' . htmlspecialchars($product['brand']) . '</p>
                <p class="product-name">' . htmlspecialchars($product['name']) . '</p>
                <p class="product-description">' . htmlspecialchars($product['short_desc']) . '</p>
                <p class="product-price">Rs ' . number_format($product['price'], 2) . '</p>
                <p>Stock: ' . htmlspecialchars($product['stock']) . '</p>
                </div>
                </a>
                
                </div>
                ';
                }
            } else {
                echo "<p style= ' margin-left:350px; font-size: 25px; font-weight:bold; font-family: Sofia, sans-serif;'>No Products Found in Womens New Arrivals</p>";
            }

            ?>

        </div>

        <!-- Navigation buttons -->
        <button class="scroll-btn left">&lt;</button>
        <button class="scroll-btn right">&gt;</button>
    </div>


    <!-- JavaScript to enable scrolling functionality -->
    <script>
        const scroll_Container = document.querySelector('.product-container');
        const scroll_LeftBtn = document.querySelector('.scroll-btn.left');
        const scroll_RightBtn = document.querySelector('.scroll-btn.right');

        scroll_LeftBtn.addEventListener('click', () => {
            scroll_Container.scrollBy({
                left: -300,
                behavior: 'smooth'
            });
        });

        scroll_RightBtn.addEventListener('click', () => {
            scroll_Container.scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        });
    </script>

    <hr>



    <div class="best-selling">
        <h2>Womens' best Arrivals</h2>
        <div class="product-container2">

            <?php
            // Fetch Womens' Best Selling products
            $query = "
    SELECT * FROM products
    WHERE category_id = (
    SELECT id FROM categories WHERE name = 'WOMENS Best Selling'
    )
    ORDER BY created_at DESC LIMIT 8
    ";
            $result = $conn->query($query);
            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {


                    echo '    
            <div class="product2">
            <a href="product_detail.php?product_id=' . htmlspecialchars($product['product_id']) . '" class = "pro2Detail">
                <img class="best_img" src="uploads/' . htmlspecialchars($product['image1']) . '" alt="' . htmlspecialchars($product['name']) . '">
                <div class="product2-info">
                <p class="product-brand">' . htmlspecialchars($product['brand']) . '</p>
                    <p class="product2-name">' . htmlspecialchars($product['name']) . '</p>
                    <p class="product2-type">' . htmlspecialchars($product['type']) . '</p>
                    <p class="product2-description">' . htmlspecialchars($product['short_desc']) . '</p>
                    <p class="product2-price">$ ' . number_format($product['price'], 2) . '</p>
                    <p>Stock: ' . htmlspecialchars($product['stock']) . '</p>
                </div></a>
            <a href="cart.php?product_id=' . htmlspecialchars($product['product_id']) . '" class="cart-button">
                     <i class="fa-solid fa-cart-shopping"></i>
                     </a></div>';
                }
            } else {
                echo "<p style= 'margin-left:350px; font-size: 25px; font-weight:bold; font-family: Sofia, sans-serif;'>No Products Found in Womens Best Selling</p>";
            }
            ?>
        </div>
    </div>


    <br>

    <hr>



    <br>



    </section>
    <br><br>
    <section id="product">
        <h2>Featured Products</h2>
        <p>Womens Collection</p>
        <div class="pro-container">
            <?php
            // Fetch Womens' products
            $query = "
    SELECT * FROM products
    WHERE category_id = (
    SELECT id FROM categories WHERE name = 'WOMENS'
    )
    ORDER BY created_at DESC 
    ";
            $result = $conn->query($query);
            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {

                    echo '
                <div class="pro">
                <a href="product_detail.php?product_id=' . htmlspecialchars($product['product_id']) . '" class = "proDetail">
                <img src="uploads/' . htmlspecialchars($product['image1']) . '" alt="' . htmlspecialchars($product['name']) . '">
                <div class="des">
                    <span>' . htmlspecialchars($product['brand']) . '</span>
                    <h5>' . htmlspecialchars($product['name']) . '</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$  ' . number_format($product['price'], 2) . '</h4>
                </div>

                <a href="cart.php?add_to_cart=1&product_id=' . htmlspecialchars($product['product_id']) . '&quantity=1">
                    <div class="cart"><i class="fa fa-shopping-cart "></i></div>
                </a>

            </div>

        </div>
        </a>';
                }
            } else {
                echo "<p style= 'margin-left:350px; font-size: 25px; font-weight:bold; font-family: Sofia, sans-serif;'>No Products Found in Womens Section</p>";
            }
            ?>


    </section>


    <!-- Footer Section -->
    <?php
    include 'footer.php';
    ?>

</body>
</body>

</html>