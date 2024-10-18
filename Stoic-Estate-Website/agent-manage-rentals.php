<?php
$title = 'Manage Rentals';
include 'includes/header.php';
include 'includes/db.php';

// Assuming you have the agent's ID stored in the session after login

$agent_id = $_SESSION['agent_id'] ?? null;

if (!$agent_id) {
    echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: You must be logged in to manage rentals.</div>";
    exit;
}

// Fetch rentals for the logged-in agent
$sql = "SELECT rentals.id AS rental_id, properties.title AS property_title, tenants.name AS tenant_name, rentals.start_date, rentals.rent_amount, rentals.status
        FROM rentals
        JOIN properties ON rentals.property_id = properties.id
        JOIN tenants ON rentals.tenant_id = tenants.id
        WHERE properties.agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $agent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Manage Rentals</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($rental = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $rental['rental_id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($rental['property_title']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($rental['tenant_name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($rental['start_date']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">$<?php echo number_format($rental['rent_amount'], 2); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($rental['status']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="edit-rental.php?id=<?php echo $rental['rental_id']; ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <a href="delete-rental.php?id=<?php echo $rental['rental_id']; ?>" class="text-red-600 hover:text-red-900 ml-4">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class='bg-yellow-100 text-yellow-800 p-4 rounded-lg'>No rentals found for your properties.</div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
