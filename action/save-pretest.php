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
    // $modul_8 = array();
    // $modul_9 = array();
    // $modul_10 = array();
    // $modul_11 = array();
    // $modul_12 = array();

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
            // } else if ($mq['module_id'] == 8) {
            //     $modul_8[] = 1;
            // } else if ($mq['module_id'] == 9) {
            //     $modul_9[] = 1;
            // } else if ($mq['module_id'] == 10) {
            //     $modul_10[] = 1;
            // } else if ($mq['module_id'] == 11) {
            //     $modul_11[] = 1;
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
            // } else if ($mq['module_id'] == 9) {
            //     $modul_9[] = 0;
            // } else if ($mq['module_id'] == 10) {
            //     $modul_10[] = 0;
            // } else if ($mq['module_id'] == 11) {
            //     $modul_11[] = 0;
            // } else {
            //     $modul_12[] = 0;
            }
        }
    }
    // var_dump($modul_1);
    // echo '<br/>';
    // var_dump($modul_2);
    // echo '<br/>';
    // var_dump($modul_3);
    // echo '<br/>';
    // var_dump($modul_4);
    // echo '<br/>';
    // var_dump($modul_5);
    // echo '<br/>';
    // var_dump($modul_6);
    // echo '<br/>';
    // var_dump($modul_7);
    // echo '<br/>';
    // var_dump($modul_8);
    // echo '<br/>';
    // var_dump($modul_9);
    // echo '<br/>';
    // var_dump($modul_10);
    // echo '<br/>';
    // var_dump($modul_11);
    // echo '<br/>';
    // var_dump($modul_12);
    // echo '<br/>';

    //Hitung modul1
    $hitung_modul_1 = max(1, mode($modul_1));
    //Hitung modul2
    $hitung_modul_2 = max(1, mode($modul_2));
    //Hitung modul3
    $hitung_modul_3 = max(1, mode($modul_3));
    //Hitung modul4
    $hitung_modul_4 = max(1, mode($modul_4));
    // //Hitung modul5
    $hitung_modul_5 = max(1, mode($modul_5));
    // //Hitung modul6
    $hitung_modul_6 = max(1, mode($modul_6));
    // //Hitung modul7
    $hitung_modul_7 = max(1, mode($modul_7));
    // //Hitung modul8
    // $hitung_modul_8 = mode($modul_8);
    // //Hitung modul9
    // $hitung_modul_9 = mode($modul_9);
    //Hitung modul10
    // $hitung_modul_10 = mode($modul_10);
    // //Hitung modul11
    // $hitung_modul_11 = mode($modul_11);
    // //Hitung modul12
    // $hitung_modul_12 = mode($modul_12);

    // $result = mysqli_query($conn, "INSERT INTO pre_test_answer (student_id, modul_1, modul_2, modul_3, modul_4, modul_5, modul_6, modul_7, modul_8, modul_9, modul_10, modul_11, modul_12) VALUES ('{$_SESSION['student_id']}', '{$hitung_modul_1}','{$hitung_modul_2}','{$hitung_modul_3}','{$hitung_modul_4}','{$hitung_modul_5}','{$hitung_modul_6}','{$hitung_modul_7}','{$hitung_modul_8}','{$hitung_modul_9}','{$hitung_modul_10}','{$hitung_modul_11}','{$hitung_modul_12}')");
    $result = mysqli_query($conn, 
    "INSERT INTO pre_test_answer (student_id, modul_1, modul_2, modul_3, modul_4, modul_5, modul_6, modul_7) VALUES ('{$_SESSION['student_id']}', '{$hitung_modul_1}','{$hitung_modul_2}','{$hitung_modul_3}','{$hitung_modul_4}','{$hitung_modul_5}','{$hitung_modul_6}','{$hitung_modul_7}')");
    if (!$result) {
        echo mysqli_error($conn);
    } else {
        header('location: ../student/index-adaptive-learning.php');
    }
}

function mode($armodul)
{
    // Jika array kosong, return 1 (modul minimum)
    if (empty($armodul)) {
        return 1;
    }
    
    $total = 1; // Default ke modul 1
    $v = array_count_values($armodul);
    arsort($v);
    foreach ($v as $k => $v) {
        $total = $k;
        break;
    }
    
    // Pastikan tidak kurang dari 1
    return max(1, $total);
}