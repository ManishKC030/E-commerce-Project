<?php
require 'vendor/autoload.php';
include("connection.php");
session_start();

// Set Stripe API key
\Stripe\Stripe::setApiKey('sk_test_51Qk34BKyP8b492kmIYdPErjH5KlfzX51Q1ug2iebrvoVzSnV18tWp2pFpZTjmHy0nYuevNmwvaGWxR7gKpKMj53D00epvNPJMs'); // Replace with your Stripe secret key
header('Content-Type: application/json');



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

            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://localhost/project_final/success.php?session_id={CHECKOUT_SESSION_ID}', // Use full URL
            'cancel_url' => 'http://localhost/project_final/cancel.html',  // Use full URL
        ]);

        // Redirect to Stripe Checkout
        header('Location: ' . $checkout_session->url);
        exit();
    } elseif ($payment_method === 'cash_on_delivery') {
        // Handle cash on delivery logic
        // For example, redirect to a confirmation page
        header('Location: COD.php');
        exit();
    } else {
        echo "Invalid payment method selected.";
    }
}
