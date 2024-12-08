<?php
session_start();

include('../connection.php');

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ad_login.php');
    exit(); // Ensure no further code is executed
}
