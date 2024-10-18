<?php
$title = 'Add Property';
include 'includes/header.php';
include 'includes/db.php';

session_start(); // Ensure the session is started

if (!isset($_SESSION['agent_id'])) {
    header('Location: agent-login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Add New Property</h2>
        <form action="process/process-add-property.php" method="POST" class="bg-white p-6 rounded-lg shadow-lg" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" name="title" id="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700">Image URL</label>
                <input type="text" name="image" id="image" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="beds" class="block text-gray-700">Beds</label>
                <input type="number" name="beds" id="beds" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="baths" class="block text-gray-700">Baths</label>
                <input type="number" name="baths" id="baths" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="sq_ft" class="block text-gray-700">Square Feet</label>
                <input type="number" name="sq_ft" id="sq_ft" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="cost" class="block text-gray-700">Cost</label>
                <input type="number" name="cost" id="cost" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="city" class="block text-gray-700">City</label>
                <input type="text" name="city" id="city" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="state" class="block text-gray-700">State</label>
                <input type="text" name="state" id="state" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="postal" class="block text-gray-700">Postal Code</label>
                <input type="text" name="postal" id="postal" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="latitude" class="block text-gray-700">Latitude</label>
                <input type="text" name="latitude" id="latitude" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="longitude" class="block text-gray-700">Longitude</label>
                <input type="text" name="longitude" id="longitude" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="altitude" class="block text-gray-700">Altitude (optional)</label>
                <input type="text" name="altitude" id="altitude" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="interiorMap" class="block text-gray-700">Interior Map</label>
                <input type="text" name="interiorMap" id="interiorMap" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="interiorCoords" class="block text-gray-700">Interior Coordinates (JSON)</label>
                <textarea name="interiorCoords" id="interiorCoords" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div class="mb-4">
                <label for="outsideCoords" class="block text-gray-700">Outside Coordinates (JSON)</label>
                <textarea name="outsideCoords" id="outsideCoords" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div class="mb-4">
                <label for="height" class="block text-gray-700">Height (optional)</label>
                <input type="text" name="height" id="height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Property</button>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
