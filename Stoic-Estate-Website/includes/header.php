<?php
include 'includes/db.php'; // Ensure this path is correct
session_start();
// Initialize variables
$is_agent = false;
$agent = null;

// Determine if logged-in user is an agent
if (isset($_SESSION['agent_id'])) {
    $agent_id = $_SESSION['agent_id'];
    $sql = "SELECT * FROM agents WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $agent_id);
    $stmt->execute();
    $agent = $stmt->get_result()->fetch_assoc();
    $is_agent = true;
} elseif (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT agent FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $is_agent = $user['agent'] ?? null; // Use null if 'agent' is not set in the 'user' array

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-gray-800 text-white">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <a href="index.php" class="text-xl md:text-2xl font-bold">Real Estate</a>
            <div class="flex items-center space-x-2 md:space-x-4">
                <a href="index.php" class="hover:underline text-sm md:text-base">Home</a>
                <a href="properties.php" class="hover:underline text-sm md:text-base">Properties</a>
                <?php if ($is_agent): ?>
                    <!-- Display profile picture and dropdown for agents -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 hover:underline">
                            <img src="<?php echo htmlspecialchars($agent['profile_picture']); ?>" alt="Agent Profile Picture" class="w-6 h-6 md:w-8 md:h-8 rounded-full">
                            <span class="text-sm md:text-base"><?php echo htmlspecialchars($agent['name']); ?></span>
                        </button>
                        <div class="absolute right-0 mt-2 w-40 md:w-48 bg-white text-gray-800 border border-gray-300 rounded-lg shadow-lg hidden group-hover:block z-50">
                            <a href="a-dash.php" class="block px-4 py-2 hover:bg-gray-100 text-sm md:text-base">Agent Dashboard</a>
                            <a href="agent-public-profile.php?username=<?php echo urlencode($agent['name']); ?>" class="block px-4 py-2 hover:bg-gray-100 text-sm md:text-base">Agent Profile</a>
                            <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100 text-sm md:text-base">Logout</a>
                        </div>
                    </div>
                <?php elseif (isset($_SESSION['user_id'])): ?>
                    <!-- Display dashboard options for regular users -->
                    <a href="tenant-dashboard.php" class="hover:underline text-sm md:text-base">Tenant Dashboard</a>
                    <a href="logout.php" class="hover:underline text-sm md:text-base">Logout</a>
                <?php else: ?>
                    <!-- Display login options for non-logged-in users -->
                    <a href="agent-login.php" class="hover:underline text-sm md:text-base">Agent Login</a>
                    <a href="tenant-login.php" class="hover:underline text-sm md:text-base">Tenant Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <script>
        // Show dropdown on hover
        document.querySelectorAll('.group').forEach(function (element) {
            element.addEventListener('mouseover', function () {
                this.querySelector('.hidden').classList.remove('hidden');
            });
            element.addEventListener('mouseleave', function () {
                this.querySelector('.hidden').classList.add('hidden');
            });
        });
    </script>
</body>
</html>
