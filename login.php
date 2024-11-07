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
    <style>
        /* Global Styles */
        body {
            background: linear-gradient(45deg, #ff7f50, #6a5acd, #4a4e69, #22223b);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            color: #333;
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

        /* Container for the login form */
        .login-container {
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 360px;
            text-align: center;
            opacity: 0;
            transform: translateY(-20px);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .login-container.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Title Styles */
        h2.project-title {
            color: #4a4e69;
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Alert Message */
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        /* Form Label Styles */
        .form-label {
            color: #444;
            font-weight: 500;
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }

        /* Input Field Styles */
        .form-control {
            width: 100%;
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #6a5acd;
            box-shadow: 0 0 5px rgba(106, 90, 205, 0.4);
            outline: none;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #6a5acd;
            color: white;
            padding: 8px;
            border-radius: 5px;
            font-size: 0.9em;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
            max-width: 120px;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #ff7f50;
            transform: translateY(-2px);
        }

        /* Center Button */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .login-container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container show">
        <h2 class="text-center project-title">Login dengan Pengenalan Wajah</h2>

        <!-- Tampilkan pesan kesalahan dari sesi jika ada -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']); ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Form login tetap sama -->
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