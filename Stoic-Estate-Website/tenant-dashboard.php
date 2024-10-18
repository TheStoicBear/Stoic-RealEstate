<?php
include 'includes/db.php';  // Database connection
include 'includes/header.php';  // Header with navigation

// Ensure the user is logged in as a tenant
if (!isset($_SESSION['tenant_id'])) {
    header('Location: tenant-login.php');
    exit();
}

$tenant_id = $_SESSION['tenant_id'];

// Fetch tenant data
$sql = "SELECT * FROM tenants WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param('i', $tenant_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$tenantResult = $stmt->get_result();

if ($tenantResult->num_rows === 0) {
    die("No tenant found with ID: " . htmlspecialchars($tenant_id));
}

$tenant = $tenantResult->fetch_assoc();

if (!$tenant) {
    die("Fetch failed: " . $stmt->error);
}
?>

<!-- Dashboard HTML -->
<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($tenant['name']); ?>!</h1>
        
        <!-- Display Profile Picture -->
        <img src="<?php echo !empty($tenant['profile_picture']) ? htmlspecialchars($tenant['profile_picture']) : 'https://via.placeholder.com/150'; ?>" 
             alt="Profile Picture" 
             class="w-24 h-24 rounded-full object-cover mr-4">
             
        <div>
            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($tenant['name']); ?></h3>
            <p class="text-gray-600"><?php echo htmlspecialchars($tenant['email']); ?></p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <!-- Button to Rent Payments -->
        <a href="tenant-payments.php" class="bg-blue-600 text-white p-4 rounded-lg shadow hover:bg-blue-700 transition duration-150 ease-in-out">
            <h2 class="text-xl font-semibold">Rent Payments</h2>
            <p class="mt-2">Manage and view your rent payments.</p>
        </a>

        <!-- Button to View Rentals -->
        <a href="tenant-rentals.php" class="bg-blue-600 text-white p-4 rounded-lg shadow hover:bg-blue-700 transition duration-150 ease-in-out">
            <h2 class="text-xl font-semibold">My Rentals</h2>
            <p class="mt-2">View your rented properties.</p>
        </a>

        <!-- Button to Add Maintenance Request -->
        <a href="tenant-add-maintenance.php" class="bg-green-600 text-white p-4 rounded-lg shadow hover:bg-green-700 transition duration-150 ease-in-out">
            <h2 class="text-xl font-semibold">Add Maintenance Request</h2>
            <p class="mt-2">Request maintenance for your rental.</p>
        </a>

        <!-- Button to Profile -->
        <a href="tenant-profile.php" class="bg-gray-600 text-white p-4 rounded-lg shadow hover:bg-gray-700 transition duration-150 ease-in-out">
            <h2 class="text-xl font-semibold">Profile</h2>
            <p class="mt-2">View and edit your profile information.</p>
        </a>

        <!-- Button to Manage Renters -->
        <a href="tenant-manage-rentals.php" class="bg-gray-600 text-white p-4 rounded-lg shadow hover:bg-gray-700 transition duration-150 ease-in-out">
            <h2 class="text-xl font-semibold">Manage Rentals</h2>
            <p class="mt-2">View and manage your rental information.</p>
        </a>

        <!-- Button to Reports -->
        <a href="tenant-reports.php" class="bg-yellow-600 text-white p-4 rounded-lg shadow hover:bg-yellow-700 transition duration-150 ease-in-out">
            <h2 class="text-xl font-semibold">Reports</h2>
            <p class="mt-2">Generate and view various reports.</p>
        </a>
    </div>
</main>

<?php include 'includes/footer.php'; // Include footer ?>
