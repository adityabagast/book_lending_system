<?php
// register_process.php
session_start();
include '../includes/db_connection.php'; // Sertakan koneksi database

// Ambil data dari form
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$name = $_POST['name']; // Nama pengguna
$role = $_POST['role']; // Role pengguna

// Validasi password
if ($password !== $confirm_password) {
    $_SESSION['register_error'] = 'Password dan konfirmasi password tidak cocok.';
    header('Location: register.php');
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Query untuk memeriksa ID pengguna
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // ID pengguna sudah ada
    $_SESSION['register_error'] = 'ID Pengguna sudah terdaftar.';
    header('Location: register.php');
    exit;
}

// Query untuk memasukkan data pengguna baru
$sql = "INSERT INTO users (name, user_id, password, role) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $user_id, $hashed_password, $role]);

if ($stmt) {
    // Registrasi berhasil
    $_SESSION['register_success'] = 'Pendaftaran berhasil, silakan login.';
    header('Location: login.php');
    exit;
} else {
    // Registrasi gagal
    $_SESSION['register_error'] = 'Terjadi kesalahan saat pendaftaran.';
    header('Location: register.php');
    exit;
}
?>
