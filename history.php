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

    // Validasi input NIM
    if (empty($nim)) {
        echo "<script>alert('NIM tidak boleh kosong.'); window.location.href='history.php';</script>";
        exit();
    }

    // Memulihkan data dari tabel history ke tabel rfid_cards
    $restore_sql = "INSERT INTO rfid_cards (nama, nim, nomor_rfid, nomor_loker, nomor_hp) 
                    SELECT nama, nim, COALESCE(nomor_rfid, '') AS nomor_rfid, COALESCE(nomor_loker, '') AS nomor_loker, status, nomor_hp 
                    FROM history WHERE nim = ?";
    $stmt = $conn->prepare($restore_sql);
    if (!$stmt) {
        die("Error saat prepare statement: " . $conn->error);
    }

    $stmt->bind_param("s", $nim);
    if ($stmt->execute()) {
        // Hapus data dari tabel history setelah dipulihkan
        $delete_sql = "DELETE FROM history WHERE nim = ?";
        $stmt_delete = $conn->prepare($delete_sql);
        $stmt_delete->bind_param("s", $nim);

        if ($stmt_delete->execute()) {
            echo "<script>alert('Data berhasil dipulihkan'); window.location.href='history.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data dari history.');</script>";
        }
    } else {
        echo "<script>alert('Gagal memulihkan data ke rfid_cards.');</script>";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="tabled-container">
        <h2 class="gradient-title">History Data</h2>

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
                    <td class="button-group2">
                        <form action="history.php" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin memulihkan data ini?')">
                            <input type="hidden" name="restore_nim" value="<?php echo $row['nim']; ?>">
                            <button type="submit" class="btn btn-info btn-sm">Restore</button>
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

        <div class="button-group text-center mt-3">
            <a href="dashboard.php" class="btn btn-purple">Back</a>
        </div>
    </div>
</body>

</html>