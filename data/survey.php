<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

// Get survey questions for DataTable
if ($_GET['action'] == 'getSurvey') {
    $columns = array(
        0 => 'id',
        1 => 'question',
        2 => 'category',
        3 => 'mapel',
        4 => 'action'
    );

    // Using the query provided by user with JOIN
    $sql = "SELECT a.*, b.mapel 
            FROM survey_question a
            LEFT JOIN pelajaran b ON a.id_mapel = b.id_mapel";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);

    $totalData = $count;
    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $sql = "SELECT a.*, b.mapel 
                FROM survey_question a
                LEFT JOIN pelajaran b ON a.id_mapel = b.id_mapel
                ORDER BY a.{$order} {$dir} 
                LIMIT {$limit} OFFSET {$start}";
        $result = mysqli_query($conn, $sql);
    } else {
        $search = mysqli_real_escape_string($conn, $_POST['search']['value']);
        $sql = "SELECT a.*, b.mapel 
                FROM survey_question a
                LEFT JOIN pelajaran b ON a.id_mapel = b.id_mapel
                WHERE a.question LIKE '%{$search}%' 
                   OR b.mapel LIKE '%{$search}%'
                ORDER BY a.{$order} {$dir} 
                LIMIT {$limit} OFFSET {$start}";
        $result = mysqli_query($conn, $sql);

        $count = mysqli_num_rows($result);
        $totalFiltered = $count;
    }

    $data = array();
    if (!empty($result)) {
        $no = $start + 1;
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($rows as $r) {
            $nestedData['no'] = $no;
            $nestedData['question'] = $r['question'];
            $nestedData['category'] = $r['category'];
            $nestedData['mapel'] = $r['mapel'] ?? '-';
            
            if ($_SESSION['level_user'] == 1) {
                $nestedData['action'] = "
                    <button class='btn btn-warning btn-sm btn-action' id='btn-edit' data='{$r['id']}'>
                        <i class='fas fa-edit'></i> Edit
                    </button>
                    <button class='btn btn-danger btn-sm btn-action' id='btn-delete' data='{$r['id']}'>
                        <i class='fas fa-trash'></i> Hapus
                    </button>
                ";
            } else {
                $nestedData['action'] = '-';
            }
            
            $data[] = $nestedData;
            $no++;
        }
    }

    $json_data = array(
        "draw" => intval($_POST['draw']),
        "recordsTotal" => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data" => $data
    );

    echo json_encode($json_data);
}

// Add survey question
if ($_GET['action'] == 'tambahSurvey') {
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $id_mapel = mysqli_real_escape_string($conn, $_POST['id_mapel']);

    $sql = "INSERT INTO survey_question (question, category, id_mapel) 
            VALUES ('{$question}', '{$category}', '{$id_mapel}')";
    $query = mysqli_query($conn, $sql);
    
    if (!$query) {
        echo mysqli_error($conn);
    } else {
        echo json_encode(['status' => 'success']);
    }
}

// Get survey question by ID
if ($_GET['action'] == 'getSurveyById') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "SELECT * FROM survey_question WHERE id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);

    $data = array(
        'id' => $result['id'],
        'question' => $result['question'],
        'category' => $result['category'],
        'id_mapel' => $result['id_mapel']
    );
    
    echo json_encode($data);
}

// Edit survey question
if ($_GET['action'] == 'editSurvey') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $id_mapel = mysqli_real_escape_string($conn, $_POST['id_mapel']);

    $sql = "UPDATE survey_question 
            SET question = '{$question}', 
                category = '{$category}',
                id_mapel = '{$id_mapel}'
            WHERE id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    
    if (!$query) {
        echo mysqli_error($conn);
    } else {
        echo json_encode(['status' => 'success']);
    }
}

// Delete survey question
if ($_GET['action'] == 'hapusSurvey') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $sql = "DELETE FROM survey_question WHERE id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    
    if (!$query) {
        echo mysqli_error($conn);
    } else {
        echo json_encode(['status' => 'success']);
    }
}
