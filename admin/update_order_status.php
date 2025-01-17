<?php
include("../connection.php");

require("auth.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    if (in_array($status, ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])) {
        $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $order_id);

        if ($stmt->execute()) {
            echo "<script>alert('Order status updated successfully!'); window.location.href = 'merged_order.php';</script>";
        } else {
            echo "<script>alert('Failed to update order status.'); window.location.href = 'merged_order.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid status selected.'); window.location.href = 'merged_order.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'merged_order.php';</script>";
}
