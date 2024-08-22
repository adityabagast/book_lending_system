<?php
session_start();
include '../includes/db_connection.php';

// Pastikan pengguna sudah login dan memiliki role 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../public/login.php');
    exit;
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}

// Ambil daftar buku yang dipinjam oleh pengguna
$sql = "SELECT b.title, b.author, b.cover_image, br.id as borrow_id, br.borrow_date, br.return_date 
        FROM borrowings br
        JOIN books b ON br.book_id = b.id
        WHERE br.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku yang Dipinjam</title>
    <!-- CDN Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 flex flex-col min-h-screen">
    <!-- Header termasuk navigation bar -->
    <?php include '../includes/header.php'; ?>

    <main class="flex-grow p-8">
        <div class="max-w-4xl mx-auto bg-gray-800 shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center">Buku yang Dipinjam</h1>

            <div class="bg-gray-700 p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Daftar Buku yang Dipinjam</h2>

                <?php if (count($borrowed_books) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($borrowed_books as $book): ?>
                            <div class="bg-gray-600 p-4 rounded shadow">
                                <img src="../assets/images/uploads/<?= htmlspecialchars($book['cover_image']); ?>" alt="Cover Buku" class="w-full h-48 object-cover mb-2 rounded">
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($book['title']); ?></h3>
                                <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']); ?></p>
                                <p><strong>Tanggal Pinjam:</strong> <?= htmlspecialchars($book['borrow_date']); ?></p>
                                <p><strong>Tanggal Kembali:</strong> <?= htmlspecialchars($book['return_date']); ?></p>
                                <?php if (empty($book['return_date'])): ?>
                                    <form action="../student/return_book_process.php" method="POST" class="mt-2">
                                        <input type="hidden" name="borrow_id" value="<?= htmlspecialchars($book['borrow_id']); ?>">
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Kembalikan Buku</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Button untuk menghapus buku yang sudah dikembalikan -->
                    <form action="../student/delete_returned_books.php" method="POST" class="mt-6">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Hapus Buku yang Sudah Dikembalikan</button>
                    </form>
                <?php else: ?>
                    <p>Anda belum meminjam buku apapun.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- CDN SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_SESSION['return_message'])): ?>
        <script>
            Swal.fire({
                title: '<?= htmlspecialchars($_SESSION['return_message']); ?>',
                icon: '<?= isset($_SESSION['return_message']) && strpos($_SESSION['return_message'], 'Terjadi kesalahan') !== false ? 'error' : 'success'; ?>'
            });
            <?php unset($_SESSION['return_message']); ?>
        </script>
    <?php endif; ?>
</body>
</html>
