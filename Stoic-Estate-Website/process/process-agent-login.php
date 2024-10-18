<?php
include '../includes/db.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

// Retrieve agent record
$sql = "SELECT * FROM agents WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$agent = $result->fetch_assoc();

if ($agent && password_verify($password, $agent['password'])) {
    $_SESSION['agent_id'] = $agent['id'];
    header('Location: ../agent-dashboard.php'); // Redirect to agent dashboard
} else {
    echo "Invalid login credentials.";
}
?>
