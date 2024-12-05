<?php
// Start session
session_start();

// Destroy session to log out
session_unset();
session_destroy();

// Redirect to index.php
header("Location: ../index.php");
exit;
?>
