<?php
$title = 'Agents Profile';
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
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Edit Your Profile</h2>
        <form action="process/process-upload-profile-picture.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="profile_picture" class="block text-gray-700">Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Upload Picture</button>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
