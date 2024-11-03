<?php
session_start();

// Pastikan username ada di sesi
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Jalankan skrip Python untuk pengenalan wajah
$username = $_SESSION['username'];
$output = shell_exec("python Recognize.py");

// Periksa apakah hasil pengenalan wajah sesuai dengan username
if (trim($output) === $username) {
    // Jika cocok, arahkan ke halaman input
    header("Location: input.php");
    exit();
} else {
    // Jika tidak cocok, tampilkan pesan kesalahan
    echo "Pengenalan wajah gagal. Silakan coba lagi.";
}
