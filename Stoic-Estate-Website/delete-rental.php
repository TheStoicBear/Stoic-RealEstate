<?php
$title = 'Delete Rental';
include 'includes/header.php';
include 'includes/db.php';

// Start the session and retrieve the agent ID
session_start();
$agent_id = $_SESSION['agent_id'] ?? null;

if (!$agent_id) {
    echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: You must be logged in to delete rentals.</div>";
    exit;
}

// Retrieve the rental ID from the URL
$rental_id = $_GET['id'] ?? null;

if (!$rental_id) {
    echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: Rental ID not specified.</div>";
    exit;
}

// Fetch the rental details to confirm ownership
$sql = "SELECT properties.title AS property_title, rentals.id
        FROM rentals
        JOIN properties ON rentals.property_id = properties.id
        WHERE rentals.id = ? AND properties.agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $rental_id, $agent_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: Rental not found or you do not have permission to delete this rental.</div>";
    exit;
}

$rental = $result->fetch_assoc();

// Delete rental logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete_sql = "DELETE FROM rentals WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param('i', $rental_id);

    if ($delete_stmt->execute()) {
        echo "<div class='bg-green-100 text-green-800 p-4 rounded-lg'>Rental deleted successfully!</div>";
        // Optionally redirect to the manage rentals page
        // header("Location: agent_manage_rentals.php");
        // exit;
    } else {
        echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: Could not delete rental.</div>";
    }
}
?>

<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Delete Rental</h2>
        <p>Are you sure you want to delete the rental for the property <strong><?php echo htmlspecialchars($rental['property_title']); ?></strong>?</p>
        <form method="post" class="mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">
                Confirm Delete
            </button>
            <a href="agent_manage_rentals.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700">
                Cancel
            </a>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
