<?php
include 'includes/db.php';

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute SQL statement
$sql = "INSERT INTO users (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssss', $first_name, $last_name, $email, $hashed_password, $phone);

if ($stmt->execute()) {
    header('Location: login.php');
} else {
    echo "Error: " . $stmt->error;
}
?>
