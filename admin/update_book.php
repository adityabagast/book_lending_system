<?php
session_start();
include '../includes/db_connection.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category_id = $_POST['category_id'];
    $publication_year = $_POST['publication_year'];
    $available_copies = $_POST['available_copies'];
    $total_copies = $_POST['total_copies'];
    $cover_image = $_FILES['cover_image']['name'] ?? null;

    try {
        // Update book details
        $sql = "UPDATE books SET title = :title, author = :author, category_id = :category_id, 
                publication_year = :publication_year, available_copies = :available_copies, 
                total_copies = :total_copies" . ($cover_image ? ", cover_image = :cover_image" : "") . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':publication_year', $publication_year);
        $stmt->bindParam(':available_copies', $available_copies);
        $stmt->bindParam(':total_copies', $total_copies);
        $stmt->bindParam(':id', $id);

        if ($cover_image) {
            // Handle file upload
            $target_dir = "../assets/images/uploads/";
            $target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
            move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file);

            // Bind cover image parameter
            $stmt->bindParam(':cover_image', $cover_image);
        }

        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
