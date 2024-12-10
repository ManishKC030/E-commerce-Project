<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Add product to cart
if (isset($_GET['add_to_cart']) && isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $product_id = intval($_GET['product_id']);
    $quantity = intval($_GET['quantity']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header('Location: cart.php');
    exit;
}

// Remove product from cart
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: cart.php');
    exit;
}

// Increase product quantity
if (isset($_GET['increase'])) {
    $product_id = intval($_GET['increase']);
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1;
    }
    header('Location: cart.php');
    exit;
}

// Decrease product quantity
if (isset($_GET['decrease'])) {
    $product_id = intval($_GET['decrease']);
    if (isset($_SESSION['cart'][$product_id]) && $_SESSION['cart'][$product_id] > 1) {
        $_SESSION['cart'][$product_id] -= 1;
    } elseif (isset($_SESSION['cart'][$product_id]) && $_SESSION['cart'][$product_id] <= 1) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: cart.php');
    exit;
}

// Display cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = 0;
?>

<!DOCTYPE html>
<html>
<!-- here is my comment -->


<!-- here is my comment --><!-- here is my comment --><!-- here is my comment --><!-- here is my comment --><!-- here is my comment --><!-- here is my comment -->
<!-- here is my comment --><!-- here is my comment --><!-- here is my comment --><!-- here is my comment --><!-- here is my comment -->

<head>
    <title>Cart</title>
</head>
<style>
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        text-align: center;
        padding: 12px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: white;
        font-size: 16px;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

<body>
    <?php include 'navbar.php'; ?>
    <h1 style="text-align:center;">Shopping Cart</h1>
    <table border="1">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Actions</th>
        </tr>
        <?php if (!$cart || count($cart) === 0): ?>
            <tr>
                <td colspan="5">Your cart is empty.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($cart as $product_id => $quantity):
                $product_id = intval($product_id);
                $sql = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
                $sql->bind_param('i', $product_id);
                $sql->execute();
                $result = $sql->get_result();

                if ($result && $result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    $subtotal = $product['price'] * $quantity;
                    $total_price += $subtotal;
            ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['price']) ?></td>
                        <td>
                            <form action="cart.php" method="get" style="display:inline;">
                                <button type="submit" name="decrease" value="<?= $product_id ?>">-</button>
                            </form>
                            <?= htmlspecialchars($quantity) ?>
                            <form action="cart.php" method="get" style="display:inline;">
                                <button type="submit" name="increase" value="<?= $product_id ?>">+</button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($subtotal) ?></td>
                        <td>
                            <a href="cart.php?remove=<?= $product_id ?>">Remove</a>
                        </td>
                    </tr>
            <?php }
            endforeach; ?>
        <?php endif; ?>
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td colspan="2"><strong>$<?= number_format($total_price, 2) ?></strong></td>
        </tr>
    </table>
    <a href="checkout.php">Proceed to Checkout</a>
    <?php include 'footer.php'; ?>
</body>

</html>