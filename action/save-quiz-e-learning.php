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
        // ✅ PERBAIKAN: Konversi ke string dan gunakan strict comparison
        $correct_answer = (string)$mq['answer']; // Jawaban benar dari DB (index: 0, 1, 2, 3)
        $user_answer = (string)$p; // Jawaban user (index: 0, 1, 2, 3)
        
        // Cek apakah jawaban benar dengan STRICT COMPARISON
        $is_correct = ($correct_answer === $user_answer);
        
        if ($is_correct) {
            // JAWABAN BENAR
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
            }
        } else {
            // JAWABAN SALAH
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
            }
        }
    }

    $moduls = array_merge($modul_1, $modul_2, $modul_3, $modul_4, $modul_5, $modul_6, $modul_7);
    
    // ✅ Hitung total jawaban benar
    $total_benar = array_sum($moduls);
    
    // ✅ Total soal quiz (dinamis berdasarkan jumlah jawaban)
    $total_soal = count($moduls);
    
    // ✅ Hitung nilai persentase
    $nilai = ($total_benar / $total_soal) * 100;
    
    // Simpan ke database
    $sql = "INSERT INTO quiz_result_e_learning (student_id, nilai) VALUES ('{$_SESSION['student_id']}', $nilai)";
    $query = mysqli_query($conn, $sql);


    if (!$query) {
        echo mysqli_error($conn);
    } else {
        $_SESSION['quiz_finish_e_learning'] = true;
        header('location: ../student/quiz-e-learning.php');
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