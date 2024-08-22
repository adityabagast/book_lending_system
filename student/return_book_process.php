<?php
// public/return_book_process.php
session_start();
include '../includes/db_connection.php';

// Pastikan pengguna sudah login dan memiliki role 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../public/login.php');
    exit;
}

// Ambil data dari form
$borrow_id = $_POST['borrow_id'];
$user_id = $_SESSION['user_id'];
$return_date = date('Y-m-d');

// Validasi input
if (empty($borrow_id)) {
    $_SESSION['return_message'] = 'ID Pinjaman tidak valid!';
    header('Location: ../student/borrowing.php');
    exit;
}

// Mulai transaksi
try {
    $pdo->beginTransaction();

    // Periksa apakah pinjaman ada dan milik pengguna
    $stmt = $pdo->prepare("SELECT book_id FROM borrowings WHERE id = ? AND user_id = ? AND return_date IS NULL");
    $stmt->execute([$borrow_id, $user_id]);
    $borrow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$borrow) {
        throw new Exception('Pinjaman tidak ditemukan atau sudah dikembalikan.');
    }

    // Update tabel borrowings dengan tanggal kembali
    $stmt = $pdo->prepare("UPDATE borrowings SET return_date = ? WHERE id = ?");
    $stmt->execute([$return_date, $borrow_id]);

    // Update jumlah salinan buku yang tersedia
    $stmt = $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
    $stmt->execute([$borrow['book_id']]);

    // Commit transaksi
    $pdo->commit();
    $_SESSION['return_message'] = 'Buku berhasil dikembalikan.';
    header('Location: ../student/borrowing.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['return_message'] = $e->getMessage();
    header('Location: ../student/borrowing.php');
    exit;
}
?>
