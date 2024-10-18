<?php
$title = 'Edit Property';
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
$property_id = intval($_GET['id']);

// Fetch property details
$sql = "SELECT * FROM properties WHERE id = ? AND agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $property_id, $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$property = $result->fetch_assoc();

// Fetch garage details
$garage_sql = "SELECT * FROM garages WHERE property_id = ?";
$garage_stmt = $conn->prepare($garage_sql);
$garage_stmt->bind_param('i', $property_id);
$garage_stmt->execute();
$garage_result = $garage_stmt->get_result();
$garage = $garage_result->fetch_assoc();
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Edit Property</h2>
        <img src="<?php echo $agent['profile_picture'] ? htmlspecialchars($agent['profile_picture']) : 'https://via.placeholder.com/150'; ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover mr-4">
        <div>
            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($agent['name']); ?></h3>
            <p class="text-gray-600"><?php echo htmlspecialchars($agent['email']); ?></p>
        </div>
        
        <?php if ($property): ?>
        <form action="process/process-edit-property.php" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            <input type="hidden" name="id" value="<?php echo $property['id']; ?>">
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($property['title']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700">Image URL</label>
                <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($property['image']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required><?php echo htmlspecialchars($property['description']); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="beds" class="block text-gray-700">Beds</label>
                <input type="number" name="beds" id="beds" value="<?php echo htmlspecialchars($property['beds']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="baths" class="block text-gray-700">Baths</label>
                <input type="number" name="baths" id="baths" value="<?php echo htmlspecialchars($property['baths']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="sq_ft" class="block text-gray-700">Square Feet</label>
                <input type="number" name="sq_ft" id="sq_ft" value="<?php echo htmlspecialchars($property['sq_ft']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="cost" class="block text-gray-700">Cost</label>
                <input type="number" name="cost" id="cost" value="<?php echo htmlspecialchars($property['cost']); ?>" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="city" class="block text-gray-700">City</label>
                <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($property['city']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="state" class="block text-gray-700">State</label>
                <input type="text" name="state" id="state" value="<?php echo htmlspecialchars($property['state']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="postal" class="block text-gray-700">Postal Code</label>
                <input type="text" name="postal" id="postal" value="<?php echo htmlspecialchars($property['postal']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="latitude" class="block text-gray-700">Latitude</label>
                <input type="text" name="latitude" id="latitude" value="<?php echo htmlspecialchars($property['latitude']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="longitude" class="block text-gray-700">Longitude</label>
                <input type="text" name="longitude" id="longitude" value="<?php echo htmlspecialchars($property['longitude']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="altitude" class="block text-gray-700">Altitude (optional)</label>
                <input type="text" name="altitude" id="altitude" value="<?php echo htmlspecialchars($property['altitude']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="interiorMap" class="block text-gray-700">Interior Map</label>
                <input type="text" name="interiorMap" id="interiorMap" value="<?php echo htmlspecialchars($property['interiorMap']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="interiorCoords" class="block text-gray-700">Interior Coordinates (JSON format)</label>
                <textarea name="interiorCoords" id="interiorCoords" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?php echo htmlspecialchars($property['interiorCoords']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="outsideCoords" class="block text-gray-700">Outside Coordinates (JSON format)</label>
                <textarea name="outsideCoords" id="outsideCoords" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?php echo htmlspecialchars($property['outsideCoords']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="height" class="block text-gray-700">Height (optional)</label>
                <input type="text" name="height" id="height" value="<?php echo htmlspecialchars($property['height']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <!-- Garage Information -->
            <h3 class="text-xl font-semibold mb-4">Garage Information</h3>
            <div class="mb-4">
                <label for="garage_location" class="block text-gray-700">Garage Location</label>
                <input type="text" name="garage_location" id="garage_location" value="<?php echo htmlspecialchars($garage['location']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="garage_latitude" class="block text-gray-700">Garage Latitude</label>
                <input type="text" name="garage_latitude" id="garage_latitude" value="<?php echo htmlspecialchars($garage['latitude']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="garage_longitude" class="block text-gray-700">Garage Longitude</label>
                <input type="text" name="garage_longitude" id="garage_longitude" value="<?php echo htmlspecialchars($garage['longitude']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="garage_capacity" class="block text-gray-700">Garage Capacity</label>
                <input type="number" name="garage_capacity" id="garage_capacity" value="<?php echo htmlspecialchars($garage['capacity']); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="garage_altitude" class="block text-gray-700">Garage Altitude (optional)</label>
                <input type="text" name="garage_altitude" id="garage_altitude" value="<?php echo htmlspecialchars($garage['altitude']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <button type="submit" class="mt-4 bg-blue-500 text-white rounded-md px-4 py-2">Update Property</button>
        </form>
        <?php else: ?>
        <p class="text-red-500">Property not found or you do not have permission to edit this property.</p>
        <?php endif; ?>
    </section>
</main>

<?php
include 'includes/footer.php';
?>
