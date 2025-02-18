<?php
include("connection.php");
session_start();

require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('API Key'); // Replace with your Stripe secret key

$user_id = $_SESSION['user_id'];

// Default to empty cart items
$cart_items = [];
$total_amount = 0;

// Check if accessed via "Buy Now" or cart
if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $product_id = intval($_GET['product_id']);
    $quantity = intval($_GET['quantity']);

    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        $total_price = $product['price'] * $quantity;
        $cart_items[] = [
            'id' => $product['product_id'],
            'name' => $product['name'],
            'image1' => $product['image1'],
            'quantity' => $quantity,
            'price' => $product['price'],
            'total_price' => $total_price
        ];
        $total_amount = $total_price;
    }

    $stmt->close();
} else {
    // Fetch cart items
    $sql = "SELECT cart.id, products.name, products.image1, cart.quantity, products.price, (cart.quantity * products.price) AS total_price 
            FROM cart
            INNER JOIN products ON cart.product_id = products.product_id
            WHERE cart.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_amount += $row['total_price'];
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .billing-section, .order-summary {
            flex: 1;
            min-width: 300px;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 10px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fafafa;
            font-size: 14px;
        }

        .btn {
            display: block;
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <!-- Billing Address Section -->
        <div class="billing-section">
            <h2>Billing Address</h2>
            <form action="process_checkout.php" method="POST" id="payment-form">
                <div class="input-group">
                    <label for="billing_name">Full Name</label>
                    <input type="text" name="billing_name" id="billing_name" required>
                </div>
                <div class="input-group">
                    <label for="billing_phone">Phone</label>
                    <input type="text" name="billing_phone" id="billing_phone" required>
                </div>
                <div class="input-group">
                    <label for="billing_email">Email</label>
                    <input type="email" name="billing_email" id="billing_email" required>
                </div>
                <div class="input-group">
                    <label for="billing_address">Address</label>
                    <input type="text" name="billing_address" id="billing_address" required>
                </div>
                <div class="input-group">
                    <label for="billing_zip">ZIP Code</label>
                    <input type="text" name="billing_zip" id="billing_zip" required>
                </div>

                <h2>Payment Method</h2>
                <label>
                    <input type="radio" name="payment_method" value="cash_on_delivery" required> Cash on Delivery
                </label>
                <label>
                    <input type="radio" name="payment_method" value="stripe" required> Stripe
                </label>

                <button type="submit" id="submit-button" class="btn">Confirm Order</button>
            </form>
        </div>

        <!-- Order Summary Section -->
        <div class="order-summary">
            <h2>Order Summary</h2>

            <?php if (count($cart_items) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><img src="uploads/<?php echo htmlspecialchars($item['image1']); ?>" class="product-image"></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>$<?php echo number_format($item['total_price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="total">Total</td>
                            <td class="total">$<?php echo number_format($total_amount, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>
