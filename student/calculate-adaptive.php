<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

include('../config/db.php');
session_start();

header('Content-Type: application/json');

// Fungsi mode untuk menghitung modus
function mode($armodul)
{
    if (empty($armodul)) {
        return 1; // Return minimum level
    }
    
    $v = array_count_values($armodul);
    $total = 1; // Default ke level 1
    arsort($v);
    foreach ($v as $k => $v) {
        $total = $k;
        break;
    }
    return max(1, $total); // Pastikan tidak kurang dari 1
}

try {
    if (!isset($_SESSION['student_id'])) {
        echo json_encode(['success' => false, 'message' => 'Sesi tidak ditemukan. Silakan login kembali.']);
        exit;
    }

    $student_id = mysqli_real_escape_string($conn, $_SESSION['student_id']);

    // Cek apakah pre-test sudah diambil
    $check_pretest = mysqli_query($conn, "SELECT * FROM pre_test_answer WHERE student_id = '$student_id'");
    if (!$check_pretest || mysqli_num_rows($check_pretest) == 0) {
        echo json_encode(['success' => false, 'message' => 'Pre-test belum diambil.']);
        exit;
    }

    // Cek apakah sudah pernah dihitung
    $check_level = mysqli_query($conn, "SELECT * FROM level_student WHERE student_id = '$student_id'");
    if (!$check_level) {
        throw new Exception('Error query level: ' . mysqli_error($conn));
    }
    
    if (mysqli_num_rows($check_level) > 0) {
        echo json_encode(['success' => false, 'message' => 'Level sudah dihitung sebelumnya.']);
        exit;
    }

    // Ambil jawaban pre-test
    $answer_query = mysqli_query($conn, "SELECT * FROM pre_test_answer WHERE student_id = '$student_id'");
    $answer = mysqli_fetch_array($answer_query, MYSQLI_ASSOC);

    if (!$answer) {
        echo json_encode(['success' => false, 'message' => 'Data jawaban tidak ditemukan.']);
        exit;
    }

    // Susun data murid untuk perhitungan IRT
    $murid = array(
        $student_id => array(
            'modul_1' => max(1, intval($answer['modul_1'])),
            'modul_2' => max(1, intval($answer['modul_2'])),
            'modul_3' => max(1, intval($answer['modul_3'])),
            'modul_4' => max(1, intval($answer['modul_4'])),
            'modul_5' => max(1, intval($answer['modul_5'])),
            'modul_6' => max(1, intval($answer['modul_6'])),
            'modul_7' => max(1, intval($answer['modul_7'])),
        )
    );

    // Hitung P (probability)
    $p_user = array_sum($murid[$student_id]) / count($murid[$student_id]);
    
    // Adjustment untuk extreme values
    if ($p_user == 0) {
        $p_user = 0.1;
    } else if ($p_user == 1) {
        $p_user = 0.9;
    }
    
    $murid[$student_id]['p_user'] = $p_user;
    
    // Hitung ability
    $ability = log($p_user / (1 - $p_user));
    $murid[$student_id]['ability'] = $ability;

    // Hitung P per modul
    $sum_modul = array();
    for ($i = 1; $i <= 7; $i++) {
        $sum_modul[$i] = $murid[$student_id]['modul_' . $i];
    }

    // Hitung difficulty per modul
    $difficulty = array();
    $adj_difficulty = array();
    
    for ($i = 1; $i <= 7; $i++) {
        $p_modul = $sum_modul[$i];
        if ($p_modul == 0) {
            $p_modul = 0.1;
        } else if ($p_modul == 1) {
            $p_modul = 0.9;
        }
        $difficulty[$i] = log((1 - $p_modul) / $p_modul);
    }

    // Hitung average difficulty
    $difficulty_average = array_sum($difficulty) / count($difficulty);

    // Hitung adjusted difficulty
    for ($i = 1; $i <= 7; $i++) {
        $adj_difficulty[$i] = $difficulty[$i] - $difficulty_average;
    }

    // Iterasi pertama - Hitung expected value
    $iterasi_harap = array();
    for ($i = 1; $i <= 7; $i++) {
        $iterasi_harap[$i] = exp($ability - $adj_difficulty[$i]) / (1 + exp($ability - $adj_difficulty[$i]));
    }

    // Filter modul dengan probability >= 0.75 (sudah dikuasai)
    $moduls = array();
    for ($i = 1; $i <= 7; $i++) {
        if ($iterasi_harap[$i] < 0.75) {
            $moduls[] = $i;
        }
    }

    // Ambil level dari modul yang belum dikuasai
    $modul_level = array();
    foreach ($moduls as $mo) {
        $sql = "SELECT module_level FROM module WHERE id = '{$mo}'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $level_data = mysqli_fetch_array($query);
            if ($level_data) {
                $modul_level[] = max(1, intval($level_data['module_level'])); // Pastikan min level 1
            }
        }
    }

    // Hitung level pre-test
    if (sizeof($modul_level) == 0) {
        $level_pre_test = 3; // Semua modul sudah dikuasai
    } else {
        $level_pre_test = max(1, mode($modul_level)); // Pastikan min level 1
    }

    // Simpan hasil pre-test
    $insert_pretest = mysqli_query($conn, "INSERT INTO pre_test_result (student_id, level) VALUES('$student_id', '$level_pre_test')");
    
    if (!$insert_pretest) {
        throw new Exception('Gagal menyimpan hasil pre-test: ' . mysqli_error($conn));
    }

    // Ambil hasil survey
    $survey_query = mysqli_query($conn, "SELECT level_result FROM survey_result WHERE student_id = '$student_id'");
    $level_survey = 1; // Default level 1
    
    if ($survey_query && mysqli_num_rows($survey_query) > 0) {
        $survey_data = mysqli_fetch_array($survey_query, MYSQLI_ASSOC);
        $level_survey = max(1, intval($survey_data['level_result'])); // Pastikan min level 1
    }

    // Hitung level akhir (minimum dari pre-test dan survey)
    $level = max(1, min($level_pre_test, $level_survey)); // Pastikan min level 1

    // Simpan level student
    $insert_level = mysqli_query($conn, "INSERT INTO level_student (student_id, level) VALUES ('$student_id', '$level')");

    if (!$insert_level) {
        throw new Exception('Gagal menyimpan level: ' . mysqli_error($conn));
    }

    // Update session
    $_SESSION['level'] = $level;
    $_SESSION['test_processed'] = true;
    
    echo json_encode([
        'success' => true,
        'message' => 'Perhitungan berhasil!',
        'data' => [
            'level' => $level,
            'level_pre_test' => $level_pre_test,
            'level_survey' => $level_survey,
            'ability' => round($ability, 4),
            'modules_not_mastered' => $moduls
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?>
