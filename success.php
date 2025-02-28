<?php
require 'vendor/autoload.php';
include("connection.php");
session_start();

// Set Stripe API key
\Stripe\Stripe::setApiKey('API Key'); // Replace with your Stripe secret key

// Retrieve the session ID from the URL
$session_id = $_GET['session_id'];

// Fetch the session data from Stripe
$session = \Stripe\Checkout\Session::retrieve($session_id);

// Check if the payment was successful
if ($session->payment_status === 'paid') {
    // Payment was successful

    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    $billing_name = $_SESSION['billing_name'];
    $billing_phone = $_SESSION['billing_phone'];
    $billing_email = $_SESSION['billing_email'];
    $billing_country = $_SESSION['billing_country'];
    $billing_city = $_SESSION['billing_city'];
    $billing_state = $_SESSION['billing_state'];
    $billing_zip = $_SESSION['billing_zip'];


    // Initialize the total order amount
    $total_order_amount = 0;

    // Fetch cart items for the user
    $sql = "SELECT cart.product_id, cart.quantity, products.price, products.admin_id
            FROM cart 
            INNER JOIN products ON cart.product_id = products.product_id 
            WHERE cart.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Insert each product as a row into the `orders` table
    $sql_insert_order = "INSERT INTO orders (user_id, total_price, status, product_id, quantity, price, created_at, admin_id, 
                          billing_name, billing_phone, billing_email, billing_country, billing_city, billing_state, billing_zip)
                         VALUES (?, ?, 'confirmed', ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_order = $conn->prepare($sql_insert_order);

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $admin_id = $row['admin_id'];

        $total_price = $price * $quantity;
        $total_order_amount += $total_price; // Accumulate total order amount

        $stmt_insert_order->bind_param(
            "idiiiisssssss",
            $user_id,
            $total_price,
            $product_id,
            $quantity,
            $price,
            $admin_id,
            $billing_name,
            $billing_phone,
            $billing_email,
            $billing_country,
            $billing_city,
            $billing_state,
            $billing_zip
        );
        $stmt_insert_order->execute();
    }

    $stmt->close();
    $stmt_insert_order->close();

    // Insert into payment table for Stripe payment
    $sql_payment = "INSERT INTO payment (order_id, payment_method, status, created_at)
                    VALUES (?, 'stripe', 'completed', NOW())";

    // Use the last inserted order_id for payment
    $order_id = $conn->insert_id;

    $stmt_payment = $conn->prepare($sql_payment);
    $stmt_payment->bind_param("i", $order_id);
    $stmt_payment->execute();
    $stmt_payment->close();

    // Clear the cart after successful order placement
    $sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
    $stmt_clear_cart = $conn->prepare($sql_clear_cart);
    $stmt_clear_cart->bind_param("i", $user_id);
    $stmt_clear_cart->execute();
    $stmt_clear_cart->close();

    echo "<script>
    alert('Your order has been placed successfully!');
    window.location.href = 'order_status.php';
  </script>";
} else {
    // Payment failed
    echo "Payment failed. Please try again.";
}
