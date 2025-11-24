<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $student_id = $_SESSION['student_id'];
    
    // Array untuk menyimpan hasil per modul
    $module_results = array();
    $module_answers = array();
    
    // Inisialisasi array untuk 7 modul
    for ($i = 1; $i <= 7; $i++) {
        $module_answers[$i] = array();
    }
    
    // Process semua jawaban
    foreach ($_POST as $key => $user_answer_index) {
        if (strpos($key, 'question') === 0) {
            $question_id = substr($key, 8);
            
            // Get question info dan correct answer (dalam bentuk index)
            $sql = "SELECT module_id, answer FROM module_question WHERE id = '{$question_id}'";
            $query = mysqli_query($conn, $sql);
            $question_data = mysqli_fetch_array($query, MYSQLI_ASSOC);
            $module_id = $question_data['module_id'];
            $correct_answer_index = (int)$question_data['answer']; // Index jawaban benar: 0, 1, 2, 3
            
            // Convert user input to integer
            $user_answer_index = (int)$user_answer_index;
            
            // Bandingkan index user dengan correct answer index
            $is_correct = ($user_answer_index === $correct_answer_index) ? 1 : 0;
            
            // Simpan ke array modul
            $module_answers[$module_id][] = $is_correct;
            
            // Insert ke table pretest_modul (detail jawaban)
            $insert_detail = "INSERT INTO pretest_modul (student_id, module_id, question_id, answer_id, is_correct) 
                             VALUES ('{$student_id}', '{$module_id}', '{$question_id}', '{$user_answer_index}', '{$is_correct}')";
            mysqli_query($conn, $insert_detail);
        }
    }
    
    // Hitung score untuk setiap modul
    foreach ($module_answers as $module_id => $answers) {
        if (!empty($answers)) {
            $correct_count = array_sum($answers);
            $total_questions = count($answers);
            
            // Hitung score berdasarkan ketentuan baru
            if ($correct_count == 0) {
                $score = 0;
            } else if ($correct_count == 1) {
                $score = 50;
            } else if ($correct_count == 2) {
                $score = 85;
            } else if ($correct_count >= 3) {
                $score = 100;
            } else {
                $score = 0;
            }
            
            // HITUNG LEVEL BERDASARKAN JAWABAN BENAR (sama seperti post-test)
            if ($correct_count <= 1) {
                $recommended_level = 1; // 0-1 benar = Level 1
            } else if ($correct_count == 2) {
                $recommended_level = 2; // 2 benar = Level 2
            } else {
                $recommended_level = 3; // 3+ benar = Level 3
            }
            
            $module_results[] = array(
                'module_id' => $module_id,
                'correct_answers' => $correct_count,
                'total_questions' => $total_questions,
                'score' => $score
            );
            
            // Insert ke table result_hasil_pretest dengan LEVEL YANG DIHITUNG
            $insert_result = "INSERT INTO result_hasil_pretest 
                             (student_id, module_id, total_questions, correct_answers, score, recommended_level, method) 
                             VALUES ('{$student_id}', '{$module_id}', '{$total_questions}', '{$correct_count}', '{$score}', '{$recommended_level}', 'PRE-TEST')
                             ON DUPLICATE KEY UPDATE 
                             total_questions = '{$total_questions}',
                             correct_answers = '{$correct_count}',
                             score = '{$score}',
                             recommended_level = '{$recommended_level}',
                             method = 'PRE-TEST',
                             updated_at = CURRENT_TIMESTAMP";
            mysqli_query($conn, $insert_result);
        }
    }
    
    // Call GNN API untuk mendapatkan prediksi
    $gnn_prediction = callGNNAPI($student_id, $module_results);
    
    // Hitung average level dari result_hasil_pretest (level yang sudah dihitung)
    $sql_avg = "SELECT ROUND(AVG(recommended_level)) as avg_level 
                FROM result_hasil_pretest 
                WHERE student_id = '{$student_id}'";
    $result_avg = mysqli_query($conn, $sql_avg);
    $row_avg = mysqli_fetch_assoc($result_avg);
    $calculated_level = $row_avg['avg_level'] ? (int)$row_avg['avg_level'] : 1;
    
    if ($gnn_prediction && isset($gnn_prediction['predictions'])) {
        // Update hasil dengan prediksi GNN (hanya confidence, jangan override level yang sudah benar)
        foreach ($gnn_prediction['predictions'] as $prediction) {
            $module_id = $prediction['module_id'];
            $gnn_pred = $prediction['predicted_level'];
            $confidence = $prediction['confidence'];
            
            // Simpan prediksi GNN, tapi gunakan max antara level yang dihitung dan GNN
            $update_gnn = "UPDATE result_hasil_pretest 
                          SET gnn_prediction = '{$confidence}',
                              gnn_confidence = '{$confidence}',
                              gnn_predicted_level = '{$gnn_pred}'
                          WHERE student_id = '{$student_id}' 
                          AND module_id = '{$module_id}'";
            mysqli_query($conn, $update_gnn);
        }
        
        // Gunakan level tertinggi antara calculated dan GNN overall_level
        $gnn_overall_level = $gnn_prediction['overall_level'];
        $final_level = max($calculated_level, $gnn_overall_level);
    } else {
        // Jika GNN tidak tersedia, gunakan level yang sudah dihitung
        $final_level = $calculated_level;
    }
    
    // Simpan final level ke pre_test_result
    $insert_overall = "INSERT INTO pre_test_result (student_id, level) 
                      VALUES ('{$student_id}', '{$final_level}')
                      ON DUPLICATE KEY UPDATE level = '{$final_level}'";
    mysqli_query($conn, $insert_overall);
    
    // Simpan ke level_student
    $insert_level = "INSERT INTO level_student (student_id, level) 
                    VALUES ('{$student_id}', '{$final_level}')
                    ON DUPLICATE KEY UPDATE level = '{$final_level}'";
    mysqli_query($conn, $insert_level);
    
    // Refresh session level
    $_SESSION['level'] = $final_level;
    $_SESSION['debug_pretest_calc'] = "Calculated: {$calculated_level}, GNN: " . ($gnn_prediction ? $gnn_overall_level : 'N/A') . ", Final: {$final_level}";
    
    // Set session message
    $_SESSION['pretest_message'] = 'Pre-test berhasil diselesaikan! Lihat hasil dan rekomendasi modul Anda.';
    
    // Redirect ke halaman hasil pretest (NEW)
    header('location: ../student/hasil-pretest.php');
}

/**
 * Call GNN API untuk mendapatkan prediksi level
 */
function callGNNAPI($student_id, $module_results) {
    // Try multiple ports for GNN API
    $api_ports = [5001, 5000, 5002]; // Try 5001 first, then 5000, then 5002
    $api_url = null;
    
    foreach ($api_ports as $port) {
        $test_url = "http://localhost:{$port}/health";
        $ch_test = curl_init($test_url);
        curl_setopt($ch_test, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_test, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch_test, CURLOPT_CONNECTTIMEOUT, 1);
        $test_result = curl_exec($ch_test);
        $http_code = curl_getinfo($ch_test, CURLINFO_HTTP_CODE);
        curl_close($ch_test);
        
        if ($http_code == 200) {
            $api_url = "http://localhost:{$port}/predict";
            break;
        }
    }
    
    if (!$api_url) {
        error_log("GNN API not available on any port. Using fallback.");
        return null;
    }
    
    $data = array(
        'student_id' => $student_id,
        'module_results' => $module_results
    );
    
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'timeout' => 10
        )
    );
    
    $context  = stream_context_create($options);
    $result = @file_get_contents($api_url, false, $context);
    
    if ($result === FALSE) {
        // Jika API gagal, gunakan perhitungan fallback
        return calculateFallbackLevel($module_results);
    }
    
    return json_decode($result, true);
}

/**
 * Fallback calculation jika GNN API tidak tersedia
 */
function calculateFallbackLevel($module_results) {
    $scores = array();
    $predictions = array();
    
    foreach ($module_results as $module) {
        $score = $module['score'];
        $scores[] = $score;
        
        // Simple rule-based prediction
        if ($score >= 85) {
            $level = 3;
        } else if ($score >= 50) {
            $level = 2;
        } else {
            $level = 1;
        }
        
        $predictions[] = array(
            'module_id' => $module['module_id'],
            'score' => $score,
            'predicted_level' => $level,
            'confidence' => 0.5
        );
    }
    
    $avg_score = array_sum($scores) / count($scores);
    
    if ($avg_score >= 85) {
        $overall_level = 3;
    } else if ($avg_score >= 50) {
        $overall_level = 2;
    } else {
        $overall_level = 1;
    }
    
    return array(
        'student_id' => null,
        'predictions' => $predictions,
        'overall_level' => $overall_level,
        'average_score' => $avg_score,
        'recommended_start_module' => 1
    );
}