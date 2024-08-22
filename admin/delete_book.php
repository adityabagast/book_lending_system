<?php
session_start();
include '../includes/db_connection.php'; // Ensure this path is correct

// Get book ID from POST request
$book_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($book_id > 0) {
    try {
        // Check if the book is currently borrowed
        $checkQuery = "SELECT COUNT(*) FROM borrowings WHERE book_id = :book_id";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $checkStmt->execute();
        $borrowCount = $checkStmt->fetchColumn();

        if ($borrowCount > 0) {
            // If the book is borrowed, return an error message
            echo json_encode(['success' => false, 'message' => 'tidak bisa menghapus buku karena buku sedang dipinjam.']);
        } else {
            // If the book is not borrowed, proceed with deletion
            $deleteQuery = "DELETE FROM books WHERE id = :book_id";
            $deleteStmt = $pdo->prepare($deleteQuery);
            $deleteStmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $deleteStmt->execute();

            echo json_encode(['success' => true, 'message' => 'The book has been deleted.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
}
?>
