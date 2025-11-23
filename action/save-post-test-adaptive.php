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
        
        // ===== SISTEM NAIK LEVEL =====
        // Hitung total modul yang lulus dan persentase kelulusan
        $sql_total_modules = "SELECT COUNT(DISTINCT id) as total FROM module WHERE 1";
        $query_total = mysqli_query($conn, $sql_total_modules);
        $data_total = mysqli_fetch_assoc($query_total);
        $total_all_modules = $data_total['total'];
        
        // Hitung modul yang sudah lulus
        $sql_passed_modules = "SELECT COUNT(DISTINCT module_id) as passed 
                               FROM post_test_adaptive_result 
                               WHERE student_id = '{$student_id}' 
                               AND status = 'lulus'";
        $query_passed = mysqli_query($conn, $sql_passed_modules);
        $data_passed = mysqli_fetch_assoc($query_passed);
        $total_passed_modules = $data_passed['passed'];
        
        // Hitung persentase
        $pass_percentage = ($total_all_modules > 0) ? ($total_passed_modules / $total_all_modules) * 100 : 0;
        
        // Get current level
        $sql_current_level = "SELECT level FROM pre_test_result WHERE student_id = '{$student_id}'";
        $query_level = mysqli_query($conn, $sql_current_level);
        $current_level_data = mysqli_fetch_assoc($query_level);
        $current_level = $current_level_data['level'] ?? 1;
        
        $new_level = $current_level;
        $level_up = false;
        
        // Logic naik level
        if ($pass_percentage >= 80 && $current_level < 3) {
            // Lebih dari 80% modul lulus -> Level 3 (Ahli)
            $new_level = 3;
            $level_up = true;
        } elseif ($pass_percentage >= 50 && $current_level < 2) {
            // Lebih dari 50% modul lulus -> Level 2 (Mahir)
            $new_level = 2;
            $level_up = true;
        }
        
        // Update level jika naik
        if ($level_up) {
            $sql_update_level = "UPDATE pre_test_result SET level = '{$new_level}' WHERE student_id = '{$student_id}'";
            mysqli_query($conn, $sql_update_level);
            
            // Set session untuk notifikasi level up
            $_SESSION['level_up'] = true;
            $_SESSION['old_level'] = $current_level;
            $_SESSION['new_level'] = $new_level;
        }
        
        // Simpan info progress untuk ditampilkan
        $_SESSION['pass_percentage'] = number_format($pass_percentage, 1);
        $_SESSION['total_passed_modules'] = $total_passed_modules;
        $_SESSION['total_all_modules'] = $total_all_modules;
        // ===== END SISTEM NAIK LEVEL =====
        
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
