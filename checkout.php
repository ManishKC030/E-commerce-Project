<?php
session_start();

include 'connection.php';

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = 1; // Replace with actual logged-in user ID
    $total_price = $_POST['total_price'];
    $payment_method = $_POST['payment_method'];

    // Create order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("id", $user_id, $total_price);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Add items to order_items
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT price FROM products WHERE product_id = $product_id";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        $price = $product['price'];

        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmt->execute();
    }

    // Clear the cart
    unset($_SESSION['cart']);

    echo "Order placed successfully! Your order ID is: " . $order_id;
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Checkout</title>
</head>

<body>
    <h1>Checkout</h1>
    <form method="post">
        <input type="hidden" name="total_price" value="<?= $_SESSION['total_price'] 
        ?>
        ">
        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method">
            <option value="cash_on_delivery">Cash on Delivery</option>
            <option value="esewa">eSewa</option>
            <option value="khalti">Khalti</option>
            <option value="card">Card</option>
        </select>
        <button type="submit">Place Order</button>
    </form>
</body>

</html>