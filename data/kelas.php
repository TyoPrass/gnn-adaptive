<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

// Mengambil data kelas untuk dataTable kelas
if ($_GET['action'] == 'getKelas') {
    $columns = array(
        0 => 'no',
        1 => 'kelas',
        2 => 'action'
    );

    $sql = "SELECT * FROM class";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);

    $totalData = $count;
    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];


    if (empty($_POST['search']['value'])) {
        // $result = mysqli_query($conn, "SELECT * FROM class order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");
        $result = mysqli_query($conn, "SELECT * FROM class");
    } else {
        $search = $_POST['search']['value'];
        $result = mysqli_query($conn, "SELECT * FROM class WHERE class_name like '%{$search}%' order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");

        $count = mysqli_num_rows($result);
        $totalData = $count;
        $totalFiltered = $totalData;
    }

    $data = array();
    // var_dump($result);
    if (!empty($result)) {
        $no = $start + 1;
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($row as $r) {
            $sql = "SELECT * FROM student WHERE class_id = '{$r['id']}'";
            $query = mysqli_query($conn, $sql);
            $jumlah = mysqli_num_rows($query);

            $nestedData['no'] = $no;
            $nestedData['kelas'] = $r["class_name"];
            $nestedData['jumlah_murid'] = $jumlah;
            $nestedData['action'] = "<a href='javascript:void(0)' id='btn-edit' data='{$r['id']}' class='btn btn-warning text-white'><i class='bi bi-pencil-fill'></i></a>
            &emsp;<a href='javascript:void(0)' data='{$r['id']}' id='btn-delete' class='btn btn-danger text-white'><i class='bi bi-trash'></i></a>";
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

// Menyimpan data kelas
if ($_GET['action'] == 'tambahKelas') {
    $kelas = $_POST['kelas'];
    $sql = "INSERT INTO class (class_name) VALUES('{$kelas}')";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo mysqli_error($conn);
    } else {
    }
}

// fungsi mengambil data 1 kelas berdasarkan id Kelas
if ($_GET['action'] == 'getKelasById') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM class WHERE id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);

    $data = array(
        'id' => $result['id'],
        'kelas' => $result['class_name'],
    );
    echo json_encode($data);
}

// fungsi edit kelas
if ($_GET['action'] == 'editKelas') {
    $id = $_POST['id'];
    $class_name = $_POST['kelas'];
    // var_dump($id);
    $sql = "UPDATE class set class_name = '{$class_name}' WHERE id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        echo mysqli_error($conn);
    }
}

// fungsi hapus kelas
if ($_GET['action'] == 'hapusKelas') {
    $id = $_POST['id'];
    $sql = "DELETE FROM class where id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        echo mysqli_error($conn);
    }
}

// mengambil data kelas untuk dataTable di hitung Pre test
if ($_GET['action'] == 'getKelasPost') {
    $columns = array(
        0 => 'no',
        1 => 'kelas',
        2 => 'action'
    );

    $sql = "SELECT * FROM class";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);

    $totalData = $count;
    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];


    if (empty($_POST['search']['value'])) {
        // $result = mysqli_query($conn, "SELECT * FROM class order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");
        $result = mysqli_query($conn, "SELECT * FROM class");
    } else {
        $search = $_POST['search']['value'];
        $result = mysqli_query($conn, "SELECT * FROM class WHERE class_name like '%{$search}%' order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");

        $count = mysqli_num_rows($result);
        $totalData = $count;
        $totalFiltered = $totalData;
    }

    $data = array();
    // var_dump($result);
    if (!empty($result)) {
        $no = $start + 1;
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // var_dump($row);
        foreach ($row as $r) {
            $sql = "SELECT * FROM student WHERE class_id = '{$r['id']}'";
            $query = mysqli_query($conn, $sql);
            $student = mysqli_fetch_all($query, MYSQLI_ASSOC);
            $jumlah = mysqli_num_rows($query);

            $jumlah_ambil_pre_test = 0;
            $student_id = 0;
            foreach ($student as $s) {
                $sql = "SELECT * FROM pre_test_answer WHERE student_id = '{$s['user_id']}'";
                $query = mysqli_query($conn, $sql);
                if (mysqli_num_rows($query) > 0) {
                    $jumlah_ambil_pre_test++;
                }
            }

            $jumlah_ambil_survey = 0;
            // $student_id = 0;
            foreach ($student as $s) {
                $sql = "SELECT * FROM survey_result WHERE student_id = '{$s['user_id']}'";
                $query = mysqli_query($conn, $sql);
                if (mysqli_num_rows($query) > 0) {
                    $jumlah_ambil_survey++;
                }
            }

            $jumlah_murid = mysqli_num_rows($query);

            $jumlah_hasil_pre_test = 0;
            foreach ($student as $s) {
                $sql = "SELECT * FROM pre_test_result WHERE student_id = '{$s['user_id']}'";
                $query = mysqli_query($conn, $sql);

                if (mysqli_num_rows($query) > 0) {
                    $jumlah_hasil_pre_test++;
                }
            }

            $nestedData['no'] = $no;
            $nestedData['id'] = $r['id']; // Add class ID to response
            $nestedData['kelas'] = $r["class_name"];
            $nestedData['jumlah_murid'] = $jumlah;
            $nestedData['ambil_post'] = $jumlah_ambil_pre_test;
            $nestedData['hasil_pre_test'] = $jumlah_hasil_pre_test;
            $nestedData['ambil_survey'] = $jumlah_ambil_survey;
            $sudah_ambil = false;
            $bisa_hitung = false;
            
            // KODE LAMA
            // if ($jumlah == $jumlah_ambil_pre_test && $jumlah == $jumlah_ambil_survey) {
            //     $sudah_ambil = '';
            // } else {
            //     $sudah_ambil = 'disabled';
            // }

            if($jumlah_hasil_pre_test == $jumlah_ambil_survey && $jumlah_hasil_pre_test == $jumlah_ambil_pre_test){
                $bisa_ambil = 'disabled';
            }else{
                $bisa_ambil = '';
            }


            if($jumlah_ambil_pre_test == $jumlah_ambil_survey){
                $bisa_hitung = true;
            }


            // if (mysqli_num_rows($query) > 0) {
            if ($jumlah == 0) {
                $nestedData['action'] = "<button type='button' disabled id='btn-edit' data='{$r['id']}' class='btn btn-primary text-white'>BELUM ADA MURID</button>";
            } else  if ($jumlah == $jumlah_ambil_pre_test && $jumlah_hasil_pre_test == $jumlah_ambil_pre_test) {
                $nestedData['action'] = "<button type='button' disabled id='btn-edit' data='{$r['id']}' class='btn btn-primary text-white'>LEVEL SUDAH DIHITUNG</button>";
            } else {
                $nestedData['action'] = "<form action='../data/hitung_pretest.php' method='POST'><input type='hidden' name='id' value='{$r['id']}'><button type='submit' id='btn-edit' data='{$r['id']}' class='btn btn-success text-white' " . $bisa_ambil . ">HITUNG LEVEL</button></form>";
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