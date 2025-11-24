<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

// Prodn
// $host = '127.0.0.1'; 
// $db = 'u636744980_hgt_gnn'; 
// $user = 'u636744980_hgt_gnn'; 
// $pass = 'Jerapah@09';

// Delop
$host = '127.0.0.1'; 
$db = 'myirt_adaptive_learning_min'; 
$user = 'root'; 
$pass = '';
$port = 3306; 

// Lakukan koneksi
$conn = mysqli_connect($host, $user, $pass, $db, $port);
// $conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    // Jangan output error jika dipanggil dari AJAX
    if (isset($_POST['action']) || isset($_GET['action'])) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit;
    }
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset untuk menghindari masalah encoding
mysqli_set_charset($conn, 'utf8mb4');