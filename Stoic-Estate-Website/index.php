<?php
$title = 'Home';
include 'includes/header.php';
include 'includes/db.php';



// Fetch properties from the database with agent information
$sql = "SELECT properties.*, agents.profile_picture, agents.name AS agent_name FROM properties
        JOIN agents ON properties.agent_id = agents.id
        LIMIT 6";
$result = $conn->query($sql);
?>

<main>
    <!-- Banner Image -->
    <section class="relative bg-blue-600 text-white">
        <img src="https://img.thestoicbear.dev/images/Stoic-2024-09-18_01-37-18-66ea2ece70ccf.jpg" alt="Real Estate Banner" class="w-full h-96 object-cover">
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center bg-black bg-opacity-50">
            <h1 class="text-5xl font-bold mb-4">Find Your Dream Home</h1>
            <p class="text-lg mb-8">Browse our latest listings and find the perfect property for you.</p>
            <a href="properties.php" class="bg-yellow-500 text-gray-800 px-6 py-3 rounded-lg text-lg font-semibold hover:bg-yellow-600">Browse Properties</a>
        </div>
    </section>

    <!-- Main Content -->
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Welcome to Our Real Estate Platform</h2>
        <p class="text-lg mb-6">We offer a wide range of properties to fit every need and budget. Whether you’re looking to buy your first home or find an investment property, we have something for you.</p>
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

                <div class="flex items-center mt-4">
                    <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="<?php echo htmlspecialchars($row['agent_name']); ?>" class="w-12 h-12 rounded-full object-cover mr-3">
                    <p class="text-gray-600">Listed by: <a href="agent-public-profile.php?username=<?php echo urlencode($row['agent_name']); ?>" class="text-blue-500 hover:underline"><?php echo htmlspecialchars($row['agent_name']); ?></a></p>
                </div>

                <a href="buy-property.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 mt-4 block text-center">Inquire Now</a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
