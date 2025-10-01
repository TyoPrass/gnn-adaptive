<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}


include('../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $modul_1 = array();
    $modul_2 = array();
    $modul_3 = array();
    $modul_4 = array();
    $modul_5 = array();
    $modul_6 = array();
    $modul_7 = array();

    foreach ($_POST as $key =>  $p) {
        $question_id = substr($key, 8);
        $sql = "SELECT * FROM module_question WHERE id = '{$question_id}'";
        $query = mysqli_query($conn, $sql);
        $mq = mysqli_fetch_array($query, MYSQLI_ASSOC);
        // echo '<br/> Module id : ';
        // echo $mq['module_id'];
        if ($mq['answer'] == $p) {
            if ($mq['module_id'] == 1) {
                $modul_1[] = 1;
            } else if ($mq['module_id'] == 2) {
                $modul_2[] = 1;
            } else if ($mq['module_id'] == 3) {
                $modul_3[] = 1;            
            } else if ($mq['module_id'] == 4) {
                $modul_4[] = 1;
            } else if ($mq['module_id'] == 5) {
                $modul_5[] = 1;
            } else if ($mq['module_id'] == 6) {
                $modul_6[] = 1;
            } else if ($mq['module_id'] == 7) {
                $modul_7[] = 1;
            // } else {
            //     $modul_12[] = 1;
            }
        } else {
            if ($mq['module_id'] == 1) {
                $modul_1[] = 0;
            } else if ($mq['module_id'] == 2) {
                $modul_2[] = 0;
            } else if ($mq['module_id'] == 3) {
                $modul_3[] = 0;            
            } else if ($mq['module_id'] == 4) {
                $modul_4[] = 0;
            } else if ($mq['module_id'] == 5) {
                $modul_5[] = 0;
            } else if ($mq['module_id'] == 6) {
                $modul_6[] = 0;
            } else if ($mq['module_id'] == 7) {
                $modul_7[] = 0;
            // } else if ($mq['module_id'] == 8) {
            //     $modul_8[] = 0;
            }
        }
    }

    $moduls = array_merge($modul_1, $modul_2, $modul_3, $modul_4, $modul_5, $modul_6, $modul_7 );
    // print_r($moduls);

    // echo array_sum($moduls);

    $nilai = (array_sum($moduls) / 49) * 100;

    // $result = mysqli_query($conn, "INSERT INTO pre_test_answer (student_id, modul_1, modul_2, modul_3, modul_4, modul_5, modul_6, modul_7) VALUES ('{$_SESSION['student_id']}', '{$hitung_modul_1}','{$hitung_modul_2}','{$hitung_modul_3}','{$hitung_modul_4}','{$hitung_modul_5}','{$hitung_modul_6}','{$hitung_modul_7}')");
    $sql = "INSERT INTO quiz_result (student_id, nilai) VALUES ('{$_SESSION['student_id']}', $nilai)";
    $query = mysqli_query($conn, $sql);


    if (!$query) {
        echo mysqli_error($conn);
    } else {
        $_SESSION['quiz_finish'] = true;
        header('location: ../student/index-adaptive-learning.php');
    }
}

function mode($armodul)
{
    $total = 0;
    $v = array_count_values($armodul);
    arsort($v);
    foreach ($v as $k => $v) {
        $total = $k;
        break;
    }
    return $total;
}