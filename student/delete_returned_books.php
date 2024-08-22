<?php
session_start();
include '../includes/db_connection.php';

// Pastikan pengguna sudah login dan memiliki role 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../public/login.php');
    exit;
}

// Ambil data pengguna
$user_id = $_SESSION['user_id'];

// Mulai transaksi
try {
    $pdo->beginTransaction();

    // Hapus buku yang sudah dikembalikan
    $stmt = $pdo->prepare("DELETE FROM borrowings WHERE user_id = ? AND return_date IS NOT NULL");
    $stmt->execute([$user_id]);

    // Periksa jumlah baris yang dihapus
    if ($stmt->rowCount() > 0) {
        // Commit transaksi jika ada baris yang dihapus
        $pdo->commit();
        $_SESSION['return_message'] = 'Buku yang sudah dikembalikan berhasil dihapus dari daftar.';
    } else {
        // Tidak ada baris yang dihapus
        $pdo->rollBack();
        $_SESSION['return_message'] = 'Tidak ada buku yang dapat dihapus karena semua buku masih dipinjam.';
    }

    header('Location: ../student/borrowing.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['return_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
    header('Location: ../student/borrowing.php');
    exit;
}
?>
