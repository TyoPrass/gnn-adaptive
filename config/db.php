<?php


if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}


// --- KONFIGURASI KONEKSI DATABASE ---

// 💡 Host adalah NAMA SERVICE dari database di Coolify
$host = '161.118.220.77'; 

// Nama database yang ingin Anda gunakan
// ⚠️ Pastikan database ini sudah Anda buat! 
// Dari screenshot, database awal yang dibuat Coolify adalah 'default'.
$db = 'myirt_adaptive_learning'; 

// User untuk koneksi
$user = 'root'; 

// Password untuk user root yang Anda set di Coolify
$pass = 'KZpcshK2nKeG47DYZfHvC2tbehurKwx2AGzoj6fFpAoWoX1Gl43i4B9jWQhsJ0Oe';

// Port internal database, defaultnya adalah 3306
$port = 3333; 

// Lakukan koneksi
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


?>