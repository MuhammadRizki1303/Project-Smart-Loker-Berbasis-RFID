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

    $insert_history = "INSERT INTO history (nama, nim, nomor_rfid, nomor_loker, status, nomor_hp) 
                       SELECT nama, nim, nomor_rfid, nomor_loker, 0 AS status, nomor_hp FROM rfid_cards WHERE nim = ?";
    $stmt = $conn->prepare($insert_history);
    $stmt->bind_param("s", $nim);
    if ($stmt->execute()) {
        $delete_card = "DELETE FROM rfid_cards WHERE nim = ?";
        $stmt_delete = $conn->prepare($delete_card);
        $stmt_delete->bind_param("s", $nim);
        if ($stmt_delete->execute()) {
            echo "<script>alert('Data berhasil dihapus'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data dari rfid_cards');</script>";
        }
    } else {
        echo "<script>alert('Gagal menyimpan data ke history');</script>";
    }
}

// Proses pengeditan data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_nim'])) {
    $nim = $_POST['edit_nim'];
    $nama = $_POST['nama'];
    $nomor_rfid = $_POST['nomor_rfid'];
    $nomor_loker = $_POST['nomor_loker'];
    $nomor_hp = $_POST['nomor_hp'];
    $status = $_POST['status'];

    $update_query = "UPDATE rfid_cards SET nama = ?, nomor_rfid = ?, nomor_loker = ?, nomor_hp = ?, status = ? WHERE nim = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssiss", $nama, $nomor_rfid, $nomor_loker, $nomor_hp, $status, $nim);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data');</script>";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
    .table-container {
        max-width: 90%;
        margin: auto;
        overflow-x: auto;
    }

    table {
        width: 100%;
        text-align: center;
    }

    .button-group2 {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    </style>
</head>

<body>
    <div class="table-container mt-5">
        <h2 class="text-center gradient-title">List User RFID</h2>
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
                        <form action="dashboard.php" method="POST" style="display:inline;"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <input type="hidden" name="delete_nim" value="<?php echo $row['nim']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
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
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info">Save</button>
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
        <div class="button-group text-center mt-3">
            <a href="history.php" class="mx-3 col-sm-2 btn btn-orange">History</a>
            <a href="input.php" class="mx-3 col-sm-2 btn btn-purple">Add</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>