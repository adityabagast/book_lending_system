<?php
session_start();
include '../includes/db_connection.php'; // Ensure this path is correct

// Fetch users data from the database
$query = "SELECT * FROM users";
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Manage Users</title>
    <style>
        body {
            background-color: #1f2937;
            color: #f9fafb;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <?php include '../includes/header.php'; ?>

    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-4">Manage Users</h1>
            <button id="addUserBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700">Add New User</button>

            <table id="usersTable" class="mt-4 w-full border border-gray-700">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="px-4 py-2 border-b border-gray-600">User ID</th>
                        <th class="px-4 py-2 border-b border-gray-600">Name</th>
                        <th class="px-4 py-2 border-b border-gray-600">Username</th>
                        <th class="px-4 py-2 border-b border-gray-600">Role</th>
                        <th class="px-4 py-2 border-b border-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="bg-gray-700">
                            <td class="px-4 py-2 border-b border-gray-600 text-center"><?php echo htmlspecialchars($user['id']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600 text-center""><?php echo htmlspecialchars($user['name']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600 text-center""><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600 text-center""><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600 text-center"">
                                <button class="edit-btn text-blue-600 hover:text-blue-800" data-id="<?php echo htmlspecialchars($user['id']); ?>">Edit</button>
                                <button class="delete-btn text-red-600 hover:text-red-800" data-id="<?php echo htmlspecialchars($user['id']); ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-gray-800 p-6 rounded-md shadow-lg w-full max-w-lg max-h-[90vh] overflow-auto">
            <h2 class="text-xl font-bold mb-4">Add New User</h2>
            <form id="addUserForm">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                    <input type="text" id="name" name="name" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-300">User ID</label>
                    <input type="text" id="user_id" name="user_id" required
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
                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">Save</button>
            </form>
            <button id="closeAddUserModal" class="mt-4 w-full text-red-500 hover:text-red-700">Close</button>
        </div>
    </div>

    <!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-gray-800 p-6 rounded-md shadow-lg w-full max-w-lg max-h-[90vh] overflow-auto">
        <h2 class="text-xl font-bold mb-4">Edit User</h2>
        <form id="editUserForm">
            <input type="hidden" id="edit_user_id" name="id">
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-300">Name</label>
                <input type="text" id="edit_name" name="name" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="edit_user_id_input" class="block text-sm font-medium text-gray-300">User ID</label>
                <input type="text" id="edit_user_id_input" name="user_id" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="edit_role" class="block text-sm font-medium text-gray-300">Role</label>
                <select id="edit_role" name="role" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">Save</button>
        </form>
        <button id="closeEditUserModal" class="mt-4 w-full text-red-500 hover:text-red-700">Close</button>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const addUserBtn = document.getElementById('addUserBtn');
    const addUserModal = document.getElementById('addUserModal');
    const closeAddUserModal = document.getElementById('closeAddUserModal');
    const editUserModal = document.getElementById('editUserModal');
    const closeEditUserModal = document.getElementById('closeEditUserModal');
    const editButtons = document.querySelectorAll('.edit-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');

    // Open Add User Modal
    addUserBtn.addEventListener('click', () => {
        addUserModal.classList.remove('hidden');
    });

    // Close Add User Modal
    closeAddUserModal.addEventListener('click', () => {
        addUserModal.classList.add('hidden');
    });

    // Open Edit User Modal
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');

            // Get user data using AJAX
            fetch(`get_user.php?id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                    } else {
                        // Fill modal with user data
                        document.getElementById('edit_user_id').value = data.id;
                        document.getElementById('edit_name').value = data.name;
                        document.getElementById('edit_user_id_input').value = data.user_id;
                        document.getElementById('edit_role').value = data.role;
                        editUserModal.classList.remove('hidden');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Close Edit User Modal
    closeEditUserModal.addEventListener('click', () => {
        editUserModal.classList.add('hidden');
    });

    // Handle Add User Form Submission
    document.getElementById('addUserForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('add_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', 'User added successfully!', 'success');
                addUserModal.classList.add('hidden');
                location.reload(); // Reload page to reflect changes
            } else {
                Swal.fire('Error', data.error, 'error');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle Edit User Form Submission
    document.getElementById('editUserForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('update_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', 'User updated successfully!', 'success');
                editUserModal.classList.add('hidden');
                location.reload(); // Reload page to reflect changes
            } else {
                Swal.fire('Error', data.error, 'error');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle Delete User
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({ action: 'delete', id: userId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', 'The user has been deleted.', 'success');
                            location.reload(); // Reload page to reflect changes
                        } else {
                            Swal.fire('Error', data.error, 'error');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });
});

    </script>
</body>
</html>
