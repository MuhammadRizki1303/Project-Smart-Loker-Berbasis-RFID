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

// Menampilkan data history
$history_sql = "SELECT * FROM rfid_cards WHERE status = 0 ORDER BY id DESC";  // Hanya mengambil data yang sudah dihapus
$result = $conn->query($history_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History | Politeknik Negeri Lhokseumawe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <style>
        /* Styling dan tema seperti sebelumnya */
        body {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(45deg, #ff7f50, #6a5acd, #ffffff);
            background-size: 300% 300%;
            animation: gradientBG 15s ease infinite;
            margin: 0;
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

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #6a5acd;
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            margin-top: 20px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #999;
        }

        .add-btn,
        .btn-dashboard {
            display: inline-block;
            background-color: #6a5acd;
            color: white;
            padding: 10px 40px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            transition: background-color 0.3s ease;
        }

        .add-btn:hover,
        .btn-dashboard:hover {
            background-color: #ff7f50;
        }

        .btn-primary {
            background-color: #6a5acd;
            border-color: #6a5acd;
            padding: 10px 40px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #ff7f50;
            border-color: #ff7f50;
        }

        .text-center {
            text-align: center;
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Data History Pengguna RFID</h2>

        <!-- Tombol untuk menambah data -->
        <div class="text-center">
            <a href="input.php" class="add-btn">Tambah Data</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Nomor RFID</th>
                    <th>Nomor Loker</th>
                    <th>Status</th>
                    <th>Nomor HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['nama']}</td>
                            <td>{$row['nim']}</td>
                            <td>{$row['nomor_rfid']}</td>
                            <td>{$row['nomor_loker']}</td>
                            <td>" . ($row['status'] == 0 ? 'Non-Aktif' : 'Aktif') . "</td>
                            <td>{$row['nomor_hp']}</td>
                            <td>
                                <a href='input.php?nim={$row['nim']}' class='btn btn-primary btn-sm'>Tambah/Edit</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='no-data'>Tidak ada data history</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="text-center">
            <a href="dashboard.php" class="btn-dashboard">Kembali ke Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Menutup koneksi
$conn->close();
?>