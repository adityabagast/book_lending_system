<?php
// admin/dashboard.php
session_start();

// Cek jika pengguna sudah login dan adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/login.php');
    exit();
}

// Include file koneksi database
include '../includes/db_connection.php';

// Ambil data pengguna dari session
$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$query = $pdo->prepare("SELECT name FROM users WHERE user_id = :user_id");
$query->execute(['user_id' => $user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-900 text-gray-100 flex flex-col min-h-screen">
    <!-- Header termasuk navigation bar -->
    <?php include '../includes/header.php'; ?>

    <main class="flex-grow p-8">
        <div class="max-w-4xl mx-auto bg-gray-800 shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center">Admin Dashboard</h1>

            <div class="mb-6">
                <p class="text-xl font-semibold">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</p>
                <p class="text-lg">You are logged in as Admin.</p>
            </div>

            <div class="flex flex-col space-y-4">
                <a href="manage_books.php" class="block p-4 bg-blue-600 text-white rounded-md shadow-md hover:bg-blue-700">Manage Books</a>
                <a href="manage_users.php" class="block p-4 bg-green-600 text-white rounded-md shadow-md hover:bg-green-700">Manage Users</a>
                <a href="report.php" class="block p-4 bg-red-600 text-white rounded-md shadow-md hover:bg-red-700">View Reports</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
