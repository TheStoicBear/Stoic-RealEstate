<?php
$title = 'Properties';
include 'includes/header.php';
include 'includes/db.php';

// Fetch all properties from the database
$sql = "SELECT * FROM properties";
$result = $conn->query($sql);
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">All Properties</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['title']); ?></h3>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($row['beds']); ?> beds, <?php echo htmlspecialchars($row['baths']); ?> baths, <?php echo htmlspecialchars($row['sq_ft']); ?> sq ft</p>
                <p class="text-gray-800 font-semibold mb-4">$<?php echo number_format($row['cost'], 2); ?></p>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($row['city']); ?>, <?php echo htmlspecialchars($row['state']); ?> <?php echo htmlspecialchars($row['postal']); ?></p>
                <p class="text-gray-600 mb-4">Location: <?php echo htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']) . ' ' . htmlspecialchars($row['postal']); ?></p>
                <p class="text-gray-600 mb-4">Coordinates: Latitude <?php echo htmlspecialchars($row['latitude']); ?>, Longitude <?php echo htmlspecialchars($row['longitude']); ?><?php if ($row['altitude']) echo ', Altitude ' . htmlspecialchars($row['altitude']); ?></p>

                <!-- Updated Inquire Now button to link to chat.php -->
                <a href="chat.php?property_id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Inquire Now</a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
