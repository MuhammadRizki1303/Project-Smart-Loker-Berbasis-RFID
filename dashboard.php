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

// Proses penghapusan data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_nim'])) {
    $nim = $_POST['delete_nim'];

    // Simpan data ke history sebelum dihapus
    $delete_sql = "INSERT INTO history (nama, nim, nomor_rfid, nomor_loker, status, nomor_hp) 
                    SELECT nama, nim, nomor_rfid, nomor_loker, status, nomor_hp FROM rfid_cards WHERE nim = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $nim);
    $stmt->execute();

    // Hapus data dari rfid_cards
    $delete_sql = "DELETE FROM rfid_cards WHERE nim = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $nim);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nim'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $nomor_rfid = $_POST['nomor_rfid'];
    $nomor_loker = $_POST['nomor_loker'];
    $nomor_hp = $_POST['nomor_hp'];
    $status = $_POST['status'];

    $update_sql = "UPDATE rfid_cards SET nama = ?, nomor_rfid = ?, nomor_loker = ?, nomor_hp = ?, status = ? WHERE nim = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssss", $nama, $nomor_rfid, $nomor_loker, $nomor_hp, $status, $nim);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data');</script>";
    }
}

// Ambil semua data user dari tabel rfid_cards
$sql = "SELECT * FROM rfid_cards";
$result = $conn->query($sql);

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RFID | Politeknik Negeri Lhokseumawe</title>
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

        /* Animasi dan tampilan tabel */
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

        /* Warna tombol yang konsisten */
        .btn-danger {
            background-color: #ff7f50;
            border-color: #ff7f50;
        }

        .btn-danger:hover {
            background-color: #6a5acd;
            border-color: #6a5acd;
        }

        .btn-info {
            background-color: #6a5acd;
            border-color: #6a5acd;
        }

        .btn-info:hover {
            background-color: #ff7f50;
            border-color: #ff7f50;
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

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
            font-weight: bold;
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
    <div class="container mt-5">
        <h2 class="text-center">List User RFID</h2>
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
                                <form action="dashboard.php" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    <input type="hidden" name="delete_nim" value="<?php echo $row['nim']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?php echo $row['nim']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Modal Edit Data -->
                        <div class="modal fade" id="editModal<?php echo $row['nim']; ?>" tabindex="-1"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Data User RFID</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="nim" value="<?php echo $row['nim']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="nama"
                                                    value="<?php echo $row['nama']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor RFID</label>
                                                <input type="text" class="form-control" name="nomor_rfid"
                                                    value="<?php echo $row['nomor_rfid']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor Loker</label>
                                                <input type="text" class="form-control" name="nomor_loker"
                                                    value="<?php echo $row['nomor_loker']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor HP</label>
                                                <input type="text" class="form-control" name="nomor_hp"
                                                    value="<?php echo $row['nomor_hp']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select class="form-select" name="status">
                                                    <option value="1" <?php echo $row['status'] == 1 ? 'selected' : ''; ?>>Aktif
                                                    </option>
                                                    <option value="0" <?php echo $row['status'] == 0 ? 'selected' : ''; ?>>
                                                        Non-Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Tidak ada data yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-3">
            <a href="history.php" class="btn btn-info">Lihat History Penghapusan</a>
        </div>
        <div class="text-center mt-2">
            <a href="input.php" class="btn btn-info">Tambah Data</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>