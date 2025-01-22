<?php
include("connection.php");
session_start();

require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('your_secret_key'); // Replace with your Stripe secret key

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.id, products.name, products.image1, cart.quantity, products.price, (cart.quantity * products.price) AS total_price 
        FROM cart
        INNER JOIN products ON cart.product_id = products.product_id
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_amount = 0;
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_amount += $row['total_price'];
}

$stmt->close();
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
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        .checkout-form label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }

        #card-element {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
            background: #fafafa;
        }

        .btn {
            display: block;
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px;
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

        .hidden {
            display: none;
        }

        .error-message {
            color: #e74c3c;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>Checkout</h1>

        <?php if (count($cart_items) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><img src="uploads/<?php echo htmlspecialchars($item['image1']); ?>" class="product-image"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['total_price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="total">Total Amount</td>
                        <td class="total">$<?php echo number_format($total_amount, 2); ?></td>
                    </tr>
                </tfoot>
            </table>

            <form action="process_checkout.php" method="POST" class="checkout-form" id="payment-form">
                <h2>Payment Method</h2>
                <label>
                    <input type="radio" name="payment_method" value="cash_on_delivery" required>
                    Cash on Delivery
                </label>
                <label>
                    <input type="radio" name="payment_method" value="stripe" required>
                    Stripe
                </label>
                <div id="card-element" class="hidden"></div>
                <div id="card-errors" class="error-message"></div>
                <button type="submit" id="submit-button" class="btn">Confirm Order</button>
            </form>

            <script>
                const stripe = Stripe('your_publishable_key'); // Replace with your Stripe publishable key
                const elements = stripe.elements();
                const card = elements.create('card', {
                    style: {
                        base: {
                            color: '#32325d',
                            fontFamily: 'Arial, sans-serif',
                            fontSize: '16px',
                            '::placeholder': {
                                color: '#888',
                            },
                        },
                    },
                });

                const paymentForm = document.getElementById('payment-form');
                const cardElement = document.getElementById('card-element');
                const cardErrors = document.getElementById('card-errors');

                paymentForm.addEventListener('change', (e) => {
                    if (e.target.name === 'payment_method' && e.target.value === 'stripe') {
                        cardElement.classList.remove('hidden');
                        card.mount('#card-element');
                    } else {
                        cardElement.classList.add('hidden');
                        card.unmount();
                    }
                });

                paymentForm.addEventListener('submit', async (e) => {
                    if (document.querySelector('input[name="payment_method"]:checked').value === 'stripe') {
                        e.preventDefault();
                        const { error, paymentMethod } = await stripe.createPaymentMethod({
                            type: 'card',
                            card: card,
                        });

                        if (error) {
                            cardErrors.textContent = error.message;
                        } else {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'stripe_payment_method_id';
                            input.value = paymentMethod.id;
                            paymentForm.appendChild(input);
                            paymentForm.submit();
                        }
                    }
                });
            </script>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>
