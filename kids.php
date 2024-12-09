<?php
require 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids: Buy new sheos for your kids</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="stylesheet" href="stylesheet/nav-footer.css">
    <link rel="stylesheet" href="stylesheet/kids.css">
</head>

<body>
    <?php
    require 'navbar.php';
    ?>



    <section id="page-header">



    </section>



    <div class="new-arrivals">
        <h2>Kids' New Arrivals</h2>
        <div class="product-container">

            <?php
            // Fetch Kids' New Arrival products
            $query = "
            SELECT * FROM products 
            WHERE category_id = (
                SELECT id FROM categories WHERE name = 'KIDS New Arrival'
            )
            ORDER BY created_at DESC LIMIT 10
        ";

            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
                    echo '
                <div class="product">
                    <img src="uploads/' . htmlspecialchars($product['image1']) . '" alt="' . htmlspecialchars($product['name']) . '">
                    <div class="product-info">
                    <p class="product-brand">' . htmlspecialchars($product['brand']) . '</h3>
                    <p class="product-name">' . htmlspecialchars($product['name']) . '</h3>
                    <p class="product-description">' . htmlspecialchars($product['short_desc']) . '</p>
                    <p class="product-price">Rs ' . number_format($product['price'], 2) . '</p>
                    <p>Stock: ' . htmlspecialchars($product['stock']) . '</p>
                    </div>
                </div>';
                }
            } else {
                echo "<p style= ' margin-left:350px; font-size: 25px; font-weight:bold;  font-family: Sofia, sans-serif;'>No Products Found in Kids New Arrivals</p>";
            }
            ?>
        </div>

        <!-- Navigation buttons -->
        <button class="scroll-btn left">&lt;</button>
        <button class="scroll-btn right">&gt;</button>
    </div>


    <!-- JavaScript to enable scrolling functionality -->
    <script>
        const scrollContainer = document.querySelector('.product-container');
        const scrollLeftBtn = document.querySelector('.scroll-btn.left');
        const scrollRightBtn = document.querySelector('.scroll-btn.right');

        scrollLeftBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({
                left: -300,
                behavior: 'smooth'
            });
        });

        scrollRightBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        });
    </script>

    <hr>
    <br><br>
    <section id="product">
        <h2>Featured Products</h2>
        <p>Summer Collection</p>
        <div class="pro-container">
            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>
            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>

            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>

            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>

            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>


            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>


            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>


            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>


            <div class="pro">
                <img src="assets/Kids/vans Knu Skool Disney Scar.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Vans Shoes</h5>
                    <div class="star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <div class="cart"> <a href="#"><i class="fa fa-shopping-cart "></i></a></div>

            </div>



        </div>


    </section>


    <!-- Footer Section -->
    <?php
    require 'footer.php';
    ?>
</body>
</body>

</html>