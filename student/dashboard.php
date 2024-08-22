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

// Ambil daftar buku yang tersedia
$sql = "SELECT * FROM books WHERE available_copies > 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <!-- CDN Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 flex flex-col min-h-screen">
    <!-- Header termasuk navigation bar -->
    <?php include '../includes/header.php'; ?>

    <main class="flex-grow p-8">
        <div class="max-w-4xl mx-auto bg-gray-800 shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center">Dashboard Siswa</h1>

            <div class="mb-6">
                <p class="text-xl font-semibold">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</p>
                <p class="text-lg">You are logged in as Student.</p>
            </div>

            <div class="bg-gray-700 p-4 rounded shadow mb-4">
                <h2 class="text-xl font-semibold">Informasi Pengguna</h2>
                <p><strong>Nama:</strong> <?= htmlspecialchars($user['name']); ?></p>
                <p><strong>Username:</strong> <?= htmlspecialchars($user['user_id']); ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars($user['role']); ?></p>
            </div>

            <!-- Daftar Buku yang Tersedia -->
            <div class="bg-gray-700 p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Daftar Buku Tersedia</h2>

                <?php if (count($books) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($books as $book): ?>
                            <div class="bg-gray-600 p-4 rounded shadow">
                                <img src="../assets/images/uploads/<?= htmlspecialchars($book['cover_image']); ?>" alt="Cover Buku" class="w-full h-48 object-cover mb-2 rounded">
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($book['title']); ?></h3>
                                <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']); ?></p>
                                <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($book['publication_year']); ?></p>
                                <p><strong>Tersedia:</strong> <?= htmlspecialchars($book['available_copies']); ?> / <?= htmlspecialchars($book['total_copies']); ?></p>
                                <form action="../student/borrow_book_process.php" method="POST" class="mt-2">
                                    <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']); ?>">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Pinjam Buku</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Tidak ada buku yang tersedia saat ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (isset($_SESSION['borrow_error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '<?= $_SESSION['borrow_error']; ?>'
                });
                <?php unset($_SESSION['borrow_error']); ?>
            <?php elseif (isset($_SESSION['borrow_success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '<?= $_SESSION['borrow_success']; ?>'
                });
                <?php unset($_SESSION['borrow_success']); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
