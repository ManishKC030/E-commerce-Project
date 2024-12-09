<?php
// Database connection
include("../connection.php");
include("auth.php");

// Check if contact_id and status are provided
if (isset($_POST['contact_id'], $_POST['status'])) {
    $contact_id = intval($_POST['contact_id']);
    $status = $_POST['status'];

    // Update the status in the database
    $sql = "UPDATE contacts SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $status, $contact_id);
        if ($stmt->execute()) {
            echo "<script>alert('Status updated successfully.'); window.location.href = 'Message.php';</script>";
        } else {
            echo "<script>alert('Error updating status.'); window.location.href = 'Message.php';</script>";
        }
    } else {
        echo "<script>alert('Error preparing statement.'); window.location.href = 'Message.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'Message.php';</script>";
}
