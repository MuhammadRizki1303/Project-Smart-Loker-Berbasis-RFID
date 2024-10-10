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

    // Validasi
    if (empty($nama_mahasiswa) || empty($nim) || empty($nomor_rfid) || empty($nomor_loker)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    } else {
        // Menggunakan prepared statement untuk mencegah SQL injection
        $stmt = $conn->prepare("INSERT INTO rfid_cards (nama, nim, nomor_rfid, nomor_loker) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nama_mahasiswa, $nim, $nomor_rfid, $nomor_loker);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Tema warna dan background */
        body {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(45deg, #ff7f50, #6a5acd, #ffffff);
            background-size: 300% 300%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Card login */
        .card-container {
            width: 350px;
            padding: 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, opacity 0.3s ease;
            position: absolute;
            left: 17%;
            opacity: 0;
            transform: translateY(-20px);
        }

        /* Animasi masuk */
        .card-container.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Animasi hover */
        .card-container:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background-color: #6a5acd;
            border-color: #6a5acd;
        }

        .btn-primary:hover {
            background-color: #ff7f50;
            border-color: #ff7f50;
        }

        .btn-dashboard {
            background-color: #ff7f50;
            border-color: #ff7f50;
            margin-top: 10px;
        }

        .btn-dashboard:hover {
            background-color: #6a5acd;
            border-color: #6a5acd;
        }

        h2 {
            color: #6a5acd;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Style logo */
        .logo-container {
            position: absolute;
            right: 20%;
            top: 50%;
            transform: translateY(-50%);
        }

        .logo-container img {
            max-width: 200px;
            max-height: 200px;
        }
    </style>
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
            <button type="submit" class="btn btn-primary">Daftar</button>
            <!-- Tombol ke Dashboard -->
            <a href="dashboard.php" class="btn btn-dashboard btn-block">Ke Dashboard</a>
        </form>
    </div>

    <!-- Logo Proyek -->
    <div class="logo-container">
        <img src="images/logo.png" alt="Logo Proyek">
    </div>

    <script>
        // Animasi saat halaman dibuka
        window.onload = function () {
            document.getElementById("login-card").classList.add("show");
        }
    </script>
</body>

</html>