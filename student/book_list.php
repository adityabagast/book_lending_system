<?php
session_start();
include '../includes/db_connection.php';

// Pastikan pengguna sudah login dan memiliki role 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../public/login.php');
    exit;
}

// Ambil daftar semua buku dari database
$sql = "SELECT * FROM books";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <!-- CDN Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 flex flex-col min-h-screen">
    <!-- Header termasuk navigation bar -->
    <?php include '../includes/header.php'; ?>

    <main class="flex-grow p-8">
        <div class="max-w-4xl mx-auto bg-gray-800 shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center">Daftar Buku</h1>

            <!-- Tabel Daftar Buku -->
            <div class="bg-gray-700 p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Semua Buku</h2>

                <?php if (count($books) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($books as $book): ?>
                            <div class="bg-gray-600 p-4 rounded shadow">
                                <img src="../assets/images/uploads/<?= htmlspecialchars($book['cover_image']); ?>" alt="Cover Buku" class="w-full h-48 object-cover mb-2 rounded">
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($book['title']); ?></h3>
                                <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']); ?></p>
                                <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($book['publication_year']); ?></p>
                                <p><strong>Total Salinan:</strong> <?= htmlspecialchars($book['total_copies']); ?></p>
                                <p><strong>Salinan Tersedia:</strong> <?= htmlspecialchars($book['available_copies']); ?></p>
                                <?php if ($book['available_copies'] > 0): ?>
                                    <form action="../student/borrow_book_process.php" method="POST" class="mt-2">
                                        <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']); ?>">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Pinjam Buku</button>
                                    </form>
                                <?php else: ?>
                                    <p class="text-red-500 mt-2">Buku ini tidak tersedia saat ini.</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Tidak ada buku yang terdaftar saat ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- CDN SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_SESSION['borrow_message'])): ?>
        <script>
            Swal.fire({
                title: '<?= htmlspecialchars($_SESSION['borrow_message']); ?>',
                icon: 'success'
            });
            <?php unset($_SESSION['borrow_message']); ?>
        </script>
    <?php endif; ?>
</body>
</html>
