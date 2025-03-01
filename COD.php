<?php
include("connection.php");
session_start();

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Get Billing Details from Session
$billing_name = $_SESSION['billing_name'];
$billing_phone = $_SESSION['billing_phone'];
$billing_email = $_SESSION['billing_email'];
$billing_country = $_SESSION['billing_country'];
$billing_city = $_SESSION['billing_city'];
$billing_street = $_SESSION['billing_street'];
$billing_zip = $_SESSION['billing_zip'];

// Fetch cart items and calculate the total
$sql = "SELECT cart.product_id, cart.quantity, products.price, products.name
        FROM cart 
        INNER JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_amount = 0;
$line_items = [];

// Prepare order details
while ($row = $result->fetch_assoc()) {
    // Check if product exists in the products table
    if (!empty($row['product_id'])) {
        // Ensure product exists in the products table
        $sql_check_product = "SELECT COUNT(*) AS count FROM products WHERE product_id = ?";
        $stmt_check_product = $conn->prepare($sql_check_product);
        $stmt_check_product->bind_param("i", $row['product_id']);
        $stmt_check_product->execute();
        $result_check = $stmt_check_product->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['count'] == 0) {
            echo "Error: Product ID {$row['product_id']} does not exist.";
            exit();
        }
        $stmt_check_product->close();

        // Fetch admin_id for the product
        $sql_admin_id = "SELECT admin_id FROM products WHERE product_id = ?";
        $stmt_admin = $conn->prepare($sql_admin_id);
        $stmt_admin->bind_param("i", $row['product_id']);
        $stmt_admin->execute();
        $stmt_admin->bind_result($admin_id);
        $stmt_admin->fetch();
        $stmt_admin->close();


        $line_items[] = [
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'total' => $row['price'] * $row['quantity'],
            'admin_id' => $admin_id,
        ];
        $total_amount += $row['price'] * $row['quantity'];
    }
}
$stmt->close();

// If there are no valid products in the cart
if (empty($line_items)) {
    echo "No valid products found in the cart.";
    exit();
}

// Insert into orders table for COD payment

$sql_order_items = "INSERT INTO orders (user_id, product_id, quantity, price, total_price, status, created_at, admin_id, 
                     billing_name, billing_phone, billing_email, billing_country, billing_city, billing_street, billing_zip)
                    VALUES (?, ?, ?, ?, ?, 'pending', NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_order_items = $conn->prepare($sql_order_items);

foreach ($line_items as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $total_price = $item['total'];
    $admin_id = $item['admin_id'];


    $stmt_order_items->bind_param(
        "iiiddissssssi", // Corrected format string
        $user_id,
        $product_id,
        $quantity,
        $price,
        $total_price,
        $admin_id,
        $billing_name,
        $billing_phone,
        $billing_email,
        $billing_country,
        $billing_city,
        $billing_street,
        $billing_zip
    );
    $stmt_order_items->execute();
}

$stmt_order_items->close();
$order_id = $conn->insert_id;
// Insert a record into the payment table for COD payment
$sql_payment = "INSERT INTO payment (order_id, payment_method, status, created_at)
                VALUES (?, 'cash_on_delivery', 'cod', NOW())";
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

// Confirmation message
echo "<script>
alert('Your order has been placed successfully!');
window.location.href = 'order_status.php';
</script>";
