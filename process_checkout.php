<?php
require 'vendor/autoload.php';
include("connection.php");
session_start();

// Set Stripe API key
\Stripe\Stripe::setApiKey('your_secret_key'); // Replace with your Stripe secret key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];

    // Fetch cart items and calculate the total
    $sql = "SELECT products.name, cart.quantity, products.price 
            FROM cart 
            INNER JOIN products ON cart.product_id = products.product_id 
            WHERE cart.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $line_items = [];
    $total_amount = 0;

    while ($row = $result->fetch_assoc()) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $row['name'],
                ],
                'unit_amount' => $row['price'] * 100, // Convert to cents
            ],
            'quantity' => $row['quantity'],
        ];
        $total_amount += $row['price'] * $row['quantity'];
    }
    $stmt->close();

    if ($payment_method === 'stripe') {
        // Create Stripe Checkout Session
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://yourdomain.com/success.php', // Replace with your success URL
            'cancel_url' => 'http://yourdomain.com/cancel.php',  // Replace with your cancel URL
        ]);

        // Redirect to Stripe Checkout
        header('Location: ' . $checkout_session->url);
        exit();
    } elseif ($payment_method === 'cash_on_delivery') {
        // Handle cash on delivery logic
        // For example, redirect to a confirmation page
        header('Location: cash_on_delivery.php');
        exit();
    } else {
        echo "Invalid payment method selected.";
    }
}
?>
