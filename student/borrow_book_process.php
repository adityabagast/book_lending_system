<?php
session_start();
include '../includes/db_connection.php';

// Pastikan pengguna sudah login dan memiliki role 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../public/login.php');
    exit;
}

// Ambil data dari form
$book_id = $_POST['book_id'];
$user_id = $_SESSION['user_id'];
$borrow_date = date('Y-m-d');

// Validasi input
if (empty($book_id)) {
    $_SESSION['borrow_error'] = 'ID Buku tidak valid!';
    header('Location: ../student/dashboard.php');
    exit;
}

// Mulai transaksi
try {
    $pdo->beginTransaction();

    // Periksa apakah buku tersedia
    $stmt = $pdo->prepare("SELECT available_copies FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book || $book['available_copies'] <= 0) {
        throw new Exception('Buku tidak tersedia.');
    }

    // Insert ke tabel borrowings
    $stmt = $pdo->prepare("INSERT INTO borrowings (user_id, book_id, borrow_date) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $book_id, $borrow_date]);

    // Update jumlah salinan buku yang tersedia
    $stmt = $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id = ?");
    $stmt->execute([$book_id]);

    // Commit transaksi
    $pdo->commit();
    $_SESSION['borrow_success'] = 'Buku berhasil dipinjam!';
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['borrow_error'] = $e->getMessage();
}

header('Location: ../student/dashboard.php');
exit;
