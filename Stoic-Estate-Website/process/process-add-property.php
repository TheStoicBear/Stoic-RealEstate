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
$altitude = isset($_POST['altitude']) ? $_POST['altitude'] : 'NULL'; // Optional altitude
$interiorMap = $_POST['interiorMap'];
$interiorCoords = $_POST['interiorCoords'];
$outsideCoords = $_POST['outsideCoords'];
$height = isset($_POST['height']) ? $_POST['height'] : 'NULL'; // Optional height

// Escape special characters to prevent SQL injection
$agent_id = mysqli_real_escape_string($conn, $agent_id);
$title = mysqli_real_escape_string($conn, $title);
$description = mysqli_real_escape_string($conn, $description);
$city = mysqli_real_escape_string($conn, $city);
$state = mysqli_real_escape_string($conn, $state);
$postal = mysqli_real_escape_string($conn, $postal);
$cost = mysqli_real_escape_string($conn, $cost);
$image = mysqli_real_escape_string($conn, $image);
$beds = mysqli_real_escape_string($conn, $beds);
$baths = mysqli_real_escape_string($conn, $baths);
$sq_ft = mysqli_real_escape_string($conn, $sq_ft);
$latitude = mysqli_real_escape_string($conn, $latitude);
$longitude = mysqli_real_escape_string($conn, $longitude);
$interiorMap = mysqli_real_escape_string($conn, $interiorMap);
$interiorCoords = mysqli_real_escape_string($conn, $interiorCoords);
$outsideCoords = mysqli_real_escape_string($conn, $outsideCoords);

// Adjust for optional fields and correct for SQL
$altitude = ($altitude === 'NULL') ? 'NULL' : "'" . mysqli_real_escape_string($conn, $altitude) . "'";
$height = ($height === 'NULL') ? 'NULL' : "'" . mysqli_real_escape_string($conn, $height) . "'";

// Create the SQL query without bind_param
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
