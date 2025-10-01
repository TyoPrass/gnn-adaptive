<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

$level = $_SESSION['level'];
$new_level = $level - 1;

$sql = "UPDATE level_student SET level = '$new_level' WHERE student_id = '{$_SESSION['student_id']}' ";
$query = mysqli_query($conn, $sql);

if($query){
    $_SESSION['level_user'] = $new_level;
    $_SESSION['sukses_turun_level'] = true;
    $sql = "DELETE FROM module_learned WHERE student_id = '${$_SESSION['student_id']}'";
    $query = mysqli_query($conn, $sql);
    if($query){
        header('location: ../student/modul.php');
    }else{
        header('location: ../student/index.php');
    }
}else{
    header('location: ../student/index.php');
}