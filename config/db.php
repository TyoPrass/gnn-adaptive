<?php

// --- Dynamic Base URL Detection ---

// Cek apakah koneksi asli via HTTPS
$is_https = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || 
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

// Ambil host asli dari header proxy, atau fallback ke host biasa
$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST'];

// Bangun Base URL yang benar
$protocol = $is_https ? 'https://' : 'http://';
$base_url = $protocol . $host;

define('BASE_URL', $base_url);

// --- End of Detection ---



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