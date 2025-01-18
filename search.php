 <?php
    // PHP Code Starts Here
    include 'connection.php';

    ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Search Result</title>
     <style>
         .search {
             width: 90%;

             margin: 20px auto;

             text-align: center;
         }

         .search h2 {
             font-size: 24px;
             margin-bottom: 20px;
             color: #333;
             text-align: center;
         }

         .product-container2 {
             display: grid;
             grid-template-columns: repeat(2, 1fr);
             /* Make products stack vertically */
             gap: 20px;
             /* Add space between each product */
         }

         .product2 {
             display: flex;
             flex-direction: row;
             flex-wrap: nowrap;
             background-color: #fff;
             border: 1px solid #e0e0e0;
             border-radius: 8px;
             overflow: hidden;
             box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
             transition: transform 0.3s ease;
             margin-bottom: 15px;
         }

         .product2:hover {
             transform: translateY(-5px);
         }

         .search_img {
             width: 250px;
             height: 30vh;
             object-fit: cover;
             flex-shrink: 0;
         }

         .product2-info {
             padding: 15px;
             flex: 1;
             text-align: left;

             flex-direction: column;
             justify-content: center;
             /* Center align the text vertically */
         }

         .product2-name {
             font-size: 18px;
             font-weight: bold;
             color: #333;
             margin-bottom: 5px;
         }

         .product2-description {
             font-size: 14px;
             color: #777;
             margin-top: 5px;
             margin-bottom: 10px;
         }

         .product2-price {
             font-size: 16px;
             font-weight: bold;
             color: #000;
             margin-bottom: 5px;
         }

         .product2-type {
             font-size: 14px;
             color: #777;
         }

         .pro2Detail {
             display: flex;
             text-decoration: none;
             /* Removes link underline */
             color: inherit;
             /* Inherits text color */
         }

         /* Responsive Design */
         @media screen and (max-width: 768px) {
             .product-container2 {
                 grid-template-columns: 1fr;
                 /* Switch to one product per row on small screens */
             }

             .product2 {
                 flex-direction: column;
                 /* Stack items vertically on smaller screens */
                 align-items: center;
                 /* Center align the items */
                 text-align: center;
                 /* Center align text */
             }

             .best_img {
                 width: 100%;
                 /* Make image take full width */
                 height: auto;
                 /* Maintain aspect ratio */
             }

             .product2-info {
                 padding: 10px;
             }
         }
     </style>
 </head>

 <body>
     <?php
        include 'navbar.php';
        ?>

     <?php
        $query = isset($_GET['query']) ? trim($_GET['query']) : '';

        if (!empty($query)) {
            $sql = "SELECT p.*, c.name AS category_name
                          FROM products p
                          JOIN categories c ON p.category_id = c.id
                          WHERE p.name LIKE ? OR p.type LIKE ?";
            echo '<div class="search">
    <h2>Search Result for ' . htmlspecialchars($query) . ' </h2>
    <div class="product-container2">';
            $stmt = $conn->prepare($sql);
            $searchTerm = "%" . $query . "%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {


                    echo '    
            <div class="product2">
            <a href="product_detail.php?product_id=' . htmlspecialchars($product['product_id']) . '" class = "pro2Detail">
                <img class="search_img" src="uploads/' . htmlspecialchars($product['image1']) . '" alt="' . htmlspecialchars($product['name']) . '">
                <div class="product2-info">
                <p class="product-brand">' . htmlspecialchars($product['brand']) . '</p>
                    <p class="product2-name">' . htmlspecialchars($product['name']) . '</p>
                    <p class="product2-type">' . htmlspecialchars($product['type']) . '</p>
                    <p class="product2-description">' . htmlspecialchars($product['short_desc']) . '</p>
                    <p class="product2-price">$ ' . number_format($product['price'], 2) . '</p>
                    <p>Stock: ' . htmlspecialchars($product['stock']) . '</p>
                </div>
            </div></a>';
                }
            } else {
                echo "<p class='no-results'>No results found for '" . htmlspecialchars($query) . "'.</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='no-results'>Please enter a search term.</p>";
        }

        ?>
     </div>
     </div>


 </body>

 </html>