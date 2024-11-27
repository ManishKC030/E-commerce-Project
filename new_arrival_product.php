<?php

include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products");

$stmt->execute();
$new_arrival = $stmt->get_result();
 
?>