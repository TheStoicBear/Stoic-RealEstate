<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['agent_id'])) {
    header('Location: agent-login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
$property_id = intval($_GET['id']);

// Delete the property
$sql = "DELETE FROM properties WHERE id = ? AND agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $property_id, $agent_id);

if ($stmt->execute()) {
    header('Location: ../agent-dashboard.php');
} else {
    echo "Error: " . $stmt->error;
}
?>
