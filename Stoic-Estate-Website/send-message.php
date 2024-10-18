<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_id = $_POST['property_id'];
    $agent_id = $_POST['agent_id'];
    $message = $_POST['message'];
    $sender_id = $_SESSION['user_id'];

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO messages (property_id, sender_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $property_id, $sender_id, $message);
    
    if ($stmt->execute()) {
        // Redirect back to chat
        header("Location: chat.php?property_id=$property_id&agent_id=$agent_id");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
