<?php
$title = 'Generate Invoice';
include 'includes/db.php';  // Database connection

if (!isset($_SESSION['agent_id'])) {
    header('Location: agent-login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $property_id = $_POST['property_id'];
    $tenant_id = $_POST['tenant_id'];
    $start_date = $_POST['start_date'];
    $rent_amount = $_POST['rent_amount'];
    $due_date = $_POST['due_date'];
    $agent_id = $_SESSION['agent_id'];

    // Prepare and execute query to create invoice
    $sql = "INSERT INTO payments (rental_id, tenant_id, amount, payment_date, status) VALUES (?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Note: Ensure that the rental_id (property_id) is valid before using it
    $stmt->bind_param('iids', $property_id, $tenant_id, $rent_amount, $due_date);
    
    if ($stmt->execute()) {
        echo "Invoice created successfully.";
    } else {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
