<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

// Menyajikan data untuk dataTable guru
if ($_GET['action'] == 'getGuru') {
    $columns = array(
        0 => 'id',
        1 => 'teacher_name',
        2 => 'nip',
        3 => 'teacher_address',
        4 => 'phone_number',
        5 => 'teacher_type',
        6 => 'class_id',
        7 => 'action',
    );

    // Mengambil data guru
    $sql = "SELECT * FROM teachers";
    $query = mysqli_query($conn, $sql);
    // hitung total data
    $count = mysqli_num_rows($query);

    $totalData = $count;
    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        // jika kolom pencarian diisi
        $result = mysqli_query($conn, "SELECT * FROM teachers order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");
    } else {
        // jika kolom pencarian tidak diisi
        $search = $_POST['search']['value'];
        $result = mysqli_query($conn, "SELECT * FROM teachers WHERE teacher_name like '%{$search}%' order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");

        // menentukan total data yang terbaru
        $count = mysqli_num_rows($result);
        $totalData = $count;
        $totalFiltered = $totalData;
    }

    $data = array();
    if (!empty($result)) {
        $no = $start + 1;

        // fetching data
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // perulangan tiap data
        foreach ($row as $r) {
            // Mengambil data kelas
            $query = mysqli_query($conn, "SELECT * FROM class_attendance where teacher_id = '{$r['id']}' ORDER BY class_id ASC");
            $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
            $kelas = '';

            // perulangan untuk menentukan kelas
            foreach ($result as $key => $c) {
                $query = mysqli_query($conn, "SELECT class_name FROM class WHERE id = '{$c['class_id']}'");
                $row = mysqli_fetch_row($query);
                if ($key === array_key_last($result)) {
                    $kelas .= $row['0'];
                } else {
                    $kelas .= $row['0'] . ", ";
                }
            }
            $query = mysqli_query($conn, "SELECT login FROM users WHERE id = '{$r['user_id']}'");
            $nis = mysqli_fetch_array($query, MYSQLI_ASSOC);
            $nestedData['no'] = $no;
            $nestedData['guru'] = $r["teacher_name"];
            $nestedData['nip'] = $nis['login'];
            $nestedData['alamat'] = $r['teacher_address'];
            $nestedData['no_hp'] = $r['phone_number'];
            $nestedData['kelas'] = $kelas;
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

// Fungsi Tambah Guru
if ($_GET['action'] == 'tambahGuru') {
    //GET POST DATA
    if ($_POST['tipeguru'] == 1) {
        $login = mysqli_real_escape_string($conn, $_POST['nip']);
    } else {
        $login = mysqli_real_escape_string($conn, $_POST['email']);
    }

    // Menginisiasi Variable
    $name = mysqli_real_escape_string($conn, $_POST['nama']);
    $address = mysqli_real_escape_string($conn, $_POST['alamat']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

    // Menambah data ke kolom user
    $register = mysqli_query($conn, "INSERT INTO users (login, password, level_user) VALUES ( '" . $login . "','" . $hashed_pass . "','2' )");

    //get registered id
    $result = mysqli_query($conn, "SELECT id FROM users WHERE login = '{$login}'");
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    //execute save guru
    if ($_POST['tipeguru'] == 1) {
        $sql = "INSERT INTO teachers (user_id, teacher_name, teacher_address, phone_number, nip, teacher_type) VALUES ('{$row['id']}', '{$name}', '{$address}', '{$phone_number}','{$login}', '{$_POST['tipeguru']}')";

        $insert_guru = mysqli_query($conn, $sql);

        if (!$insert_guru) {
            echo mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO teachers (user_id, teacher_name, teacher_address, phone_number, email, teacher_type) VALUES ('{$row['id']}', '{$name}', '{$address}', '{$phone_number}','{$login}', '{$_POST['tipeguru']}')";

        $insert_guru = mysqli_query($conn, $sql);

        if (!$insert_guru) {
            echo mysqli_error($conn);
        }
    }

    //Get Teachers ID
    $sql = "SELECT id from teachers WHERE user_id = '{$row['id']}'";
    $result_teacher = mysqli_query($conn, $sql);
    $teacher = mysqli_fetch_assoc($result_teacher);
    // 
    if (isset($_POST['kelas1'])) {
        $sql = "INSERT INTO class_attendance (class_id, teacher_id) VALUES ('{$_POST['kelas1']}', '{$teacher['id']}')";
        $insert_attendance = mysqli_query($conn, $sql);
        if (!$insert_attendance) {
            echo mysqli_error($conn);
        }
    }
    if (isset($_POST['kelas2'])) {
        $sql = "INSERT INTO class_attendance (class_id, teacher_id) VALUES ('{$_POST['kelas2']}', '{$teacher['id']}')";
        $insert_attendance = mysqli_query($conn, $sql);
        if (!$insert_attendance) {
            echo mysqli_error($conn);
        }
    }
    if (isset($_POST['kelas3'])) {
        $sql = "INSERT INTO class_attendance (class_id, teacher_id) VALUES ('{$_POST['kelas3']}', '{$teacher['id']}')";
        $insert_attendance = mysqli_query($conn, $sql);
        if (!$insert_attendance) {
            echo mysqli_error($conn);
        }
    }
    if (isset($_POST['kelas4'])) {
        $sql = "INSERT INTO class_attendance (class_id, teacher_id) VALUES ('{$_POST['kelas4']}', '{$teacher['id']}')";
        $insert_attendance = mysqli_query($conn, $sql);
        if (!$insert_attendance) {
            echo mysqli_error($conn);
        }
    }
    if (isset($_POST['kelas5'])) {
        $sql = "INSERT INTO class_attendance (class_id, teacher_id) VALUES ('{$_POST['kelas5']}', '{$teacher['id']}')";
        $insert_attendance = mysqli_query($conn, $sql);
        if (!$insert_attendance) {
            echo mysqli_error($conn);
        }
    }
}

// Mengambil data 1 guru berdasarkan ID
if ($_GET['action'] == 'getGuruById') {
    $id = $_POST['id'];

    // Select guru dari database
    $sql = "SELECT * FROM teachers WHERE id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
    $kelas1 = null;
    $kelas2 = null;
    $kelas3 = null;
    $kelas4 = null;
    $kelas5 = null;

    // Mengambil data kelas yang diampu dari database
    $query = mysqli_query($conn, "SELECT class_id from class_attendance where teacher_id = '{$id}'");
    $kelas = mysqli_fetch_all($query, MYSQLI_ASSOC);

    // mengambil data kelas
    foreach ($kelas as $k) {
        if ($k['class_id'] == 1) {
            $kelas1 = $k['class_id'];
        } else if ($k['class_id'] == 2) {
            $kelas2 = $k['class_id'];
        } else if ($k['class_id'] == 3) {
            $kelas3 = $k['class_id'];
        } else if ($k['class_id'] == 4) {
            $kelas4 = $k['class_id'];
        } else {
            $kelas5 = $k['class_id'];
        }
    }

    // menyatukan data
    $data = array(
        'id' => $result['id'],
        'nip' => $result['nip'],
        'email' => $result['email'],
        'nama' => $result['teacher_name'],
        'alamat' => $result['teacher_address'],
        'no_hp' => $result['phone_number'],
        'tipeguru' => $result['teacher_type'],
        'kelas1' => $kelas1,
        'kelas2' => $kelas2,
        'kelas3' => $kelas3,
        'kelas4' => $kelas4,
        'kelas5' => $kelas5,
    );

    // mengembalikan data ke view
    echo json_encode($data);
}

// Fungsi submit edit gutu
if ($_GET['action'] == 'editGuru') {
    //GET POST DATA
    $id = $_POST['id'];
    $nama = $_POST['namaEdit'];
    $alamat = $_POST['alamatEdit'];
    $no_hp = $_POST['no_hpEdit'];
    $tipeguru = $_POST['tipeGuruEdit'];

    //looking for kelas A
    $query = mysqli_query($conn, "SELECT * from class_attendance where teacher_id = '{$id}' AND class_id = 1");
    $row = mysqli_num_rows($query);
    if ($row > 0) {
        if (!isset($_POST['kelas1Edit'])) {
            $query = mysqli_query($conn, "DELETE FROM class_attendance where teacher_id = '{$id}' AND class_id = '1' ");
        }
    } else {
        if (isset($_POST['kelas1Edit'])) {
            $query = mysqli_query($conn, "INSERT INTO class_attendance (teacher_id, class_id) VALUES ('{$id}', '{$_POST['kelas1Edit']}')");
        }
    }

    //looking for kelas B
    $query = mysqli_query($conn, "SELECT * from class_attendance where teacher_id = '{$id}' AND class_id = 2");
    $row = mysqli_num_rows($query);
    if ($row > 0) {
        if (!isset($_POST['kelas2Edit'])) {
            $query = mysqli_query($conn, "DELETE FROM class_attendance where teacher_id = '{$id}' AND class_id = '2' ");
        }
    } else {
        if (isset($_POST['kelas2Edit'])) {
            $query = mysqli_query($conn, "INSERT INTO class_attendance (teacher_id, class_id) VALUES ('{$id}', '{$_POST['kelas2Edit']}')");
        }
    }

    //looking for kelas C
    $query = mysqli_query($conn, "SELECT * from class_attendance where teacher_id = '{$id}' AND class_id = 3");
    $row = mysqli_num_rows($query);
    if ($row > 0) {
        if (!isset($_POST['kelas3Edit'])) {
            $query = mysqli_query($conn, "DELETE FROM class_attendance where teacher_id = '{$id}' AND class_id = '3' ");
        }
    } else {
        if (isset($_POST['kelas3Edit'])) {
            $query = mysqli_query($conn, "INSERT INTO class_attendance (teacher_id, class_id) VALUES ('{$id}', '{$_POST['kelas3Edit']}')");
            if (!$query) {
                echo mysqli_error($conn);
            } else {
                print("TAMBAH KELAS C");
            }
        }
    }

    //looking for kelas D
    $query = mysqli_query($conn, "SELECT * from class_attendance where teacher_id = '{$id}' AND class_id = 4");
    $row = mysqli_num_rows($query);
    if ($row > 0) {
        if (!isset($_POST['kelas4Edit'])) {
            $query = mysqli_query($conn, "DELETE FROM class_attendance where teacher_id = '{$id}' AND class_id = '4' ");
        }
    } else {
        if (isset($_POST['kelas4Edit'])) {
            $query = mysqli_query($conn, "INSERT INTO class_attendance (teacher_id, class_id) VALUES ('{$id}', '{$_POST['kelas4Edit']}')");
        }
    }

    //looking for kelas E
    $query = mysqli_query($conn, "SELECT * from class_attendance where teacher_id = '{$id}' AND class_id = 5");
    $row = mysqli_num_rows($query);
    if ($row > 0) {
        if (!isset($_POST['kelas5Edit'])) {
            $query = mysqli_query($conn, "DELETE FROM class_attendance where teacher_id = '{$id}' AND class_id = '5' ");
        }
    } else {
        if (isset($_POST['kelas5Edit'])) {
            $query = mysqli_query($conn, "INSERT INTO class_attendance (teacher_id, class_id) VALUES ('{$id}', '{$_POST['kelas5Edit']}')");
        }
    }

    // Update data di database
    $sql = "UPDATE teachers SET teacher_name = '{$nama}', teacher_address = '{$alamat}', phone_number = '{$no_hp}' WHERE id = '{$id}'";

    // Error checking
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        echo mysqli_error($conn);
    }
}

// Fungsi hapus guru
if ($_GET['action'] == 'hapusGuru') {
    $id = $_POST['id'];
    // mengambil data guru
    $sql = "SELECT user_id FROM teachers where id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($query, MYSQLI_ASSOC);

    // mengahapus guru dari table user
    $query = mysqli_query($conn, "DELETE FROM users WHERE id = '{$user['user_id']}'");
    if (!$query) {
        echo mysqli_error($conn);
    }

    // mengahpus guru dari data kelas diampu
    $query = mysqli_query($conn, "DELETE FROM class_attendance WHERE teacher_id = '{$id}'");
    if (!$query) {
        echo mysqli_error($conn);
    }

    // menghapus guru dari data guru
    $sql = "DELETE FROM teachers where id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        echo mysqli_error($conn);
    }
}