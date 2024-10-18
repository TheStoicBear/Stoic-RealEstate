<?php
session_start(); // Start the session

// Destroy all session data
$_SESSION = array(); // Clear the session array

// If a session cookie exists, delete it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to the home page or login page
header("Location: index.php"); // Change to 'login.php' if preferred
exit();
?>
