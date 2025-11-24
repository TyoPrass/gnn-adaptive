<?php
session_start();
include('../config/db.php');

// Check login
if (!isset($_SESSION['name'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'] ?? '';
    $module_id = $_POST['module_id'] ?? '';
    $question_text = $_POST['question_text'] ?? '';
    $correct_answer_index = $_POST['correct_answer'] ?? '';
    $choices = $_POST['choices'] ?? [];
    
    // Validasi input
    if (empty($question_text)) {
        echo json_encode(['status' => 'error', 'message' => 'Pertanyaan tidak boleh kosong']);
        exit;
    }
    
    if (empty($module_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Modul harus dipilih']);
        exit;
    }
    
    if (count($choices) < 2) {
        echo json_encode(['status' => 'error', 'message' => 'Minimal harus ada 2 pilihan jawaban']);
        exit;
    }
    
    if ($correct_answer_index === '') {
        echo json_encode(['status' => 'error', 'message' => 'Jawaban benar harus dipilih']);
        exit;
    }
    
    // Validasi index jawaban benar
    $correct_answer_index = intval($correct_answer_index);
    if ($correct_answer_index < 0 || $correct_answer_index >= count($choices)) {
        echo json_encode(['status' => 'error', 'message' => 'Index jawaban benar tidak valid']);
        exit;
    }
    
    mysqli_begin_transaction($conn);
    
    try {
        if (!empty($question_id)) {
            // Update existing question
            $update_query = "UPDATE module_question SET 
                           module_id = '" . mysqli_real_escape_string($conn, $module_id) . "',
                           question = '" . mysqli_real_escape_string($conn, $question_text) . "',
                           answer = " . $correct_answer_index . "
                           WHERE id = '" . mysqli_real_escape_string($conn, $question_id) . "'";
            
            if (!mysqli_query($conn, $update_query)) {
                throw new Exception('Gagal update pertanyaan: ' . mysqli_error($conn));
            }
            
            // Delete existing choices
            $delete_choices = "DELETE FROM module_question_choice WHERE question_id = '" . $question_id . "'";
            if (!mysqli_query($conn, $delete_choices)) {
                throw new Exception('Gagal hapus pilihan jawaban lama: ' . mysqli_error($conn));
            }
            
            $new_question_id = $question_id;
            $message = 'Soal berhasil diupdate';
        } else {
            // Insert new question
            $insert_query = "INSERT INTO module_question (module_id, question, answer) VALUES (
                           '" . mysqli_real_escape_string($conn, $module_id) . "',
                           '" . mysqli_real_escape_string($conn, $question_text) . "',
                           " . $correct_answer_index . "
                           )";
            
            if (!mysqli_query($conn, $insert_query)) {
                throw new Exception('Gagal simpan pertanyaan: ' . mysqli_error($conn));
            }
            
            $new_question_id = mysqli_insert_id($conn);
            $message = 'Soal berhasil ditambahkan';
        }
        
        // Insert choices
        foreach ($choices as $choice) {
            if (!empty(trim($choice))) {
                $choice_query = "INSERT INTO module_question_choice (question_id, answer_desc) VALUES (
                               '" . $new_question_id . "',
                               '" . mysqli_real_escape_string($conn, $choice) . "'
                               )";
                
                if (!mysqli_query($conn, $choice_query)) {
                    throw new Exception('Gagal simpan pilihan jawaban: ' . mysqli_error($conn));
                }
            }
        }
        
        mysqli_commit($conn);
        echo json_encode(['status' => 'success', 'message' => $message]);
        exit;
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
