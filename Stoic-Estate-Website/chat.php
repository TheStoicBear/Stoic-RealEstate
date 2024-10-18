<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$property_id = $_GET['property_id'];
$agent_id = $_GET['agent_id'];

// Fetch messages for this chat
$sql = "SELECT * FROM messages WHERE property_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $property_id);
$stmt->execute();
$messages = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat with Agent</title>
    <link rel="stylesheet" href="path/to/tailwind.css"> <!-- Add your CSS -->
</head>
<body>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Chat with Agent</h2>
        <div class="bg-gray-100 p-4 rounded-lg h-96 overflow-y-scroll">
            <?php while ($msg = $messages->fetch_assoc()): ?>
                <div class="<?php echo $msg['sender_id'] == $_SESSION['user_id'] ? 'text-right' : 'text-left'; ?>">
                    <p class="bg-blue-200 rounded-lg p-2 mb-2"><?php echo htmlspecialchars($msg['message']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
        
        <form action="send-message.php" method="POST" class="mt-4">
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
            <input type="hidden" name="agent_id" value="<?php echo $agent_id; ?>">
            <textarea name="message" class="w-full p-2 border rounded-lg" required></textarea>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Send</button>
        </form>
    </div>
</body>
</html>
