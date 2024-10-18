<?php
session_start();
include 'includes/db.php';

// Get chat_id from the query parameter
$chat_id = $_GET['chat_id'];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch chat messages
$sql = "SELECT * FROM chat_messages WHERE chat_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$result = $stmt->get_result();

// Display the chat messages
while ($message = $result->fetch_assoc()) {
    echo '<div>';
    echo '<strong>' . htmlspecialchars($message['sender_name']) . ':</strong> ' . htmlspecialchars($message['message']);
    echo '</div>';
}

// Chat message form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id'];

    // Insert new message into chat_messages table
    $sql = "INSERT INTO chat_messages (chat_id, user_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $chat_id, $user_id, $message);
    $stmt->execute();
}

?>

<form action="" method="post">
    <input type="text" name="message" required placeholder="Type your message">
    <button type="submit">Send</button>
</form>
