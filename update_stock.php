<?php
include("connection.php");
// update_stock.php
function updateStock($conn, $user_id)
{
    // Fetch cart details for the user before proceeding with the checkout process
    $sql = "SELECT cart.product_id, cart.quantity, products.stock 
            FROM cart 
            INNER JOIN products ON cart.product_id = products.product_id 
            WHERE cart.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $current_stock = $row['stock'];

        // Verify sufficient stock
        if ($current_stock >= $quantity) {
            // Deduct stock
            $update_stock = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
            $update_stmt = $conn->prepare($update_stock);
            $update_stmt->bind_param("ii", $quantity, $product_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            return "Error: Not enough stock for Product ID: " . $product_id;
        }
    }

    $stmt->close();
    return "Stock updated successfully.";
}
