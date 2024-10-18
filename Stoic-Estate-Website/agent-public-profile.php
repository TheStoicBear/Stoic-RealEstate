<?php
$title = 'Agent Edit Profile';
include 'includes/header.php';
include 'includes/db.php';

$agent_username = isset($_GET['username']) ? $_GET['username'] : '';

if (empty($agent_username)) {
    echo "No agent specified.";
    exit();
}

// Fetch agent details
$sql = "SELECT * FROM agents WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $agent_username);
$stmt->execute();
$agent = $stmt->get_result()->fetch_assoc();

if (!$agent) {
    echo "Agent not found.";
    exit();
}

// Fetch properties managed by the agent
$sql = "SELECT * FROM properties WHERE agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $agent['id']);
$stmt->execute();
$properties = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Meta Tags for Social Sharing -->
    <?php
    $profile_picture = !empty($agent['profile_picture']) ? htmlspecialchars($agent['profile_picture']) : 'https://via.placeholder.com/150';
    $bio = isset($agent['bio']) ? htmlspecialchars($agent['bio']) : 'Check out this agent\'s profile.';
    ?>
    <meta property="og:title" content="<?php echo htmlspecialchars($agent['name']); ?>'s Profile">
    <meta property="og:description" content="<?php echo $bio; ?>">
    <meta property="og:image" content="<?php echo $profile_picture; ?>">
    <meta property="og:url" content="<?php echo 'https://realestate.thestoicbear.dev/' . urlencode($agent['name']); ?>">

    <!-- Optional: Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($agent['name']); ?>'s Profile">
    <meta name="twitter:description" content="<?php echo $bio; ?>">
    <meta name="twitter:image" content="<?php echo $profile_picture; ?>">
</head>
<body>
    <main>
        <section class="container mx-auto p-6">
            <h2 class="text-3xl font-bold mb-6"><?php echo htmlspecialchars($agent['name']); ?>'s Profile</h2>
            <div class="flex items-center mb-6">
                <img src="<?php echo !empty($agent['profile_picture']) ? htmlspecialchars($agent['profile_picture']) : 'https://via.placeholder.com/150'; ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover mr-4">
                <div>
                    <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($agent['name']); ?></h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars($agent['email']); ?></p>
                    <?php if (isset($agent['phone']) && !empty($agent['phone'])): ?>
                        <p class="text-gray-600">Phone: <?php echo htmlspecialchars($agent['phone']); ?></p>
                    <?php endif; ?>
                    <?php if (isset($agent['bio']) && !empty($agent['bio'])): ?>
                        <p class="text-gray-600">Bio: <?php echo htmlspecialchars($agent['bio']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <h3 class="text-2xl font-semibold mb-4">Properties for Sale</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($row = $properties->fetch_assoc()): ?>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($row['beds']); ?> beds, <?php echo htmlspecialchars($row['baths']); ?> baths, <?php echo htmlspecialchars($row['sq_ft']); ?> sq ft</p>
                    <p class="text-gray-800 font-semibold mb-4">$<?php echo number_format($row['cost'], 2); ?></p>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($row['city']); ?>, <?php echo htmlspecialchars($row['state']); ?> <?php echo htmlspecialchars($row['postal']); ?></p>
                    <p class="text-gray-600 mb-4">Location: <?php echo htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']) . ' ' . htmlspecialchars($row['postal']); ?></p>
                    <p class="text-gray-600 mb-4">Coordinates: Latitude <?php echo htmlspecialchars($row['latitude']); ?>, Longitude <?php echo htmlspecialchars($row['longitude']); ?><?php if ($row['altitude']) echo ', Altitude ' . htmlspecialchars($row['altitude']); ?></p>
                    
                    <a href="buy-property.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">Inquire Now</a>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
