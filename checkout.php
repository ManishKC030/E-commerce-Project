<?php
include("connection.php");
session_start();

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
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .total {
            font-weight: bold;
        }

        .checkout-form {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <?php
    include('navbar.php');
    ?>

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
                        <td><img src="uploads/<?php echo htmlspecialchars($item['image1']); ?>" class="product-image"> </td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>$ <?php echo number_format($item['price'], 2); ?></td>
                        <td>$ <?php echo number_format($item['total_price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total">Total Amount</td>
                    <td class="total">$ <?php echo number_format($total_amount, 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <form action="process_checkout.php" method="POST" class="checkout-form">
            <h2>Payment Method</h2>
            <label>
                <input type="radio" name="payment_method" value="cash_on_delivery" required> Cash on Delivery
            </label><br>
            <label>
                <input type="radio" name="payment_method" value="esewa" required> eSewa
            </label><br>
            <label>
                <input type="radio" name="payment_method" value="khalti" required> Khalti
            </label><br>


            <button type="submit">Confirm Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

</body>

</html>

<?php
$conn->close();
?>