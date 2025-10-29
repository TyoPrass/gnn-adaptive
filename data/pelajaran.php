<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

// mengambil data pelajaran untuk dataTable
if ($_GET['action'] == 'getPelajaran') {
    $columns = array(
        0 => 'id_mapel',
        1 => 'mapel',
        2 => 'jumlah_topik',
        3 => 'action'
    );

    $sql = "SELECT p.*, COUNT(t.id) as jumlah_topik 
            FROM pelajaran p 
            LEFT JOIN topic t ON p.id_mapel = t.id_mapel 
            GROUP BY p.id_mapel, p.mapel";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);

    $totalData = $count;
    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $result = mysqli_query($conn, "SELECT p.*, COUNT(t.id) as jumlah_topik 
            FROM pelajaran p 
            LEFT JOIN topic t ON p.id_mapel = t.id_mapel 
            GROUP BY p.id_mapel, p.mapel 
            ORDER BY {$order} {$dir} LIMIT {$limit} OFFSET {$start}");
    } else {
        $search = $_POST['search']['value'];
        $result = mysqli_query($conn, "SELECT p.*, COUNT(t.id) as jumlah_topik 
            FROM pelajaran p 
            LEFT JOIN topic t ON p.id_mapel = t.id_mapel 
            WHERE p.mapel LIKE '%{$search}%' 
            GROUP BY p.id_mapel, p.mapel 
            ORDER BY {$order} {$dir} LIMIT {$limit} OFFSET {$start}");

        $count = mysqli_num_rows($result);
        $totalData = $count;
        $totalFiltered = $totalData;
    }

    $data = array();
    if (!empty($result)) {
        $no = $start + 1;
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($row as $r) {
            $nestedData['id'] = $r['id_mapel'];
            $nestedData['no'] = $no;
            $nestedData['mapel'] = "<strong class='text-primary'><i class='fas fa-graduation-cap me-2'></i>{$r['mapel']}</strong>";
            $nestedData['jumlah_topik'] = "<span class='badge bg-info'><i class='fas fa-book me-1'></i>{$r['jumlah_topik']} Topik</span>";
            $nestedData['action'] = "<div class='d-flex gap-2'>
                <button class='btn btn-edit btn-sm' id='btn-edit' data='{$r['id_mapel']}'>
                    <i class='fas fa-edit me-1'></i>Edit
                </button>
                <button class='btn btn-delete btn-sm' id='btn-delete' data='{$r['id_mapel']}'>
                    <i class='fas fa-trash me-1'></i>Hapus
                </button>
            </div>";
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

// fungsi tambah pelajaran
if ($_GET['action'] == 'tambahPelajaran') {
    $mapel = mysqli_real_escape_string($conn, $_POST['mapel']);
    $sql = "INSERT INTO pelajaran (mapel) VALUES('{$mapel}')";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo mysqli_error($conn);
    }
}

// fungsi mengambil 1 data pelajaran dari database
if ($_GET['action'] == 'getPelajaranById') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM pelajaran WHERE id_mapel = '{$id}'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);

    $data = array(
        'id' => $result['id_mapel'],
        'mapel' => $result['mapel']
    );
    echo json_encode($data);
}

// fungsi edit pelajaran
if ($_GET['action'] == 'editPelajaran') {
    $id = $_POST['id_mapel'];
    $mapel = mysqli_real_escape_string($conn, $_POST['mapel']);
    
    $sql = "UPDATE pelajaran SET mapel = '{$mapel}' WHERE id_mapel = '{$id}'";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        echo mysqli_error($conn);
    }
}

// fungsi hapus pelajaran
if ($_GET['action'] == 'hapusPelajaran') {
    $id = $_POST['id'];
    
    // Set id_mapel to NULL for topics that reference this pelajaran
    $update_sql = "UPDATE topic SET id_mapel = NULL WHERE id_mapel = '{$id}'";
    mysqli_query($conn, $update_sql);
    
    // Delete the pelajaran
    $sql = "DELETE FROM pelajaran WHERE id_mapel = '{$id}'";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        echo mysqli_error($conn);
    }
}
