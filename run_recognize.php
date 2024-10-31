<?php
// Debug untuk memastikan file ini dipanggil
echo "Pengenalan wajah dimulai!";

// Jika ada perintah untuk menjalankan Python
$output = shell_exec("python Recognize.py 2>&1");
echo $output;
