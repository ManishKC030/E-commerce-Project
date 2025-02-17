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
    $user_id = $_SESSION['user_id'];

    // Check if the product is already in the cart
    $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if product already in cart
        $sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Insert new product into cart
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $user_id, $product_id, $quantity);
        $stmt->execute();
    }

    header('Location: cart.php');
    exit;
}

// Remove product from cart
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    $user_id = $_SESSION['user_id'];

    // Delete product from cart
    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();

    header('Location: cart.php');
    exit;
}

// Increase or Decrease product quantity
if (isset($_GET['increase']) || isset($_GET['decrease'])) {
    $product_id = isset($_GET['increase']) ? intval($_GET['increase']) : intval($_GET['decrease']);
    $user_id = $_SESSION['user_id'];

    // Fetch current quantity
    $sql = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + (isset($_GET['increase']) ? 1 : -1);

        // Ensure quantity does not go below 1
        if ($new_quantity < 1) {
            $new_quantity = 1;
        }

        // Update quantity in database
        $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $new_quantity, $user_id, $product_id);
        $stmt->execute();
    }

    header('Location: cart.php');
    exit;
}

// Remove all products from cart
if (isset($_GET['remove_all'])) {
    $user_id = $_SESSION['user_id'];

    // Delete all products for the user
    $sql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    header('Location: cart.php');
    exit;
}

// Display cart
$user_id = $_SESSION['user_id'];
$sql = "SELECT cart.product_id, cart.quantity, products.name, products.price, products.image1 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Shopping Cart</title>
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
        background-color: rgb(250, 250, 250);
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .product-image {
        width: 100px;
        height: 100px;
        overflow: hidden;
        display: inline-block;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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

    .cart-actions {
        text-align: center;
        margin-top: 20px;
    }

    .remove-all-btn {
        background-color: #dc3545;
        color: white;
        padding: 10px 20px;
        border: none;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        transition: background 0.3s ease;
    }

    .remove-all-btn:hover {
        background-color: #c82333;
    }

    .checkout-btn {
        background-color: #28a745;
        color: white;
        padding: 12px 24px;
        font-size: 18px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-left: 10px;
        transition: background 0.3s ease;
    }

    .checkout-btn:hover {
        background-color: #218838;
    }
</style>

<body>
    <?php include 'navbar.php'; ?>
    <h1 style="text-align:center;">Shopping Cart</h1>
    <table border="1">
        <tr>
            <th>Product Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Actions</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()):
                $subtotal = $product['price'] * $product['quantity'];
                $total_price += $subtotal;
            ?>
                <tr>
                    <td>
                        <div class="product-image">
                            <img src="uploads/<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        </div>
                    </td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td>
                        <form action="" method="get" style="display:inline;">
                            <button type="submit" name="decrease" value="<?= $product['product_id'] ?>">-</button>
                        </form>
                        <?= htmlspecialchars($product['quantity']) ?>
                        <form action="" method="get" style="display:inline;">
                            <button type="submit" name="increase" value="<?= $product['product_id'] ?>">+</button>
                        </form>
                    </td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <a href="cart.php?remove=<?= $product['product_id'] ?>">Remove</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Your cart is empty.</td>
            </tr>
        <?php endif; ?>

        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td colspan="3"><strong>$<?= number_format($total_price, 2) ?></strong></td>
        </tr>
    </table>
    <div class="cart-actions">
        <form action="cart.php" method="get" style="display:inline;">
            <button type="submit" name="remove_all" class="remove-all-btn">Remove All Products</button>
        </form>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br>
    <?php include 'footer.php'; ?>
</body>

</html>