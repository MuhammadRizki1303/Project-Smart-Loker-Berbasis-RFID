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

// Menghapus data
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM rfid_cards WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data Berhasil Dihapus!!');</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Mengambil data dari database
$sql = "SELECT * FROM rfid_cards";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RFID | Politeknik Negeri Lhokseumawe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/dashboard.css">

    <style>
        /* Tema warna dan background */
        body {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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

        /* Animasi dan tampilan tabel */
        .table-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
            transform: translateY(-20px);
            width: 90%;
            max-width: 900px;
        }

        .table-container.show {
            opacity: 1;
            transform: translateY(0);
        }

        h2 {
            color: #6a5acd;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-danger {
            background-color: #ff7f50;
            border-color: #ff7f50;
        }

        .btn-danger:hover {
            background-color: #6a5acd;
            border-color: #6a5acd;
        }

        .btn-back {
            margin-top: 20px;
            background-color: #6a5acd;
            border-color: #6a5acd;
        }

        .btn-back:hover {
            background-color: #ff7f50;
            border-color: #ff7f50;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="table-container" id="dashboard-table">
        <h2>List User RFID</h2>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Nomor RFID</th>
                    <th>Nomor Loker</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['nim']; ?></td>
                            <td><?php echo $row['nomor_rfid']; ?></td>
                            <td><?php echo $row['nomor_loker']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="dashboard.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Tidak ada data yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tombol kembali ke halaman login -->
        <div class="text-center">
            <a href="index.php" class="btn btn-back btn-block">Kembali ke Halaman Login</a>
        </div>
    </div>

    <script>
        // Animasi saat halaman dibuka
        window.onload = function () {
            document.getElementById("dashboard-table").classList.add("show");
        }
    </script>
</body>

</html>

<?php
// Menutup koneksi
$conn->close();
?>