<?php
$title = 'Agent Invoices';
include 'includes/header.php';
include 'includes/db.php';

$agent_id = $_SESSION['agent_id'] ?? null;

if (!$agent_id) {
    echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>No agent is logged in.</div>";
    exit;
}

// Fetch invoices for the logged-in agent
$sql = "SELECT p.id AS payment_id, p.amount, p.payment_date, p.status, r.property_id, t.name AS tenant_name
        FROM payments p
        JOIN rentals r ON p.rental_id = r.id
        JOIN properties prop ON r.property_id = prop.id
        JOIN tenants t ON r.tenant_id = t.id
        WHERE prop.agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $agent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Your Invoices</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['tenant_name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['property_id']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">$<?php echo number_format($row['amount'], 2); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['payment_date']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['status']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="generate-invoice.php?payment_id=<?php echo $row['payment_id']; ?>" class="text-indigo-600 hover:text-indigo-900">Generate Invoice</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
