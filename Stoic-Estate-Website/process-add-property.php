<?php
$title = 'Process Add Property';
include '../includes/db.php';

// Start the session if not already started
session_start();

if (!isset($_SESSION['agent_id'])) {
    header('Location: agent-login.php');
    exit();
}

// Collect POST data
$agent_id = $_SESSION['agent_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$city = $_POST['city'];
$state = $_POST['state'];
$postal = $_POST['postal'];
$cost = $_POST['cost'];
$image = $_POST['image'];
$beds = $_POST['beds'];
$baths = $_POST['baths'];
$sq_ft = $_POST['sq_ft'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$altitude = isset($_POST['altitude']) ? $_POST['altitude'] : 'NULL'; // Optional
$interiorMap = $_POST['interiorMap'];
$interiorCoords = $_POST['interiorCoords'];
$outsideCoords = $_POST['outsideCoords'];
$height = isset($_POST['height']) ? $_POST['height'] : 'NULL'; // Optional

// Build the SQL query dynamically
$sql = "INSERT INTO properties 
    (agent_id, title, description, city, state, postal, cost, image, beds, baths, sq_ft, latitude, longitude, altitude, interiorMap, interiorCoords, outsideCoords, height) 
    VALUES (
        '$agent_id', 
        '$title', 
        '$description', 
        '$city', 
        '$state', 
        '$postal', 
        '$cost', 
        '$image', 
        '$beds', 
        '$baths', 
        '$sq_ft', 
        '$latitude', 
        '$longitude', 
        $altitude, 
        '$interiorMap', 
        '$interiorCoords', 
        '$outsideCoords', 
        $height
    )";

// Execute the query
if ($conn->query($sql) === TRUE) {
    // Redirect upon success
    header('Location: ../agent-dashboard.php');
    exit(); // Always exit after header redirection
} else {
    // Display the error if something goes wrong
    echo "Error: " . $conn->error;
}

// Close the connection
$conn->close();
?>
