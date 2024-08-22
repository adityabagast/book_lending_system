<?php
session_start();
include '../includes/db_connection.php'; // Pastikan path ini benar

if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    // Ambil data buku dari database
    $query = "SELECT * FROM books WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kirimkan data buku dalam format JSON
    echo json_encode($book);
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>
