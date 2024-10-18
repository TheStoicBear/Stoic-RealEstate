<?php

$title = 'Tenant Login';
include 'includes/header.php';  // Include header with navigation

if (isset($_SESSION['tenant_id'])) {
    header('Location: tenant-dashboard.php');  // Redirect if already logged in
    exit();
}

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare and execute the SQL statement
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'tenant'";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $tenant = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $tenant['password'])) {
            // Store tenant data in session
            $_SESSION['tenant_id'] = $tenant['id'];
            $_SESSION['tenant_name'] = $tenant['name'];

            header('Location: tenant-dashboard.php');  // Redirect to tenant dashboard
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<main>
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Tenant Login</h2>

        <?php if ($error): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Login</button>
        </form>

        <p class="mt-4 text-sm">
            Don't have an account? <a href="register-renter.php" class="text-blue-600">Register here</a>.
        </p>
    </section>
</main>

<?php include 'includes/footer.php'; // Include footer ?>
