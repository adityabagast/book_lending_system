<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Navigation Bar</title>
</head>
<body>
    <nav class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex-1 flex items-center sm:items-stretch">
                <div class="flex-shrink-0">
                        <?php
                        $dashboardLink = 'index.php'; // Default link

                        if (isset($_SESSION['role'])) {
                            if ($_SESSION['role'] === 'admin') {
                                $dashboardLink = '../admin/dashboard.php';
                            } elseif ($_SESSION['role'] === 'siswa') {
                                $dashboardLink = '../student/dashboard.php';
                            }
                        }
                        ?>
                        <a href="<?php echo htmlspecialchars($dashboardLink); ?>" class="text-xl font-bold">Book Lending System</a>
                    </div>
                </div>
                <div class="hidden sm:flex sm:space-x-4">
                    <?php if (isset($_SESSION['role'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <!-- Admin Links -->
                            <div class="flex space-x-4">
                                <a href="../admin/dashboard.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                <a href="../admin/manage_books.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Manage Books</a>
                                <a href="../admin/manage_users.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Manage Users</a>
                                <a href="../admin/report.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Reports</a>
                            </div>
                        <?php elseif ($_SESSION['role'] === 'student'): ?>
                            <!-- Siswa Links -->
                            <div class="flex space-x-4">
                                <a href="../student/dashboard.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                <a href="../student/book_list.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Book List</a>
                                <a href="../student/borrowing.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Borrowing</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="../logout.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</body>
</html>
