<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

$host = '2raEEvCp1AcJy3cFu6n0ozE808v7kGFQgTEB8YSUtAUc0Rlomce5N8XTGfcxm1kV@kwcggwkso48ocsks8880g40k';
$db = 'myirt_adaptive_learning';
$user = 'root';
$pass = 'KZpcshK2nKeG47DYZfHvC2tbehurKwx2AGzoj6fFpAoWoX1Gl43i4B9jWQhsJ0Oe';

$conn = mysqli_connect($host, $user, $pass, $db);