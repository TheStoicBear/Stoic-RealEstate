<?php
$title = 'Register User';
include 'includes/header.php';
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Register</h2>
        <form action="process/process-register.php" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700">First Name</label>
                <input type="text" name="first_name" id="first_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-gray-700">Last Name</label>
                <input type="text" name="last_name" id="last_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700">Phone</label>
                <input type="text" name="phone" id="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Register</button>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
