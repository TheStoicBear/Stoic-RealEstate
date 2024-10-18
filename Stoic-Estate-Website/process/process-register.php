<?php
include 'includes/db.php';

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if this is the first user
$sql_count = "SELECT COUNT(*) AS user_count FROM users";
$result = $conn->query($sql_count);
$row = $result->fetch_assoc();
$is_first_user = $row['user_count'] == 0 ? true : false;

// Prepare SQL statement for inserting the user
$sql = "INSERT INTO users (first_name, last_name, email, password, phone, agent, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Set the agent flag and role based on whether this is the first user
$agent_flag = $is_first_user ? 1 : 0; // Set to 1 if first user
$role = $is_first_user ? 'agent' : 'tenant'; // Set to 'agent' if first user

$stmt->bind_param('sssssiss', $first_name, $last_name, $email, $hashed_password, $phone, $agent_flag, $role);

if ($stmt->execute()) {
    header('Location: login.php');
} else {
    echo "Error: " . $stmt->error;
}
?>
