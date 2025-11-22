<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $module_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    $student_id = $_SESSION['student_id'];
    
    // CEK: Apakah sudah lulus sebelumnya? Jika ya, redirect kembali
    $sql_check_passed = "SELECT * FROM post_test_adaptive_result 
                         WHERE student_id = '{$student_id}' 
                         AND module_id = '{$module_id}' 
                         AND status = 'lulus'
                         LIMIT 1";
    $query_check_passed = mysqli_query($conn, $sql_check_passed);
    
    if (mysqli_num_rows($query_check_passed) > 0) {
        // Sudah lulus, tidak perlu submit lagi
        $_SESSION['info_message'] = 'Anda sudah lulus post-test ini. Nilai tidak akan diubah.';
        header('location: ../student/berhasil-post-test.php?module_id=' . $module_id);
        exit();
    }
    
    $correct_answers = 0;
    $total_questions = 0;
    
    // Hitung jawaban benar
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question') === 0 && $key !== 'question_count') {
            $question_id = substr($key, 8); // Remove 'question' prefix
            $user_answer = (int)$value; // Index yang dipilih user (0, 1, 2, 3)
            
            // Get correct answer index from module_question
            $sql = "SELECT answer FROM module_question WHERE id = '{$question_id}'";
            $query = mysqli_query($conn, $sql);
            $question = mysqli_fetch_assoc($query);
            
            if ($question) {
                $correct_answer = (int)$question['answer']; // Index jawaban yang benar
                $total_questions++;
                
                // Check if answer is correct
                if ($user_answer === $correct_answer) {
                    $correct_answers++;
                }
            }
        }
    }
    
    // Calculate score (0-100)
    $nilai = ($total_questions > 0) ? ($correct_answers / $total_questions) * 100 : 0;
    $percentage = number_format($nilai, 2);
    
    // Determine status (passing grade 70%)
    $status = ($nilai >= 70) ? 'lulus' : 'gagal';
    
    // Save to database - buat tabel baru untuk post test adaptive
    $sql = "INSERT INTO post_test_adaptive_result (student_id, module_id, correct_answers, total_questions, score, status, created_at) 
            VALUES ('{$student_id}', '{$module_id}', {$correct_answers}, {$total_questions}, {$percentage}, '{$status}', NOW())";
    $query = mysqli_query($conn, $sql);
    
    if (!$query) {
        $_SESSION['error_message'] = "Error menyimpan hasil: " . mysqli_error($conn);
        header('location: ../student/post_test.php?module=' . $module_id);
        exit();
    }
    
    // Set session for result page
    $_SESSION['posttest_status'] = $status;
    $_SESSION['posttest_score'] = $percentage;
    $_SESSION['posttest_correct'] = $correct_answers;
    $_SESSION['posttest_total'] = $total_questions;
    $_SESSION['posttest_module'] = $module_id;
    
    if ($status == 'lulus') {
        // Mark module as learned (jika belum ada)
        $sql_check_learned = "SELECT * FROM module_learned WHERE student_id = '{$student_id}' AND module_id = '{$module_id}'";
        $query_check = mysqli_query($conn, $sql_check_learned);
        
        if (mysqli_num_rows($query_check) == 0) {
            $sql_learned = "INSERT INTO module_learned (student_id, module_id) 
                           VALUES ('{$student_id}', '{$module_id}')";
            mysqli_query($conn, $sql_learned);
        }
        
        // Check if perfect score (semua benar)
        $is_perfect = ($correct_answers == $total_questions);
        $_SESSION['posttest_perfect'] = $is_perfect;
        
        // Redirect ke halaman sukses
        header('location: ../student/berhasil-post-test.php?module_id=' . $module_id);
        exit();
    } else {
        // Redirect ke halaman gagal
        header('location: ../student/gagal-post-test.php?module_id=' . $module_id);
        exit();
    }
} else {
    header('location: ../student/index.php');
    exit();
}
?>
