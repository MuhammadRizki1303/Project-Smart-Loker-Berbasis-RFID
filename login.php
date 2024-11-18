<?php
session_start();

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    // Simpan username ke sesi untuk kebutuhan autentikasi wajah
    $_SESSION['username'] = $username;

    // Langsung alihkan ke halaman autentikasi wajah
    header("Location: recognize.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengenalan Wajah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="col-sm-2 card-container show">
        <h2 class="text-center gradient-title">Login</h2>

        <!-- Tampilkan pesan kesalahan dari sesi jika ada -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']); ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Form login tetap sama -->
        <form class="form-container" method="POST" action="">
            <div class="mb-3 form-group floating-label">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <label for="username" class="form-label">Username</label>
            </div>
            <div class="mb-3 form-group floating-label">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                    required>
                <label for="password" class="form-label">Password</label>
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-purple">Login</button>
            </div>
        </form>
    </div>

    <!-- Logo Proyek -->
    <div class="col-sm-2 logo-container">
        <img src="images/logo.png" alt="Logo Proyek" class="rotate-scale-up circle-avatar">
        <h2 class="project-title">Smart Locker</h2>
    </div>
</body>

</html>