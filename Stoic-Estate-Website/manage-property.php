<?php
$title = 'Manage Properties';
include 'includes/header.php';
include 'includes/db.php';


if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Fetch properties belonging to the logged-in agent
$sql = "SELECT * FROM properties WHERE agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $agent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Manage Your Properties</h2>
        <a href="add-property.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 mb-4 inline-block">Add New Property</a>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['title']); ?></h3>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($row['beds']); ?> beds, <?php echo htmlspecialchars($row['baths']); ?> baths, <?php echo htmlspecialchars($row['sq_ft']); ?> sq ft</p>
                <p class="text-gray-800 font-semibold mb-4">$<?php echo number_format($row['cost'], 2); ?></p>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($row['city']); ?>, <?php echo htmlspecialchars($row['state']); ?> <?php echo htmlspecialchars($row['postal']); ?></p>
                <a href="edit-property.php?id=<?php echo $row['id']; ?>" class="bg-yellow-500 text-gray-800 px-4 py-2 rounded-lg hover:bg-yellow-600">Edit</a>
                <a href="process-delete-property.php?id=<?php echo $row['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
