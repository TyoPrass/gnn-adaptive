<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $module_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    $attempt = (int)$_POST['attempt'];
    
    // Get student_id yang valid dari session
    $student_id = $_SESSION['student_id'];
    
    $correct_answers = 0;
    $total_questions = 0;
    
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $question_id = substr($key, 9); // Remove 'question_' prefix
            $user_answer = (int)$value; // Index yang dipilih user (0, 1, 2, dst)
            
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
    
    // Calculate score
    $nilai = ($correct_answers / $total_questions) * 100;
    
    // Determine status
    $status = ($nilai >= 70) ? 'lulus' : 'gagal';
    
    // Save to database
    $sql = "INSERT INTO post_test_e_learning_result (student_id, module_id, nilai, status, attempt, created_at) 
            VALUES ('{$student_id}', '{$module_id}', {$nilai}, '{$status}', {$attempt}, NOW())";
    $query = mysqli_query($conn, $sql);
    
    if (!$query) {
        die("Error: " . mysqli_error($conn) . "<br>Student ID: {$student_id}<br>Module ID: {$module_id}");
    } else {
        // Set session for result
        $_SESSION['posttest_status'] = $status;
        $_SESSION['posttest_score'] = $nilai;
        $_SESSION['posttest_module'] = $module_id;
        
        if ($status == 'lulus') {
            // Mark module as learned (gunakan INSERT IGNORE atau CHECK dulu)
            $sql_check_learned = "SELECT * FROM module_learned WHERE student_id = '{$student_id}' AND module_id = '{$module_id}'";
            $query_check = mysqli_query($conn, $sql_check_learned);
            
            if (mysqli_num_rows($query_check) == 0) {
                // Belum ada record, INSERT baru
                $sql_learned = "INSERT INTO module_learned (student_id, module_id) 
                               VALUES ('{$student_id}', '{$module_id}')";
                mysqli_query($conn, $sql_learned);
            }
            
            // Get next module untuk lanjut pembelajaran
            $sql_next = "SELECT * FROM module WHERE id > '{$module_id}' ORDER BY id ASC LIMIT 1";
            $query_next = mysqli_query($conn, $sql_next);
            
            if (mysqli_num_rows($query_next) > 0) {
                $next_module = mysqli_fetch_assoc($query_next);
                // Redirect ke modul berikutnya
                header('location: ../student/module-e-learning.php?module=' . $next_module['id'] . '&posttest=passed');
            } else {
                // Jika sudah modul terakhir, kembali ke index
                header('location: ../student/index-e-learning.php?posttest=completed');
            }
        } else {
            // Jika gagal, redirect kembali ke materi modul yang sama untuk mengulang
            header('location: ../student/module-e-learning.php?module=' . $module_id . '&posttest=failed');
        }
    }
}
?>
