<?php
// public/login_process.php
session_start();

// Include file koneksi database
include '../includes/db_connection.php';

// Ambil data dari form
$user_id = $_POST['user_id'];
$password = $_POST['password'];

// Query untuk mengambil data pengguna dari database
$query = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$query->execute(['user_id' => $user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Verifikasi pengguna dan password
if ($user && password_verify($password, $user['password'])) {
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    
    // Redirect berdasarkan role
    switch ($user['role']) {
        case 'admin':
            header('Location: ../admin/dashboard.php');
            break;
        case 'student':
            header('Location: ../student/dashboard.php');
            break;
        default:
            // Role tidak dikenali
            $_SESSION['login_error'] = 'Role tidak dikenali!';
            header('Location: ../public/login.php');
            exit();
    }
    exit();
} else {
    // Jika login gagal
    $_SESSION['login_error'] = 'ID Pengguna atau Password salah!';
    header('Location: ../public/login.php');
    exit();
}
?>
