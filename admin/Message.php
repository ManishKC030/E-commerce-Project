<?php
// Database connection
include("../connection.php");
include("ad_nav.php");
require("auth.php");



// Fetch contact messages
$sql = "SELECT c.id, c.name, c.email, c.message, c.status, c.created_at, u.username AS user_name 
        FROM contacts c
        LEFT JOIN users u ON c.user_id = u.user_id
        ORDER BY c.created_at ASC";
$result = $conn->query($sql);

// Check if any messages exist
if ($result && $result->num_rows > 0) {
    $messages = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $messages = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Messages</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-read {
            color: green;
            font-weight: bold;
        }

        .status-resolved {
            color: blue;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Contact Us Messages</h1>

    <?php if (!empty($messages)) : ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>User (If Logged In)</th>
                    <th>Submitted On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message) : ?>
                    <tr>
                        <td><?php echo $message['id']; ?></td>
                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
                        <td class="status-<?php echo strtolower($message['status']); ?>">
                            <?php echo ucfirst($message['status']); ?>
                        </td>
                        <td>
                            <?php echo $message['user_name'] ? htmlspecialchars($message['user_name']) : 'Guest'; ?>
                        </td>
                        <td><?php echo $message['created_at']; ?></td>
                        <td>
                            <form method="POST" action="update_contact.php" style="display:inline-block;">
                                <input type="hidden" name="contact_id" value="<?php echo $message['id']; ?>">
                                <select name="status" required>
                                    <option value="pending" <?php echo $message['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="read" <?php echo $message['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                    <option value="resolved" <?php echo $message['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No messages found.</p>
    <?php endif; ?>

</body>

</html>