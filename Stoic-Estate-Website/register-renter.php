<?php
$title = 'Register Renter';
include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $phone = $_POST['phone'];
    
    // Check if the email is already registered
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Email is already registered!</div>";
    } else {
        // Insert user data into the `users` table
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'tenant')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $name, $email, $password);
        
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Insert data into the `tenants` table
            $sql = "INSERT INTO tenants (user_id, name, phone) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iss', $user_id, $name, $phone);
            
            if ($stmt->execute()) {
                echo "<div class='bg-green-100 text-green-800 p-4 rounded-lg'>Account created successfully! You can now log in.</div>";
            } else {
                echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error creating tenant profile: " . $stmt->error . "</div>";
            }
        } else {
            echo "<div class='bg-red-100 text-red-800 p-4 rounded-lg'>Error creating user: " . $stmt->error . "</div>";
        }
    }
}
?>

<main class="max-w-lg mx-auto p-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Register as a Renter</h2>
        <form method="POST" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Register
            </button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
