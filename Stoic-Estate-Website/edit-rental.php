<?php
$title = 'Edit Rental';
include 'includes/header.php';
include 'includes/db.php';

// Start the session and retrieve the agent ID

$agent_id = $_SESSION['agent_id'] ?? null;

if (!$agent_id) {
    echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: You must be logged in to edit rentals.</div>";
    exit;
}

// Retrieve the rental ID from the URL
$rental_id = $_GET['id'] ?? null;

if (!$rental_id) {
    echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: Rental ID not specified.</div>";
    exit;
}

// Fetch the rental details from the database
$sql = "SELECT rentals.id, properties.title AS property_title, tenants.name AS tenant_name, rentals.start_date, rentals.rent_amount, rentals.status
        FROM rentals
        JOIN properties ON rentals.property_id = properties.id
        JOIN tenants ON rentals.tenant_id = tenants.id
        WHERE rentals.id = ? AND properties.agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $rental_id, $agent_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: Rental not found or you do not have permission to edit this rental.</div>";
    exit;
}

$rental = $result->fetch_assoc();

// Update rental logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rent_amount = $_POST['rent_amount'];
    $status = $_POST['status'];

    $update_sql = "UPDATE rentals SET rent_amount = ?, status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssi', $rent_amount, $status, $rental_id);

    if ($update_stmt->execute()) {
        echo "<div class='bg-green-100 text-green-800 p-4 rounded-lg'>Rental updated successfully!</div>";
        // Optionally redirect or refresh the page
        // header("Location: agent_manage_rentals.php");
        // exit;
    } else {
        echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: Could not update rental.</div>";
    }
}
?>

<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Rental</h2>
        <form method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Property</label>
                <input type="text" value="<?php echo htmlspecialchars($rental['property_title']); ?>" readonly
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tenant</label>
                <input type="text" value="<?php echo htmlspecialchars($rental['tenant_name']); ?>" readonly
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Rent Amount</label>
                <input type="number" name="rent_amount" value="<?php echo htmlspecialchars($rental['rent_amount']); ?>"
                       required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="active" <?php if ($rental['status'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($rental['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    Update Rental
                </button>
                <a href="agent_manage_rentals.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
