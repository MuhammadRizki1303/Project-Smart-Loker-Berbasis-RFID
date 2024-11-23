<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Redirect ke halaman autentikasi
header("Location: authenticate.html");
exit();