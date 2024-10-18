<?php
$title = 'Agent Add Rentals';
include 'includes/header.php';
include 'includes/db.php';

// Initialize variables
$property_id = $_POST['property_id'] ?? null;
$tenant_id = $_POST['tenant_id'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$rent_amount = $_POST['rent_amount'] ?? null;
$status = $_POST['status'] ?? 'pending';
$due_date = $_POST['due_date'] ?? null;

// Fetch properties for dropdown
$propertiesQuery = "SELECT id, title FROM properties WHERE agent_id = ?"; // Assuming agent_id is the logged-in agent's ID
$stmt = $conn->prepare($propertiesQuery);
$stmt->bind_param('i', $_SESSION['agent_id']); // Replace with the session variable for logged-in agent
$stmt->execute();
$propertiesResult = $stmt->get_result();

// Fetch tenants for dropdown
$tenantsQuery = "SELECT id, name FROM tenants";
$stmt = $conn->prepare($tenantsQuery);
$stmt->execute();
$tenantsResult = $stmt->get_result();

// Check if tenant_id exists
if ($tenant_id) {
    $checkTenantSql = "SELECT id FROM tenants WHERE id = ?";
    $stmt = $conn->prepare($checkTenantSql);
    $stmt->bind_param('i', $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: Tenant ID does not exist.</div>";
        exit;
    }
}

// Insert data into rentals
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO rentals (property_id, tenant_id, start_date, rent_amount, status, due_date)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissss', $property_id, $tenant_id, $start_date, $rent_amount, $status, $due_date);
    
    if ($stmt->execute()) {
        echo "<div class='bg-green-100 text-green-800 p-4 rounded-lg'>Rental added successfully!</div>";
    } else {
        echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: " . $stmt->error . "</div>";
    }
}
?>

<!-- HTML Form for Adding Rentals -->
<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Add Rental</h2>
        <form method="post" class="space-y-6">
            <div>
                <label for="property_id" class="block text-sm font-medium text-gray-700">Property ID</label>
                <select name="property_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select a property</option>
                    <?php while ($property = $propertiesResult->fetch_assoc()): ?>
                        <option value="<?php echo $property['id']; ?>"><?php echo htmlspecialchars($property['title']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="tenant_id" class="block text-sm font-medium text-gray-700">Tenant ID</label>
                <select name="tenant_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select a tenant</option>
                    <?php while ($tenant = $tenantsResult->fetch_assoc()): ?>
                        <option value="<?php echo $tenant['id']; ?>"><?php echo htmlspecialchars($tenant['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="rent_amount" class="block text-sm font-medium text-gray-700">Rent Amount ($)</label>
                <input type="number" name="rent_amount" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" name="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Add Rental
            </button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
