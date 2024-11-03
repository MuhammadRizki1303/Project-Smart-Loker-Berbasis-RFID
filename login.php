<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username_db = "root"; // Ganti sesuai username MySQL
$password_db = ""; // Ganti sesuai password MySQL
$dbname = "rfid_system";

// Membuat koneksi
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah sudah login
if (isset($_SESSION['admins'])) {
    // Jika sudah login, langsung ke halaman pengenalan wajah
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pengenalan Wajah</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
            }
            .button-container {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2 class="text-center">Pengenalan Wajah</h2>
            <div class="button-container">
                <button class="btn btn-primary" id="startRecognition">Mulai Pengenalan</button>
            </div>
        </div>

        <script>
            document.getElementById("startRecognition").addEventListener("click", function() {
                // Menjalankan skrip Python untuk pengenalan wajah
                fetch("run_recognize.php")
                    .then(response => response.text())
                    .then(data => {
                        console.log(data); // Output dari skrip Python
                    })
                    .catch(error => console.error("Error:", error));
            });
        </script>
    </body>
    </html>';
    exit();
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Cek login admin di database
    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Memverifikasi password
        if (password_verify($password, $row['password'])) {
            // Login sukses
            $_SESSION['admins'] = $username;
            // Simpan username untuk pengenalan wajah
            file_put_contents('current_user.txt', $_SESSION['admins']);
            header("Location: " . $_SERVER['PHP_SELF']); // Reload untuk mengarahkan ke halaman pengenalan wajah
            exit();
        } else {
            // Password salah
            $error_message = "Username atau password salah!";
        }
    } else {
        // Username tidak ditemukan
        $error_message = "Username atau password salah!";
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
    <title>Login dengan Pengenalan Wajah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 class="text-center project-title">Login dengan Pengenalan Wajah</h2>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>

</html>