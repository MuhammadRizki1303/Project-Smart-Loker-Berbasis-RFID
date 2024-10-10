<?php
// Informasi koneksi database
$servername = "localhost";
$username = "root";  // Ganti dengan username MySQL Anda
$password = "";  // Ganti dengan password MySQL Anda

// Membuat koneksi ke server MySQL
$conn = new mysqli($servername, $username, $password);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Membuat database
$sql = "CREATE DATABASE IF NOT EXISTS rfid_system";
if ($conn->query($sql) === TRUE) {
    echo "Database rfid_system berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error membuat database: " . $conn->error;
}

// Menggunakan database rfid_system
$conn->select_db("rfid_system");

// Membuat tabel rfid_cards
$sql = "CREATE TABLE IF NOT EXISTS rfid_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    nomor_rfid VARCHAR(50) NOT NULL,
    nomor_loker INT NOT NULL,
    status ENUM('Digunakan', 'Tidak Digunakan') DEFAULT 'Tidak Digunakan'
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabel rfid_cards berhasil dibuat atau sudah ada.";
} else {
    echo "Error membuat tabel: " . $conn->error;
}

// Menutup koneksi
$conn->close();