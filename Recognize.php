<?php
session_start();

// Pastikan pengguna telah login dan ada sesi aktif
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Jalankan skrip Python untuk pengenalan wajah
$output = shell_exec("python capture_and_authenticate.py");

// Periksa apakah hasil pengenalan wajah berhasil
if (trim($output) === "Login Successful") {
    // Jika berhasil, arahkan ke halaman input
    header("Location: http://localhost/Project-Smart-Loker-Berbasis-RFID/input.php");
    exit();
} else {
    // Jika gagal, arahkan kembali ke login dengan pesan kesalahan
    $_SESSION['error_message'] = "Pengenalan wajah gagal. Silakan coba lagi.";
    header("Location: login.php");
    exit();
}
