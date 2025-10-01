<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

$host = 'localhost';
$db = 'myirt_adaptive_learning';
$user = 'root';
$pass = 'KZpcshK2nKeG47DYZfHvC2tbehurKwx2AGzoj6fFpAoWoX1Gl43i4B9jWQhsJ0Oe';

$conn = mysqli_connect($host, $user, $pass, $db);