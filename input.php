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
    $status = $_POST['status'];  // Ambil nilai status dari dropdown
    $nomor_hp = $_POST['nomor_hp'];  // Nomor HP

    // Validasi input
    if (empty($nama_mahasiswa) || empty($nim) || empty($nomor_rfid) || empty($nomor_loker) || $status == "" || empty($nomor_hp)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    } else {
        // Menggunakan prepared statement untuk mencegah SQL injection
        $stmt = $conn->prepare("INSERT INTO rfid_cards (nama, nim, nomor_rfid, nomor_loker, status, nomor_hp) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisi", $nama_mahasiswa, $nim, $nomor_rfid, $nomor_loker, $status, $nomor_hp);

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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body>
    <div class="col-sm-6 col-lg-2 card-container" id="login-card">
        <h2 class="gradient-title">RFID Registration</h2>
        <form class="form-container" action="" method="POST">
            <div class="mb-3 form-group floating-label">
                <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" placeholder=""
                    required>
                <label for="nama">Nama</label>
            </div>
            <div class="mb-3 form-group floating-label">
                <input type="text" class="form-control" id="nim" name="nim" placeholder="" required>
                <label for="nim">NIM</label>
            </div>
            <div class="mb-3 form-group floating-label">
                <input type="text" class="form-control" id="nomor_rfid" name="nomor_rfid" placeholder="" required>
                <label for="nomor-kartu">No Kartu</label>
            </div>
            <div class="mb-3 form-group floating-label">
                <input type="number" class="form-control" id="nomor_loker" name="nomor_loker" placeholder="" required>
                <label for="nomor-loker">No Loker</label>
            </div>
            <!-- Dropdown untuk Status -->
            <div class="mb-3 form-group floating-label">
                <select class="form-select" id="status" name="status" required>
                    <option value="" disabled selected></option>
                    <option value="1">Aktif</option>
                    <option value="0">Non-Aktif</option>
                </select>
                <label for="status">Status</label>
            </div>

            <!-- Kolom untuk Nomor HP -->
            <div class="mb-3 form-group floating-label">
                <input type="text" class="form-control" id="nomor-hp" name="nomor_hp" placeholder="" required>
                <label for="nomor-hp">No HP</label>
            </div>

            <!-- Tombol ke Dashboard -->
            <div class="button-group" style="margin-top: 10px;">
                <button type="submit" class="col-6 btn btn-purple">Add</button>
                <a type="button" href="dashboard.php" class="col-6 btn btn-orange">List</a>
            </div>
        </form>
    </div>

    <!-- Logo Proyek -->
    <div class="col-sm-6 col-lg-2 logo-container">
        <img src="images/logo.png" alt="Logo Proyek" class="rotate-scale-up circle-avatar">
        <h2 class="project-title">Smart Locker</h2>
    </div>

    <script>
        // Animasi saat halaman dibuka
        window.onload = function () {
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