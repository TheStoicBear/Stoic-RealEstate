<?php

include 'includes/db.php';  // Database connection
include 'includes/header.php';  // Header with navigation

if (!isset($_SESSION['agent_id'])) {
    header('Location: agent-login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Fetch properties and tenants to populate dropdowns
$properties = $conn->query("SELECT id, title FROM properties WHERE agent_id = $agent_id");
if (!$properties) {
    die("Property query failed: " . $conn->error);
}

$tenants = $conn->query("SELECT id, name FROM tenants");
if (!$tenants) {
    die("Tenant query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <link rel="stylesheet" href="path/to/tailwind.css"> <!-- Tailwind CSS -->
    <style>
        /* Add any custom styles here */
    </style>
</head>
<body>
<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-6">Create New Invoice</h1>

        <form action="generate-invoice.php" method="post">
            <div class="mb-4">
                <label for="property_id" class="block text-sm font-medium text-gray-700">Property</label>
                <select id="property_id" name="property_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
                    <?php while ($property = $properties->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($property['id']); ?>">
                            <?php echo htmlspecialchars($property['title']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="tenant_id" class="block text-sm font-medium text-gray-700">Tenant</label>
                <select id="tenant_id" name="tenant_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
                    <?php while ($tenant = $tenants->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($tenant['id']); ?>">
                            <?php echo htmlspecialchars($tenant['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="rent_amount" class="block text-sm font-medium text-gray-700">Rent Amount ($)</label>
                <input type="number" id="rent_amount" name="rent_amount" step="0.01" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" id="due_date" name="due_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white p-4 rounded-lg shadow hover:bg-blue-700 transition duration-150 ease-in-out">Create Invoice</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
