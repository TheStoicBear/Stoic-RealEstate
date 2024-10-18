<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['agent_id'])) {
    header('Location: agent-login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
$property_id = intval($_POST['id']);
$title = $_POST['title'];
$description = isset($_POST['description']) ? $_POST['description'] : ''; // Default to empty string if not set
$city = $_POST['city'];
$state = $_POST['state'];
$postal = $_POST['postal'];
$cost = $_POST['cost'];
$image = $_POST['image'];
$beds = intval($_POST['beds']); // Convert to integer
$baths = intval($_POST['baths']); // Convert to integer
$sq_ft = intval($_POST['sq_ft']); // Convert to integer
$latitude = floatval($_POST['latitude']); // Convert to float
$longitude = floatval($_POST['longitude']); // Convert to float
$altitude = isset($_POST['altitude']) ? floatval($_POST['altitude']) : 'NULL'; // Default to NULL if not set

// Construct the SQL query
$sql = "UPDATE properties SET 
            title = '$title', 
            description = '$description', 
            city = '$city', 
            state = '$state', 
            postal = '$postal', 
            cost = '$cost', 
            image = '$image', 
            beds = $beds, 
            baths = $baths, 
            sq_ft = $sq_ft, 
            latitude = $latitude, 
            longitude = $longitude, 
            altitude = $altitude 
        WHERE id = $property_id AND agent_id = $agent_id";

// Execute the query
if ($conn->query($sql) === TRUE) {
    header('Location: ../agent-dashboard.php');
} else {
    echo "Error: " . $conn->error;
}

?>
