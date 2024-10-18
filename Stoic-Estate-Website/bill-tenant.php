<?php
$title = 'Agent Bill Tenant';
include 'includes/header.php';
include 'includes/db.php';

$rental_id = $_GET['rental_id'] ?? null;
$amount = $_POST['amount'] ?? null;
$tenant_id = $_POST['tenant_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($rental_id && $amount && $tenant_id) {
        $sql = "INSERT INTO payments (rental_id, tenant_id, amount, payment_date, status)
                VALUES (?, ?, ?, CURDATE(), 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iid', $rental_id, $tenant_id, $amount);
        
        if ($stmt->execute()) {
            echo "<div class='bg-green-100 text-green-800 p-4 rounded-lg'>Bill sent to tenant!</div>";
        } else {
            echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Please provide all required fields.</div>";
    }
}

// Fetch rental details for the form
if ($rental_id) {
    $sql = "SELECT r.id, r.property_id, r.tenant_id, r.rent_amount, t.name AS tenant_name
            FROM rentals r
            JOIN tenants t ON r.tenant_id = t.id
            WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $rental_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rental = $result->fetch_assoc();
}
?>

<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Bill Tenant</h2>
        <?php if ($rental): ?>
            <form method="post" class="space-y-6">
                <div>
                    <label for="tenant" class="block text-sm font-medium text-gray-700">Tenant</label>
                    <input type="text" id="tenant" name="tenant_name" value="<?php echo htmlspecialchars($rental['tenant_name']); ?>" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount ($)</label>
                    <input type="number" id="amount" name="amount" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <input type="hidden" name="tenant_id" value="<?php echo htmlspecialchars($rental['tenant_id']); ?>">
                <input type="hidden" name="rental_id" value="<?php echo htmlspecialchars($rental['id']); ?>">
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Send Bill
                </button>
            </form>
        <?php else: ?>
            <p class="text-red-500">Rental not found.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
