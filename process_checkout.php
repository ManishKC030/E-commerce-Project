<?php
include("connection.php");
require 'vendor/autoload.php'; // Include Composer autoloader

\Stripe\Stripe::setApiKey('your_secret_key'); // Replace with your Stripe secret key

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $payment_method = $_POST['payment_method'];

    $conn->begin_transaction();

    try {
        // Insert into orders table
        $sql_order = "INSERT INTO orders (user_id, total_price, status) VALUES (?, 0, 'pending')";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("i", $user_id);
        $stmt_order->execute();
        $order_id = $stmt_order->insert_id; // Get the inserted order ID
        $stmt_order->close();

        // Fetch cart items
        $sql_cart = "SELECT cart.id, products.product_id, cart.quantity, products.price 
                     FROM cart
                     INNER JOIN products ON cart.product_id = products.product_id
                     WHERE cart.user_id = ?";
        $stmt_cart = $conn->prepare($sql_cart);
        $stmt_cart->bind_param("i", $user_id);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();

        $total_price = 0;

        // Insert into order_items
        $sql_order_items = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_order_items = $conn->prepare($sql_order_items);

        while ($row = $result_cart->fetch_assoc()) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
            $price = $row['price'];
            $total_price += $quantity * $price;

            $stmt_order_items->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt_order_items->execute();
        }

        $stmt_order_items->close();
        $stmt_cart->close();

        // Handle Stripe payment if selected
        if ($payment_method === 'stripe') {
            $payment_intent_id = $_POST['payment_intent_id']; // Get Payment Intent ID from the frontend

            try {
                // Verify the payment intent with Stripe
                $paymentIntent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

                if ($paymentIntent->status !== 'succeeded') {
                    throw new Exception('Stripe payment not completed.');
                }

                $status = 'completed'; // Update status if payment is successful
            } catch (Exception $e) {
                $conn->rollback();
                echo "Error: " . $e->getMessage();
                exit;
            }
        } else {
            $status = 'cod'; // Cash on delivery
        }

        // Update the total price in the orders table
        $sql_update_order = "UPDATE orders SET total_price = ?, status = ? WHERE order_id = ?";
        $stmt_update_order = $conn->prepare($sql_update_order);
        $stmt_update_order->bind_param("dsi", $total_price, $status, $order_id);
        $stmt_update_order->execute();
        $stmt_update_order->close();

        // Insert into payment table
        $sql_payment = "INSERT INTO payment (order_id, payment_method, status) VALUES (?, ?, ?)";
        $stmt_payment = $conn->prepare($sql_payment);
        $stmt_payment->bind_param("iss", $order_id, $payment_method, $status);
        $stmt_payment->execute();
        $stmt_payment->close();

        // Clear the cart
        $sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
        $stmt_clear_cart = $conn->prepare($sql_clear_cart);
        $stmt_clear_cart->bind_param("i", $user_id);
        $stmt_clear_cart->execute();
        $stmt_clear_cart->close();

        // Commit the transaction
        $conn->commit();

        echo "<h1>Order Successful!</h1>";
        echo "<p>Your order has been placed successfully. Your Order ID is: <strong>$order_id</strong></p>";
        echo "<a href='index.php'>Go Back</a>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>
