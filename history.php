<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses pemulihan data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restore_nim'])) {
    $nim = $_POST['restore_nim'];

    // Memulihkan data dari tabel history ke tabel rfid_cards
    $restore_sql = "INSERT INTO rfid_cards (nama, nim, nomor_rfid, nomor_loker, status, nomor_hp) 
                    SELECT nama, nim, nomor_rfid, nomor_loker, status, nomor_hp FROM history WHERE nim = ?";
    $stmt = $conn->prepare($restore_sql);
    $stmt->bind_param("s", $nim);
    $stmt->execute();

    // Hapus data dari tabel history setelah dipulihkan
    $delete_sql = "DELETE FROM history WHERE nim = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $nim);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dipulihkan'); window.location.href='history.php';</script>";
    } else {
        echo "<script>alert('Gagal memulihkan data');</script>";
    }
}

// Ambil semua data dari tabel history
$sql = "SELECT * FROM history";
$result = $conn->query($sql);

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Penghapusan | Politeknik Negeri Lhokseumawe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .table-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
            margin-top: 30px;
        }

        h2 {
            color: #6a5acd;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-success {
            background-color: #6a5acd;
            border-color: #6a5acd;
        }

        .btn-success:hover {
            background-color: #ff7f50;
            border-color: #ff7f50;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
        }

        /* Responsif */
        @media (max-width: 767px) {
            .table-container {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="table-container">
        <h2>History Penghapusan Data</h2>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Nomor RFID</th>
                    <th>Nomor Loker</th>
                    <th>Nomor HP</th>
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
                            <td><?php echo $row['nomor_hp']; ?></td>
                            <td>
                                <?php echo $row['status'] == 1 ? '<span class="status-active">Aktif</span>' : '<span class="status-inactive">Non-Aktif</span>'; ?>
                            </td>
                            <td>
                                <form action="history.php" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin memulihkan data ini?')">
                                    <input type="hidden" name="restore_nim" value="<?php echo $row['nim']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Pulihkan</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Tidak ada data yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-info">Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>