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
    echo "Data berhasil dihapus!";
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

    <style>
    body {
        background-color: #f8f9fa;
        padding: 20px;
    }

    table {
        margin: auto;
        width: 80%;
        background-color: #fff;
        border-collapse: collapse;
    }

    table th,
    table td {
        padding: 10px;
        text-align: left;
        border: 1px solid #dee2e6;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
    }
    </style>
</head>

<body>
    <h2 class="text-center">Dashboard RFID</h2>

    <table class="table">
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

</body>

</html>

<?php
// Menutup koneksi
$conn->close();
?>