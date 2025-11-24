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
    
    // ===== HITUNG LEVEL PER MODUL BERDASARKAN JAWABAN BENAR =====
    // Logika: 0-1 benar = Level 1, 2 benar = Level 2, 3+ benar (semua) = Level 3
    $recommended_level = 1; // Default level 1
    
    if ($correct_answers >= 3) {
        // Jika benar semua atau hampir semua (3+) -> Level 3
        $recommended_level = 3;
    } elseif ($correct_answers == 2) {
        // Jika benar 2 -> Level 2
        $recommended_level = 2;
    } else {
        // Jika benar 0-1 -> Level 1
        $recommended_level = 1;
    }
    
    // Save to post_test_adaptive_result
    $sql = "INSERT INTO post_test_adaptive_result (student_id, module_id, correct_answers, total_questions, score, status, created_at) 
            VALUES ('{$student_id}', '{$module_id}', {$correct_answers}, {$total_questions}, {$percentage}, '{$status}', NOW())";
    $query = mysqli_query($conn, $sql);
    
    if (!$query) {
        $_SESSION['error_message'] = "Error menyimpan hasil post test: " . mysqli_error($conn);
        header('location: ../student/post_test.php?module=' . $module_id);
        exit();
    }
    
    // ===== SAVE TO result_hasil_pretest UNTUK TRACKING PER MODUL =====
    // SELALU INSERT/UPDATE ke result_hasil_pretest (baik lulus maupun gagal)
    $sql_check_result = "SELECT * FROM result_hasil_pretest WHERE student_id = '{$student_id}' AND module_id = '{$module_id}'";
    $query_check_result = mysqli_query($conn, $sql_check_result);
    
    $insert_success = false;
    
    if (!$query_check_result) {
        error_log("ERROR checking result_hasil_pretest: " . mysqli_error($conn));
        $_SESSION['debug_error'] = "Check error: " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($query_check_result) > 0) {
            // Update jika sudah ada
            $existing = mysqli_fetch_assoc($query_check_result);
            $existing_score = floatval($existing['score']);
            $new_score = floatval($percentage);
            
            // Update SELALU (bukan hanya jika lebih baik)
            $sql_update_result = "UPDATE result_hasil_pretest 
                                  SET correct_answers = {$correct_answers}, 
                                      total_questions = {$total_questions}, 
                                      score = {$percentage}, 
                                      recommended_level = {$recommended_level},
                                      method = 'POST-TEST',
                                      updated_at = NOW()
                                  WHERE student_id = '{$student_id}' AND module_id = '{$module_id}'";
            $query_update = mysqli_query($conn, $sql_update_result);
            
            if (!$query_update) {
                error_log("ERROR updating result_hasil_pretest: " . mysqli_error($conn));
                $_SESSION['debug_error'] = "Update error: " . mysqli_error($conn);
            } else {
                $insert_success = true;
                $_SESSION['debug_success'] = "Updated result_hasil_pretest: Module {$module_id}, Level {$recommended_level}";
            }
        } else {
            // Insert baru jika belum ada
            $sql_insert_result = "INSERT INTO result_hasil_pretest 
                                  (student_id, module_id, correct_answers, total_questions, score, recommended_level, method, created_at) 
                                  VALUES ('{$student_id}', '{$module_id}', {$correct_answers}, {$total_questions}, {$percentage}, {$recommended_level}, 'POST-TEST', NOW())";
            $query_insert = mysqli_query($conn, $sql_insert_result);
            
            if (!$query_insert) {
                error_log("ERROR inserting to result_hasil_pretest: " . mysqli_error($conn));
                $_SESSION['debug_error'] = "Insert error: " . mysqli_error($conn);
            } else {
                $insert_success = true;
                $_SESSION['debug_success'] = "Inserted to result_hasil_pretest: Module {$module_id}, Correct {$correct_answers}, Level {$recommended_level}";
            }
        }
    }
    // ===== END SAVE TO result_hasil_pretest =====
    
    // ===== HITUNG DAN UPDATE LEVEL SISWA (SELALU, TIDAK PEDULI LULUS/GAGAL) =====
    // Hitung rata-rata level dari semua modul di result_hasil_pretest
    $sql_avg_level = "SELECT 
                        ROUND(AVG(recommended_level)) as avg_level,
                        AVG(score) as avg_score,
                        COUNT(*) as total_module_tested
                      FROM result_hasil_pretest 
                      WHERE student_id = '{$student_id}'";
    $query_avg = mysqli_query($conn, $sql_avg_level);
    $avg_data = mysqli_fetch_assoc($query_avg);
    $average_level = intval($avg_data['avg_level'] ?? 1);
    
    // Hitung persentase modul yang lulus
    $sql_passed = "SELECT COUNT(DISTINCT module_id) as passed FROM post_test_adaptive_result 
                   WHERE student_id = '{$student_id}' AND status = 'lulus'";
    $query_passed = mysqli_query($conn, $sql_passed);
    $passed_data = mysqli_fetch_assoc($query_passed);
    $passed_modules = intval($passed_data['passed'] ?? 0);
    
    $sql_total = "SELECT COUNT(DISTINCT id) as total FROM module";
    $query_total = mysqli_query($conn, $sql_total);
    $total_data = mysqli_fetch_assoc($query_total);
    $total_modules = intval($total_data['total'] ?? 7);
    
    $pass_percentage = ($total_modules > 0) ? ($passed_modules / $total_modules) * 100 : 0;
    
    // Level dari persentase kelulusan
    $percentage_based_level = 1;
    if ($pass_percentage >= 80) {
        $percentage_based_level = 3;
    } elseif ($pass_percentage >= 50) {
        $percentage_based_level = 2;
    }
    
    // Ambil level terbaik
    $best_level = max($average_level, $percentage_based_level);
    
    // SELALU update level_student dengan level terbaik
    $sql_update_level = "UPDATE level_student SET level = '{$best_level}' WHERE student_id = '{$student_id}'";
    mysqli_query($conn, $sql_update_level);
    
    // Update pre_test_result juga
    $sql_check_pretest = "SELECT * FROM pre_test_result WHERE student_id = '{$student_id}'";
    $query_check_pretest = mysqli_query($conn, $sql_check_pretest);
    if (mysqli_num_rows($query_check_pretest) > 0) {
        mysqli_query($conn, "UPDATE pre_test_result SET level = '{$best_level}' WHERE student_id = '{$student_id}'");
    } else {
        mysqli_query($conn, "INSERT INTO pre_test_result (student_id, level) VALUES ('{$student_id}', '{$best_level}')");
    }
    
    // Update session
    $_SESSION['level'] = $best_level;
    $_SESSION['debug_level_calc'] = "Avg: {$average_level}, Pass%: {$pass_percentage}%, PercentLevel: {$percentage_based_level}, Best: {$best_level}";
    // ===== END UPDATE LEVEL =====
    
    // Refresh session level dari database untuk memastikan data terbaru
    $sql_get_current_level = "SELECT level FROM level_student WHERE student_id = '{$student_id}'";
    $query_get_level = mysqli_query($conn, $sql_get_current_level);
    if ($query_get_level && mysqli_num_rows($query_get_level) > 0) {
        $current_level_refresh = mysqli_fetch_assoc($query_get_level);
        $_SESSION['level'] = $current_level_refresh['level'];
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
        
        // Simpan info progress untuk ditampilkan (sudah dihitung di atas)
        $_SESSION['pass_percentage'] = number_format($pass_percentage, 1);
        $_SESSION['total_passed_modules'] = $passed_modules;
        $_SESSION['total_all_modules'] = $total_modules;
        
        // Cek apakah ada level up untuk notifikasi
        $sql_old_level = "SELECT level FROM level_student WHERE student_id = '{$student_id}'";
        $query_old = mysqli_query($conn, $sql_old_level);
        if ($query_old && mysqli_num_rows($query_old) > 0) {
            $old_data = mysqli_fetch_assoc($query_old);
            $old_level = intval($old_data['level']);
            
            if ($best_level > $old_level) {
                $_SESSION['level_up'] = true;
                $_SESSION['old_level'] = $old_level;
                $_SESSION['new_level'] = $best_level;
            }
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
