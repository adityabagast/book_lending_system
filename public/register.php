<!-- register.php -->
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Register</title>
    <style>
        /* Custom dark mode settings */
        body {
            background-color: #1f2937; /* Dark background color */
            color: #f9fafb; /* Light text color */
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-900 text-gray-100">
    <div class="flex-grow flex items-center justify-center mb-8 mt-8"> <!-- Add margin-bottom -->
        <div class="w-full max-w-md p-8 bg-gray-800 shadow-md rounded-lg">
            <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>

            <?php
            if (isset($_SESSION['register_error'])) {
                echo '<p class="text-red-400 text-center mb-4">' . $_SESSION['register_error'] . '</p>';
                unset($_SESSION['register_error']);
            }
            if (isset($_SESSION['register_success'])) {
                echo '<p class="text-green-400 text-center mb-4">' . $_SESSION['register_success'] . '</p>';
                unset($_SESSION['register_success']);
            }
            ?>

            <form action="register_process.php" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-300">Nama</label>
                    <input type="text" id="name" name="name" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-300">Username</label>
                    <input type="text" id="user_id" name="user_id" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    <input type="password" id="password" name="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-300">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-300">Role</label>
                    <select id="role" name="role" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Register
                </button>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-400">Sudah punya akun? <a href="login.php" class="text-blue-400 hover:text-blue-500">Login sekarang</a></p>
                </div>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
