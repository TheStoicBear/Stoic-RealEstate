<?php

include 'includes/db.php';  // Database connection
include 'includes/header.php';  // Header with navigation

if (!isset($_SESSION['agent_id'])) {
    header('Location: agent-login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Fetch agent data
$sql = "SELECT * FROM agents WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param('i', $agent_id);  // Corrected variable name
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$agentResult = $stmt->get_result();

if ($agentResult->num_rows === 0) {
    die("No agent found with ID: " . htmlspecialchars($agent_id));  // Corrected variable name
}

$agent = $agentResult->fetch_assoc();

if (!$agent) {
    die("Fetch failed: " . $stmt->error);
}
?>

<!-- Dashboard HTML -->
<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($agent['name']); ?>!</h1>
        <img src="<?php echo $agent['profile_picture'] ? htmlspecialchars($agent['profile_picture']) : 'https://via.placeholder.com/150'; ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover mr-4">
            <div>
                <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($agent['name']); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($agent['email']); ?></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Button to Invoices -->
            <a href="agent-invoices.php" class="bg-blue-600 text-white p-4 rounded-lg shadow hover:bg-blue-700 transition duration-150 ease-in-out">
                <h2 class="text-xl font-semibold">Invoices</h2>
                <p class="mt-2">Manage and view your invoices.</p>
            </a>

            <!-- Button to Invoices -->
            <a href="agent-dashboard.php" class="bg-blue-600 text-white p-4 rounded-lg shadow hover:bg-blue-700 transition duration-150 ease-in-out">
                <h2 class="text-xl font-semibold">Properties</h2>
                <p class="mt-2">Manage and view your Properties.</p>
            </a>

            <!-- Button to Add Rental -->
            <a href="agent-add-rental.php" class="bg-green-600 text-white p-4 rounded-lg shadow hover:bg-green-700 transition duration-150 ease-in-out">
                <h2 class="text-xl font-semibold">Add Rental</h2>
                <p class="mt-2">Add new rental records.</p>
            </a>

            <!-- Button to Profile -->
            <a href="agent-profile.php" class="bg-gray-600 text-white p-4 rounded-lg shadow hover:bg-gray-700 transition duration-150 ease-in-out">
                <h2 class="text-xl font-semibold">Profile</h2>
                <p class="mt-2">View and edit your profile information.</p>
            </a>

                        <!-- Button to Profile -->
            <a href="agent-manage-rentals.php" class="bg-gray-600 text-white p-4 rounded-lg shadow hover:bg-gray-700 transition duration-150 ease-in-out">
                <h2 class="text-xl font-semibold">Manage Rentals</h2>
                <p class="mt-2">View and edit your rental property information.</p>
            </a>

            <!-- Button to Other Sections (Optional) -->
            <a href="agent-reports.php" class="bg-yellow-600 text-white p-4 rounded-lg shadow hover:bg-yellow-700 transition duration-150 ease-in-out">
                <h2 class="text-xl font-semibold">Reports</h2>
                <p class="mt-2">Generate and view various reports.</p>
            </a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; // Include footer ?>
