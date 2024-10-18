<?php
$title = 'Agent View Rentals';
include 'includes/header.php';
include 'includes/db.php';

$agent_id = $_SESSION['agent_id']; // Assume agent is logged in

$sql = "SELECT r.id, p.title, u.name AS tenant_name, r.start_date, r.rent_amount, r.status
        FROM rentals r
        JOIN properties p ON r.property_id = p.id
        JOIN users u ON r.tenant_id = u.id
        WHERE r.agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $agent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Your Rentals</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['title']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['tenant_name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">$<?php echo number_format($row['rent_amount'], 2); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['status']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="bill-tenant.php?rental_id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900">Bill Tenant</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
