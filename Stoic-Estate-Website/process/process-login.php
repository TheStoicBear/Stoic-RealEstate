<?php
include '../includes/db.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

// Retrieve user record
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    header('Location: ../index.php'); // Redirect to homepage or dashboard
} else {
    echo "Invalid login credentials.";
}
?>
