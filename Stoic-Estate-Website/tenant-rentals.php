<?php
include 'includes/db.php';      // Database connection
include 'includes/header.php';  // Header with navigation

// Fetch tenant rentals
$query = "
    SELECT rentals.id, properties.title, rentals.start_date, rentals.rent_amount, rentals.status, tenants.name AS tenant_name
    FROM rentals
    JOIN properties ON rentals.property_id = properties.id
    JOIN tenants ON rentals.tenant_id = tenants.id
    ORDER BY rentals.start_date DESC
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<div class="container mx-auto mt-4">
    <h1 class="text-3xl font-bold mb-4">Tenant Rentals</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="min-w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Rental ID</th>
                    <th class="border px-4 py-2">Tenant Name</th>
                    <th class="border px-4 py-2">Property Title</th>
                    <th class="border px-4 py-2">Start Date</th>
                    <th class="border px-4 py-2">Rent Amount</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $row['id']; ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['tenant_name']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['start_date']); ?></td>
                        <td class="border px-4 py-2">$<?php echo number_format($row['rent_amount'], 2); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-gray-600">No rentals found.</p>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>
</div>

<?php include 'includes/footer.php'; // Footer ?>
