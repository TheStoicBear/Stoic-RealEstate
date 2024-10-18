<?php
$title = 'Agent Dashboard';
include 'includes/header.php';
include 'includes/db.php';


if (!isset($_SESSION['agent_id'])) {
    header('Location: agent-login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Fetch agent details
$sql = "SELECT * FROM agents WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $agent_id);
$stmt->execute();
$agent = $stmt->get_result()->fetch_assoc();

// Fetch properties managed by the agent
$sql = "SELECT * FROM properties WHERE agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $agent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Properties Dashboard</h2>
        <div class="flex items-center mb-6">
            <img src="<?php echo $agent['profile_picture'] ? htmlspecialchars($agent['profile_picture']) : 'https://via.placeholder.com/150'; ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover mr-4">
            <div>
                <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($agent['name']); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($agent['email']); ?></p>
            </div>
        </div>
        <a href="add-property.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Add New Property</a>
        <div class="mt-6">
            <h3 class="text-2xl font-semibold mb-4">Your Properties</h3>
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Title</th>
                        <th class="py-2 px-4 border-b">Location</th>
                        <th class="py-2 px-4 border-b">Cost</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']) . ' ' . htmlspecialchars($row['postal']); ?></td>
                        <td class="py-2 px-4 border-b">$<?php echo number_format($row['cost'], 2); ?></td>
                        <td class="py-2 px-4 border-b">
                            <a href="edit-property.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Edit</a> |
                            <a href="process/process-delete-property.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
