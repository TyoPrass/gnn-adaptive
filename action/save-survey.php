<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
require_once('../config/gnn_api_client.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Debug log
    error_log("Survey POST data count: " . count($_POST));
    error_log("Student ID from session: " . $_SESSION['student_id']);
    
    // Extract answers
    $ketertarikan = array(
        '1' => intval($_POST['question1']),
        '2' => intval($_POST['question2']),
        '3' => intval($_POST['question3']),
        '4' => intval($_POST['question4']),
        '5' => intval($_POST['question5']),
    );
    
    $keterlibatan = array(
        '6' => intval($_POST['question6']),
        '7' => intval($_POST['question7']),
        '8' => intval($_POST['question8']),
        '9' => intval($_POST['question9']),
        '10' => intval($_POST['question10']),
    );
    
    // Hitung total score
    $ktt = 0;
    $ktl = 0;

    // Mengambil data ketertarikan (max 5)
    foreach ($ketertarikan as $k) {
        $ktt += $k;
    }

    // Mengambil data keterlibatan (max 5)
    foreach ($keterlibatan as $k) {
        $ktl += $k;
    }

    // Menentukan level ketertarikan (0-5 scale)
    if ($ktt <= 1) {
        $levelktt = 1;
    } elseif ($ktt <= 3) {
        $levelktt = 2;
    } else {
        $levelktt = 3;
    }

    // Menentukan level keterlibatan (0-5 scale)
    if ($ktl <= 1) {
        $levelktl = 1;
    } elseif ($ktl <= 3) {
        $levelktl = 2;
    } else {
        $levelktl = 3;
    }
        // } else {
        //     $sql = "SELECT * FROM module_question WHERE id = '{$question_id}'";
        //     $query = mysqli_query($conn, $sql);
        //     $question = mysqli_fetch_array($query, MYSQLI_ASSOC);
        //     if ($question['answer'] == $p) {
        //         $jawaban_benar++;
        //     }
        //     $total_soal++;
        // }
    }
    // if ($jawaban_benar <= 3) {
    //     $leveltest = 1;
    // } else if ($jawaban_benar <= 7) {
    //     $leveltest = 2;
    // } else {
    //     $leveltest = 3;
    // }

    // ALGORITMA PENGHITUNGAN LAMA
    // if ($levelktl == 1 && $levelktt == 1 && $leveltest == 1) {
    //     $level = 1;
    // } elseif ($levelktl == 1 && $levelktt == 1 && $leveltest == 2) {
    //     $level = 1;
    // } elseif ($levelktl == 1 && $levelktt == 1 && $leveltest == 3) {
    //     $level = 2;
    // } elseif ($levelktl == 1 && $levelktt == 2 && $leveltest == 1) {
    //     $level = 1;
    // } elseif ($levelktl == 1 && $levelktt == 2 && $leveltest == 2) {
    //     $level = 2;
    // } elseif ($levelktl == 1 && $levelktt == 2 && $leveltest == 3) {
    //     $level = 2;
    // } elseif ($levelktl == 1 && $levelktt == 3 && $leveltest == 1) {
    //     $level = 2;
    // } elseif ($levelktl == 1 && $levelktt == 3 && $leveltest == 2) {
    //     $level = 2;
    // } elseif ($levelktl == 1 && $levelktt == 3 && $leveltest == 3) {
    //     $level = 2;
    // } elseif ($levelktl == 2 && $levelktt == 1 && $leveltest == 1) {
    //     $level = 1;
    // } elseif ($levelktl == 2 && $levelktt == 1 && $leveltest == 2) {
    //     $level = 2;
    // } elseif ($levelktl == 2 && $levelktt == 1 && $leveltest == 3) {
    //     $level = 2;
    // } elseif ($levelktl == 2 && $levelktt == 2 && $leveltest == 1) {
    //     $level = 2;
    // } elseif ($levelktl == 2 && $levelktt == 2 && $leveltest == 2) {
    //     $level = 2;
    // } elseif ($levelktl == 2 && $levelktt == 2 && $leveltest == 3) {
    //     $level = 2;
    // } elseif ($levelktl == 2 && $levelktt == 3 && $leveltest == 1) {
    //     $level = 2;
    // } elseif ($levelktl == 2 && $levelktt == 3 && $leveltest == 2) {
    //     $level = 2;
    // } elseif ($levelktl == 2 && $levelktt == 3 && $leveltest == 3) {
    //     $level = 3;
    // } elseif ($levelktl == 3 && $levelktt == 1 && $leveltest == 1) {
    //     $level = 2;
    // } elseif ($levelktl == 3 && $levelktt == 1 && $leveltest == 2) {
    //     $level = 2;
    // } elseif ($levelktl == 3 && $levelktt == 1 && $leveltest == 3) {
    //     $level = 2;
    // } elseif ($levelktl == 3 && $levelktt == 2 && $leveltest == 1) {
    //     $level = 2;
    // } elseif ($levelktl == 3 && $levelktt == 2 && $leveltest == 2) {
    //     $level = 2;
    // } elseif ($levelktl == 3 && $levelktt == 2 && $leveltest == 3) {
    //     $level = 3;
    // } elseif ($levelktl == 3 && $levelktt == 3 && $leveltest == 1) {
    //     $level = 2;
    // } elseif ($levelktl == 3 && $levelktt == 3 && $leveltest == 2) {
    //     $level = 3;
    // } elseif ($levelktl == 3 && $levelktt == 3 && $leveltest == 3) {
    //     $level = 3;
    // }


    // ALGORITMA PENGHITUNGAN - ADAPTIVE SIMPLIFIED
    // Rata-rata dari ketertarikan dan keterlibatan
    $avg_level = ($levelktt + $levelktl) / 2;
    
    if ($avg_level < 1.5) {
        $level = 1;  // Rendah
    } elseif ($avg_level < 2.5) {
        $level = 2;  // Sedang
    } else {
        $level = 3;  // Tinggi
    }

    error_log("Survey calculation: ktt={$ktt}, ktl={$ktl}, levelktt={$levelktt}, levelktl={$levelktl}, final_level={$level}");

    // Simpan ke survey_result
    $sql = "INSERT INTO survey_result (level_result, student_id) 
            VALUES ('{$level}', '{$_SESSION['student_id']}')";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        error_log("Error saving survey_result: " . mysqli_error($conn));
        die("Database Error: " . mysqli_error($conn));
    }
    
    error_log("Survey saved successfully for student_id={$_SESSION['student_id']}, level={$level}");
    
    // Save to session
    $_SESSION['survey_level'] = $level;
    $_SESSION['survey_completed'] = true;
    
    // Initialize GNN API Client (optional)
    try {
        $gnn_client = new GNNApiClient('http://localhost:5001');
        
        // Update student motivation profile in GNN
        $gnn_result = $gnn_client->update_student_progress([
            'student_id' => $_SESSION['student_id'],
            'type' => 'survey',
            'motivation_level' => $level,
            'interest_score' => $ktt,
            'engagement_score' => $ktl
        ]);
        
        error_log("GNN updated with survey data");
    } catch (Exception $e) {
        error_log("GNN API Error in survey: " . $e->getMessage());
    }
    
    // Redirect
    header('Location: ../student/index-adaptive-learning.php?survey=success&level=' . $level);
    exit();
