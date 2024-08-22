<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    $query = "UPDATE users SET name = :name, user_id = :user_id, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update user.']);
    }
}
?>
