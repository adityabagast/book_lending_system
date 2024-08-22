<?php
session_start();
include '../includes/db_connection.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    try {
        $sql = "INSERT INTO users (name, user_id, role) VALUES (:name, :user_id, :role)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':role', $role);

        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
