<?php
include('../config/db.php');

// Set header untuk JSON response
header('Content-Type: application/json');

// Fungsi untuk memendekkan teks
function truncateText($text, $length = 100) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'getSoalList':
            getSoalList();
            break;
        case 'getSoalDetail':
            getSoalDetail();
            break;
        case 'saveSoal':
            saveSoal();
            break;
        case 'deleteSoal':
            deleteSoal();
            break;
        case 'getModules':
            getModules();
            break;
        case 'getSoalStats':
            getSoalStats();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
}

function getSoalList() {
    global $conn;
    
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $search = $_POST['search']['value'] ?? '';
    $modul_filter = $_POST['modul'] ?? '';
    
    // Base query menggunakan struktur yang diminta
    $base_query = "FROM module AS a
                   JOIN module_question AS m ON m.module_id = a.id
                   JOIN module_question_choice AS mm ON mm.question_id = m.id
                   WHERE 1=1";
    
    // Add module filter if specified
    if (!empty($modul_filter)) {
        $base_query .= " AND a.id = '" . mysqli_real_escape_string($conn, $modul_filter) . "'";
    }
    
    // Add search filter
    if (!empty($search)) {
        $base_query .= " AND (a.module_desc LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                        OR m.question LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                        OR mm.answer_desc LIKE '%" . mysqli_real_escape_string($conn, $search) . "%')";
    }
    
    // Count total records (distinct questions)
    $count_query = "SELECT COUNT(DISTINCT m.id) as total " . $base_query;
    $count_result = mysqli_query($conn, $count_query);
    $total_records = mysqli_fetch_assoc($count_result)['total'];
    
    // Get data with pagination (distinct questions)
    $data_query = "SELECT DISTINCT 
                   m.id as question_id,
                   a.module_desc, 
                   m.question, 
                   m.answer as correct_answer,
                   a.id as module_id
                   " . $base_query . 
                   " ORDER BY m.id DESC LIMIT $start, $length";
    
    $data_result = mysqli_query($conn, $data_query);
    
    $data = [];
    $no = $start + 1;
    
    while ($row = mysqli_fetch_assoc($data_result)) {
        $actions = "
            <div class='btn-group' role='group'>
                <button type='button' class='btn btn-sm btn-info' onclick='viewSoal({$row['question_id']})' title='Lihat Detail'>
                    <i class='fas fa-eye'></i>
                </button>
                <button type='button' class='btn btn-sm btn-warning' onclick='editSoal({$row['question_id']})' title='Edit'>
                    <i class='fas fa-edit'></i>
                </button>
                <button type='button' class='btn btn-sm btn-danger' onclick='deleteSoal({$row['question_id']})' title='Hapus'>
                    <i class='fas fa-trash'></i>
                </button>
            </div>
        ";
        
        // Get all choices for this question dengan informasi jawaban benar berdasarkan index
        $choices_query = "SELECT answer_desc FROM module_question_choice 
                         WHERE question_id = '" . $row['question_id'] . "' 
                         ORDER BY id";
        $choices_result = mysqli_query($conn, $choices_query);
        
        $choices_display = '<div class="choices-container">';
        $choice_letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $choice_index = 0;
        
        while ($choice = mysqli_fetch_assoc($choices_result)) {
            $choice_text = htmlspecialchars($choice['answer_desc']);
            $is_correct = ($choice_index == $row['correct_answer']); // Compare dengan index, bukan text
            
            if ($is_correct) {
                $choices_display .= "<div class='choice-badge correct badge me-1 mb-1 p-2' style='font-size: 0.85em;'>";
                $choices_display .= "<i class='fas fa-check-circle me-1'></i>";
                $choices_display .= "<strong>" . $choice_letters[$choice_index] . ".</strong> " . truncateText($choice_text, 25);
                $choices_display .= "</div>";
            } else {
                $choices_display .= "<div class='choice-badge incorrect badge me-1 mb-1 p-2' style='font-size: 0.85em;'>";
                $choices_display .= $choice_letters[$choice_index] . ". " . truncateText($choice_text, 25);
                $choices_display .= "</div>";
            }
            $choice_index++;
        }
        
        $choices_display .= '</div>';
        
        // Jika tidak ada pilihan, tampilkan peringatan
        if ($choice_index === 0) {
            $choices_display = "<div class='choices-container'><span class='badge bg-warning p-2'><i class='fas fa-exclamation-triangle me-1'></i>Tidak ada pilihan</span></div>";
        }
        
        $data[] = [
            'no' => $no++,
            'module_desc' => htmlspecialchars($row['module_desc']),
            'question_text' => truncateText(htmlspecialchars($row['question']), 100),
            'correct_answer' => $choices_display,
            'action' => $actions
        ];
    }
    
    echo json_encode([
        'draw' => intval($draw),
        'recordsTotal' => $total_records,
        'recordsFiltered' => $total_records,
        'data' => $data
    ]);
}

function getSoalDetail() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    
    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID soal tidak valid']);
        return;
    }
    
    // Get question details menggunakan query yang diminta
    $question_query = "SELECT 
                       a.module_desc, 
                       m.question, 
                       m.answer as correct_answer,
                       m.module_id,
                       m.id as question_id
                       FROM module AS a
                       JOIN module_question AS m ON m.module_id = a.id
                       WHERE m.id = '" . mysqli_real_escape_string($conn, $id) . "'";
    
    $question_result = mysqli_query($conn, $question_query);
    
    if (mysqli_num_rows($question_result) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Soal tidak ditemukan']);
        return;
    }
    
    $question = mysqli_fetch_assoc($question_result);
    
    // Get choices for this question
    $choices_query = "SELECT mm.answer_desc, mm.question_id, mm.id as choice_id
                      FROM module_question_choice AS mm 
                      WHERE mm.question_id = '" . $id . "' 
                      ORDER BY mm.id";
    $choices_result = mysqli_query($conn, $choices_query);
    $choices = [];
    
    while ($choice = mysqli_fetch_assoc($choices_result)) {
        $choices[] = $choice;
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => [
            'question' => $question,
            'choices' => $choices
        ]
    ]);
}

function saveSoal() {
    global $conn;
    
    $question_id = $_POST['question_id'] ?? 0;
    $module_id = $_POST['module_id'] ?? '';
    $question_text = $_POST['question_text'] ?? '';
    $correct_answer_index = $_POST['correct_answer_index'] ?? '';
    $choices = $_POST['choices'] ?? [];
    
    // Validasi input
    if (empty($question_text)) {
        echo json_encode(['status' => 'error', 'message' => 'Pertanyaan tidak boleh kosong']);
        return;
    }
    
    if (empty($module_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Modul harus dipilih']);
        return;
    }
    
    if (count($choices) < 2) {
        echo json_encode(['status' => 'error', 'message' => 'Minimal harus ada 2 pilihan jawaban']);
        return;
    }
    
    if ($correct_answer_index === '') {
        echo json_encode(['status' => 'error', 'message' => 'Jawaban benar harus dipilih']);
        return;
    }
    
    // Validasi index jawaban benar
    $correct_answer_index = intval($correct_answer_index);
    if ($correct_answer_index < 0 || $correct_answer_index >= count($choices)) {
        echo json_encode(['status' => 'error', 'message' => 'Index jawaban benar tidak valid']);
        return;
    }
    
    mysqli_begin_transaction($conn);
    
    try {
        if ($question_id > 0) {
            // Update existing question - simpan index sebagai integer
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
        } else {
            // Insert new question - simpan index sebagai integer
            $insert_query = "INSERT INTO module_question (module_id, question, answer) VALUES (
                           '" . mysqli_real_escape_string($conn, $module_id) . "',
                           '" . mysqli_real_escape_string($conn, $question_text) . "',
                           " . $correct_answer_index . "
                           )";
            
            if (!mysqli_query($conn, $insert_query)) {
                throw new Exception('Gagal simpan pertanyaan: ' . mysqli_error($conn));
            }
            
            $new_question_id = mysqli_insert_id($conn);
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
        echo json_encode(['status' => 'success', 'message' => 'Soal berhasil disimpan']);
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function deleteSoal() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    
    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID soal tidak valid']);
        return;
    }
    
    mysqli_begin_transaction($conn);
    
    try {
        // Delete choices first
        $delete_choices = "DELETE FROM module_question_choice WHERE question_id = '" . mysqli_real_escape_string($conn, $id) . "'";
        if (!mysqli_query($conn, $delete_choices)) {
            throw new Exception('Gagal hapus pilihan jawaban: ' . mysqli_error($conn));
        }
        
        // Delete question
        $delete_question = "DELETE FROM module_question WHERE id = '" . mysqli_real_escape_string($conn, $id) . "'";
        if (!mysqli_query($conn, $delete_question)) {
            throw new Exception('Gagal hapus pertanyaan: ' . mysqli_error($conn));
        }
        
        mysqli_commit($conn);
        echo json_encode(['status' => 'success', 'message' => 'Soal berhasil dihapus']);
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getModules() {
    global $conn;
    
    $query = "SELECT id, module_desc FROM module ORDER BY module_desc";
    $result = mysqli_query($conn, $query);
    
    $modules = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $modules[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $modules
    ]);
}

function getSoalStats() {
    global $conn;
    
    // Count total questions
    $query = "SELECT COUNT(*) as total FROM module_question";
    $result = mysqli_query($conn, $query);
    $total_questions = mysqli_fetch_assoc($result)['total'];
    
    // Count total modules with questions
    $query_modules = "SELECT COUNT(DISTINCT module_id) as total_modules FROM module_question";
    $result_modules = mysqli_query($conn, $query_modules);
    $total_modules = mysqli_fetch_assoc($result_modules)['total_modules'];
    
    // Count total choices
    $query_choices = "SELECT COUNT(*) as total_choices FROM module_question_choice";
    $result_choices = mysqli_query($conn, $query_choices);
    $total_choices = mysqli_fetch_assoc($result_choices)['total_choices'];
    
    echo json_encode([
        'status' => 'success',
        'data' => [
            'total_questions' => $total_questions,
            'total_modules' => $total_modules,
            'total_choices' => $total_choices
        ]
    ]);
}
?>