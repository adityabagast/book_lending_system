<?php
session_start();
include '../includes/db_connection.php'; // Ensure this path is correct

// Fetch book data from the database
$query = "SELECT * FROM books";
$stmt = $pdo->prepare($query);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories from the database
$query = "SELECT * FROM categories";
$stmt = $pdo->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Manage Books</title>
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
            <h1 class="text-2xl font-bold mb-4">Manage Books</h1>
            <button id="addBookBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700">Add New Book</button>

            <table id="booksTable" class="mt-4 w-full border border-gray-700">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="px-4 py-2 border-b border-gray-600">Book ID</th>
                        <th class="px-4 py-2 border-b border-gray-600">Cover</th>
                        <th class="px-4 py-2 border-b border-gray-600">Title</th>
                        <th class="px-4 py-2 border-b border-gray-600">Author</th>
                        <th class="px-4 py-2 border-b border-gray-600">Category ID</th>
                        <th class="px-4 py-2 border-b border-gray-600">Publication Year</th>
                        <th class="px-4 py-2 border-b border-gray-600">Available Copies</th>
                        <th class="px-4 py-2 border-b border-gray-600">Total Copies</th>
                        <th class="px-4 py-2 border-b border-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr class="bg-gray-700">
                            <td class="px-4 py-2 border-b border-gray-600"><?php echo htmlspecialchars($book['id']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600">
                                <?php if ($book['cover_image']): ?>
                                    <img src="../assets/images/uploads/<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Cover" class="w-20 h-auto">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 border-b border-gray-600"><?php echo htmlspecialchars($book['title']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600"><?php echo htmlspecialchars($book['author']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600"><?php echo htmlspecialchars($book['category_id']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600"><?php echo htmlspecialchars($book['publication_year']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600"><?php echo htmlspecialchars($book['available_copies']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600"><?php echo htmlspecialchars($book['total_copies']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600">
                                <button class="edit-btn text-blue-600 hover:text-blue-800" data-id="<?php echo htmlspecialchars($book['id']); ?>">Edit</button>
                                <button class="delete-btn text-red-600 hover:text-red-800" data-id="<?php echo htmlspecialchars($book['id']); ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Add Book Modal -->
    <div id="addBookModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-gray-800 p-6 rounded-md shadow-lg w-full max-w-lg max-h-[90vh] overflow-auto">
            <h2 class="text-xl font-bold mb-4">Add New Book</h2>
            <form id="addBookForm" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-300">Title</label>
                    <input type="text" id="title" name="title" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="author" class="block text-sm font-medium text-gray-300">Author</label>
                    <input type="text" id="author" name="author" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-300">Category</label>
                    <select id="category_id" name="category_id" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="publication_year" class="block text-sm font-medium text-gray-300">Publication Year</label>
                    <input type="text" id="publication_year" name="publication_year" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="available_copies" class="block text-sm font-medium text-gray-300">Available Copies</label>
                    <input type="number" id="available_copies" name="available_copies" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="total_copies" class="block text-sm font-medium text-gray-300">Total Copies</label>
                    <input type="number" id="total_copies" name="total_copies" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="cover_image" class="block text-sm font-medium text-gray-300">Cover Image</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">Save</button>
            </form>
            <button id="closeAddBookModal" class="mt-4 w-full text-red-500 hover:text-red-700">Close</button>
        </div>
    </div>

    <!-- Edit Book Modal -->
    <div id="editBookModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-gray-800 p-6 rounded-md shadow-lg w-full max-w-lg max-h-[90vh] overflow-auto">
            <h2 class="text-xl font-bold mb-4">Edit Book</h2>
            <form id="editBookForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_book_id" name="id">
                <div class="mb-4">
                    <label for="edit_title" class="block text-sm font-medium text-gray-300">Title</label>
                    <input type="text" id="edit_title" name="title" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="edit_author" class="block text-sm font-medium text-gray-300">Author</label>
                    <input type="text" id="edit_author" name="author" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="edit_category_id" class="block text-sm font-medium text-gray-300">Category</label>
                    <select id="edit_category_id" name="category_id" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="edit_publication_year" class="block text-sm font-medium text-gray-300">Publication Year</label>
                    <input type="text" id="edit_publication_year" name="publication_year" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="edit_available_copies" class="block text-sm font-medium text-gray-300">Available Copies</label>
                    <input type="number" id="edit_available_copies" name="available_copies" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="edit_total_copies" class="block text-sm font-medium text-gray-300">Total Copies</label>
                    <input type="number" id="edit_total_copies" name="total_copies" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="edit_cover_image" class="block text-sm font-medium text-gray-300">Cover Image</label>
                    <input type="file" id="edit_cover_image" name="cover_image" accept="image/*"
                           class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">Save</button>
            </form>
            <button id="closeEditBookModal" class="mt-4 w-full text-red-500 hover:text-red-700">Close</button>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const addBookBtn = document.getElementById('addBookBtn');
        const addBookModal = document.getElementById('addBookModal');
        const closeAddBookModal = document.getElementById('closeAddBookModal');
        const editBookModal = document.getElementById('editBookModal');
        const closeEditBookModal = document.getElementById('closeEditBookModal');
        const editButtons = document.querySelectorAll('.edit-btn');
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const addBookForm = document.getElementById('addBookForm');
        const editBookForm = document.getElementById('editBookForm');

        // Open Add Book Modal
        addBookBtn.addEventListener('click', () => {
            addBookModal.classList.remove('hidden');
        });

        // Close Add Book Modal
        closeAddBookModal.addEventListener('click', () => {
            addBookModal.classList.add('hidden');
        });

        // Open Edit Book Modal
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const bookId = this.getAttribute('data-id');

                // Ambil data buku menggunakan AJAX
                fetch(`get_book.php?id=${bookId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                        } else {
                            // Isi modal dengan data buku
                            document.getElementById('edit_book_id').value = data.id;
                            document.getElementById('edit_title').value = data.title;
                            document.getElementById('edit_author').value = data.author;
                            document.getElementById('edit_category_id').value = data.category_id;
                            document.getElementById('edit_publication_year').value = data.publication_year;
                            document.getElementById('edit_available_copies').value = data.available_copies;
                            document.getElementById('edit_total_copies').value = data.total_copies;
                            editBookModal.classList.remove('hidden');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Close Edit Book Modal
        closeEditBookModal.addEventListener('click', () => {
            editBookModal.classList.add('hidden');
        });

        // Handle Add Book Form Submission
        addBookForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(addBookForm);

            fetch('add_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', 'Book added successfully!', 'success');
                    addBookModal.classList.add('hidden');
                    location.reload(); // Reload page to reflect changes
                } else {
                    Swal.fire('Error', data.error, 'error');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Handle Edit Book Form Submission
        editBookForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(editBookForm);

            fetch('update_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', 'Book updated successfully!', 'success');
                    editBookModal.classList.add('hidden');
                    location.reload(); // Reload page to reflect changes
                } else {
                    Swal.fire('Error', data.error, 'error');
                }
            })
            .catch(error => console.error('Error:', error));
        });

// Handle Delete Book
deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
        const bookId = this.getAttribute('data-id');

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
                fetch('delete_book.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ id: bookId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success');
                        location.reload(); // Reload page to reflect changes
                    } else {
                        Swal.fire('Error', data.message || 'An error occurred.', 'error');
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
