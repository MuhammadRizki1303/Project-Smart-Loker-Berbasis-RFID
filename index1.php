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
        echo "Semua kolom harus diisi!";
    } else {
        // Insert data ke database
        $sql = "INSERT INTO rfid_cards (nama, nim, nomor_rfid, nomor_loker) 
                VALUES ('$nama_mahasiswa', '$nim', '$nomor_rfid', '$nomor_loker')";

        if ($conn->query($sql) === TRUE) {
            echo "Pendaftaran berhasil!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
    body {
        background-color: #1a0a3d;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .card-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 400px;
        text-align: center;
    }

    .btn-primary {
        width: 100%;
        padding: 10px;
        font-size: 1.2em;
    }
    </style>
</head>

<body>
    <div class="card-container">
        <form action="" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa"
                    placeholder="Nama Mahasiswa">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="nomor_rfid" name="nomor_rfid" placeholder="Nomor RFID">
            </div>
            <div class="mb-3">
                <input type="number" class="form-control" id="nomor_loker" name="nomor_loker" placeholder="Nomor Loker">
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
    </div>
</body>

</html>