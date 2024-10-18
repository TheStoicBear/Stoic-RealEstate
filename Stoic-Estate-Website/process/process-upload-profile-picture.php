<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['agent_id'])) {
    header('Location: ../agent-login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
$upload_dir = '../images/profile_pictures/';
$upload_file = $upload_dir . basename($_FILES['profile_picture']['name']);
$file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));

// Check if the file is an image
$check = getimagesize($_FILES['profile_picture']['tmp_name']);
if ($check === false) {
    echo "File is not an image.";
    exit();
}

// Check file size (5MB max)
if ($_FILES['profile_picture']['size'] > 5000000) {
    echo "Sorry, your file is too large.";
    exit();
}

// Allow certain file formats
if ($file_type != "jpg" && $file_type != "jpeg" && $file_type != "png" && $file_type != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    exit();
}

// Move the file to the uploads directory
if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_file)) {
    // Update the agent's profile picture in the database
    $sql = "UPDATE agents SET profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $upload_file, $agent_id);

    if ($stmt->execute()) {
        header('Location: ../agent-dashboard.php');
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>
