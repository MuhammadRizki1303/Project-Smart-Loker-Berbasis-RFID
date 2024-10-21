<?php
session_start();
// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti sesuai username MySQL
$password = ""; // Ganti sesuai password MySQL
$dbname = "rfid_system";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);
// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek login admin di database
    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login sukses
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Login gagal
        echo "<script>alert('Username atau password salah!');</script>";
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
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
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

    .video-container {
        border: 2px solid #6a5acd;
        padding: 10px;
        border-radius: 10px;
    }

    #video {
        width: 400px;
        height: 300px;
    }

    .login-btn {
        margin-top: 20px;
    }
    </style>
</head>

<body>

    <div class="login-container">
        <h2 class="text-center project-title">Login dengan Pengenalan Wajah</h2>
        <div class="video-container">
            <video id="video" autoplay muted></video>
        </div>
        <button class="btn btn-primary login-btn" onclick="window.location.href='/login.py'">Login dengan
            Wajah</button>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <script>
    // Mengakses kamera pengguna
    async function startCamera() {
        const video = document.getElementById('video');
        navigator.mediaDevices.getUserMedia({
                video: {}
            })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => console.error("Kamera tidak ditemukan:", err));
    }

    // Mulai pengenalan wajah
    async function startFaceRecognition() {
        await faceapi.nets.tinyFaceDetector.loadFromUri('/models'); // Pastikan model diunduh
        const video = document.getElementById('video');
        const options = new faceapi.TinyFaceDetectorOptions();

        const detection = await faceapi.detectSingleFace(video, options);
        if (detection) {
            alert('Wajah terdeteksi, login berhasil!');
            // Redirect ke halaman dashboard setelah login sukses
            window.location.href = "input.php";
        } else {
            alert('Wajah tidak terdeteksi, coba lagi.');
        }
    }

    // Mulai kamera saat halaman dimuat
    window.onload = startCamera;
    </script>

</body>

</html>