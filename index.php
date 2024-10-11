<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";  // Ganti dengan username MySQL Anda
$password = "";  // Ganti dengan password MySQL Anda
$dbname = "rfid_system";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mahasiswa = $_POST['nama_mahasiswa'];
    $nim = $_POST['nim'];
    $nomor_rfid = $_POST['nomor_rfid'];
    $nomor_loker = $_POST['nomor_loker'];
    $status = $_POST['status'];

    // Validasi
    if (empty($nama_mahasiswa) || empty($nim) || empty($nomor_rfid) || empty($nomor_loker) || empty($status)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    } else {
        // Menggunakan prepared statement untuk mencegah SQL injection
        $stmt = $conn->prepare("INSERT INTO rfid_cards (nama, nim, nomor_rfid, nomor_loker, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $nama_mahasiswa, $nim, $nomor_rfid, $nomor_loker, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Pendaftaran berhasil!');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Registration | Politeknik Negeri Lhokseumawe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Card Login -->
    <div class="card-container" id="login-card">
        <h2>RFID Registration</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa"
                    placeholder="Nama Mahasiswa">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="nomor_rfid" name="nomor_rfid" placeholder="Nomor Kartu">
            </div>
            <div class="mb-3">
                <input type="number" class="form-control" id="nomor_loker" name="nomor_loker" placeholder="Nomor Loker">
            </div>
            <!-- Tambahkan dropdown untuk status -->
            <div class="mb-3">
                <select class="form-control" id="status" name="status">
                    <option value="">Pilih Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Non-Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
            <!-- Tombol ke Dashboard -->
            <div style="margin-top: 10px;">
                <a href="dashboard.php" class="dashboard-btn">Ke Dashboard</a>
            </div>
        </form>
    </div>

    <!-- Logo Proyek -->
    <div class="logo-container">
        <img src="images/logo.png" alt="Logo Proyek" class="circle-avatar">
    </div>

    <script>
    // Animasi saat halaman dibuka
    window.onload = function() {
        document.getElementById("login-card").classList.add("show");
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</body>

</html>