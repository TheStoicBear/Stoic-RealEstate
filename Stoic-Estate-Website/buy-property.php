<?php
$title = 'Buy Property';
include 'includes/header.php';
include 'includes/db.php';


$property_id = intval($_GET['id']);
$sql = "SELECT * FROM properties WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $property_id);
$stmt->execute();
$result = $stmt->get_result();
$property = $result->fetch_assoc();
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Inquire About Property</h2>
        <?php if ($property): ?>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-48 object-cover rounded-lg mb-4">
            <h3 class="text-2xl font-semibold mb-2"><?php echo htmlspecialchars($property['title']); ?></h3>
            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($property['beds']); ?> beds, <?php echo htmlspecialchars($property['baths']); ?> baths, <?php echo htmlspecialchars($property['sq_ft']); ?> sq ft</p>
            <p class="text-gray-800 font-semibold mb-4">$<?php echo number_format($property['cost'], 2); ?></p>
            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($property['city']); ?>, <?php echo htmlspecialchars($property['state']); ?> <?php echo htmlspecialchars($property['postal']); ?></p>
        </div>
        <?php else: ?>
        <p class="text-red-500">Property not found.</p>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
