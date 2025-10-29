<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}

include('../config/db.php');
session_start();

// mengambil data murid untuk dataTable
if ($_GET['action'] == 'getMurid') {
    $columns = array(
        0 => 'id',
        1 => 'student_name',
        2 => 'login',
        3 => 'student_address',
        4 => 'class_id',
        5 => 'action'
    );

    // ✅ FILTER BERDASARKAN KELAS YANG DIAMPU GURU
    $class_filter = "";
    if (isset($_SESSION['level_user']) && $_SESSION['level_user'] == 2) {
        // Guru - hanya lihat murid dari kelas yang diampu
        $teacher_id = $_SESSION['teacher_id'];
        
        // Ambil semua class_id yang diampu guru ini
        $class_query = mysqli_query($conn, "SELECT DISTINCT class_id FROM class_attendance WHERE teacher_id = '{$teacher_id}'");
        
        if (mysqli_num_rows($class_query) > 0) {
            $class_ids = array();
            while ($class_row = mysqli_fetch_assoc($class_query)) {
                $class_ids[] = $class_row['class_id'];
            }
            
            if (!empty($class_ids)) {
                $class_id_list = implode(',', $class_ids);
                $class_filter = " AND student.class_id IN ({$class_id_list})";
            } else {
                $class_filter = " AND 1=0";
            }
        } else {
            $class_filter = " AND 1=0";
        }
    }

    $sql = "SELECT * FROM student WHERE 1=1 {$class_filter}";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);

    $totalData = $count;
    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        // ✅ Query dengan JOIN ke quiz_result dan quiz_result_e_learning
        $sql = "SELECT student.*, 
                       COALESCE(qr.nilai, '-') as quiz_adaptive, 
                       COALESCE(qre.nilai, '-') as quiz_e_learning 
                FROM student
                LEFT JOIN quiz_result qr ON qr.student_id = student.id
                LEFT JOIN quiz_result_e_learning qre ON qre.student_id = student.id
                WHERE 1=1 {$class_filter}
                ORDER BY {$order} {$dir} 
                LIMIT {$limit} OFFSET {$start}";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            echo json_encode(['error' => mysqli_error($conn)]);
            exit;
        }
    } else {
        // ✅ Query pencarian dengan filter kelas
        $search = mysqli_real_escape_string($conn, $_POST['search']['value']);
        
        $sql = "SELECT student.*, 
                       COALESCE(qr.nilai, '-') as quiz_adaptive, 
                       COALESCE(qre.nilai, '-') as quiz_e_learning 
                FROM student
                LEFT JOIN quiz_result qr ON qr.student_id = student.id
                LEFT JOIN quiz_result_e_learning qre ON qre.student_id = student.id
                WHERE student.student_name LIKE '%{$search}%' {$class_filter}
                ORDER BY {$order} {$dir} 
                LIMIT {$limit} OFFSET {$start}";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            echo json_encode(['error' => mysqli_error($conn)]);
            exit;
        }

        // ✅ Hitung ulang filtered
        $count_sql = "SELECT COUNT(*) as total 
                      FROM student
                      WHERE student_name LIKE '%{$search}%' {$class_filter}";
        $count_result = mysqli_query($conn, $count_sql);
        $count_data = mysqli_fetch_assoc($count_result);
        $totalFiltered = $count_data['total'];
    }

    $data = array();
    if (!empty($result)) {
        $no = $start + 1;
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($row as $r) {
            // ✅ Ambil data kelas
            $query = mysqli_query($conn, "SELECT class_name FROM class WHERE id = '{$r['class_id']}'");
            $kelas = mysqli_fetch_array($query, MYSQLI_ASSOC);

            // ✅ Ambil NIS dari tabel users (jika ada relasi)
            if (isset($r['user_id']) && !empty($r['user_id'])) {
                $query = mysqli_query($conn, "SELECT login FROM users WHERE id = '{$r['user_id']}'");
                $nis_data = mysqli_fetch_array($query);
                $nis = $nis_data['login'] ?? $r['nis'] ?? '-';
            } else {
                $nis = $r['nis'] ?? '-';
            }
            
            $nestedData['no'] = $no;
            $nestedData['murid'] = htmlspecialchars($r["student_name"]);
            $nestedData['nis'] = htmlspecialchars($nis);
            $nestedData['alamat'] = htmlspecialchars($r['student_address'] ?? '-');
            $nestedData['kelas'] = htmlspecialchars($kelas['class_name'] ?? '-');
            
            // ✅ Tampilkan nilai quiz
            $nestedData['quiz_adaptive'] = $r['quiz_adaptive'] != '-' 
                ? '<span class="badge bg-success">' . number_format($r['quiz_adaptive'], 2) . '</span>' 
                : '<span class="badge bg-secondary">Belum</span>';
                
            $nestedData['quiz_e_learning'] = $r['quiz_e_learning'] != '-' 
                ? '<span class="badge bg-info">' . number_format($r['quiz_e_learning'], 2) . '</span>' 
                : '<span class="badge bg-secondary">Belum</span>';
            
            // ✅ Tombol aksi
            if (isset($_SESSION['level_user']) && $_SESSION['level_user'] == 1) {
                // Admin bisa edit/hapus
                $nestedData['action'] = "
                    <a href='javascript:void(0)' id='btn-edit' data='{$r['id']}' class='btn btn-action btn-edit'>
                        <i class='fas fa-edit'></i>
                    </a>
                    <a href='javascript:void(0)' data='{$r['id']}' id='btn-delete' class='btn btn-action btn-delete'>
                        <i class='fas fa-trash'></i>
                    </a>";
            } else {
                // Guru hanya lihat
                $nestedData['action'] = '<span class="badge bg-secondary"><i class="fas fa-eye me-1"></i>Lihat Saja</span>';
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
    exit;
}

if ($_GET['action'] == 'getStats') {
    $class_filter = "";
    
    if (isset($_SESSION['level_user']) && $_SESSION['level_user'] == 2) {
        $teacher_id = $_SESSION['teacher_id'];
        
        $class_query = mysqli_query($conn, "SELECT DISTINCT class_id FROM class_attendance WHERE teacher_id = '{$teacher_id}'");
        
        if (mysqli_num_rows($class_query) > 0) {
            $class_ids = array();
            while ($class_row = mysqli_fetch_assoc($class_query)) {
                $class_ids[] = $class_row['class_id'];
            }
            
            if (!empty($class_ids)) {
                $class_id_list = implode(',', $class_ids);
                $class_filter = " WHERE class_id IN ({$class_id_list})";
            }
        }
    }
    
    $total_murid_query = "SELECT COUNT(*) as total FROM student {$class_filter}";
    $total_murid_result = mysqli_query($conn, $total_murid_query);
    $total_murid = mysqli_fetch_assoc($total_murid_result)['total'];
    
    if (isset($_SESSION['level_user']) && $_SESSION['level_user'] == 2) {
        $teacher_id = $_SESSION['teacher_id'];
        $total_kelas_query = "SELECT COUNT(DISTINCT class_id) as total FROM class_attendance WHERE teacher_id = '{$teacher_id}'";
    } else {
        $total_kelas_query = "SELECT COUNT(*) as total FROM class";
    }
    $total_kelas_result = mysqli_query($conn, $total_kelas_query);
    $total_kelas = mysqli_fetch_assoc($total_kelas_result)['total'];
    
    echo json_encode([
        'totalMurid' => $total_murid,
        'totalKelas' => $total_kelas
    ]);
    exit;
}


// fungsi tambah data murid
if ($_GET['action'] == 'tambahMurid') {
    //GET POST DATA
    $login = mysqli_real_escape_string($conn, $_POST['nis']);
    $name = mysqli_real_escape_string($conn, $_POST['nama']);
    $address = mysqli_real_escape_string($conn, $_POST['alamat']);
    $class_id = mysqli_real_escape_string($conn, $_POST['kelas']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

    //INSERT INTO users table
    $register = mysqli_query($conn, "INSERT INTO users (login, password, level_user) VALUES ( '" . $login . "','" . $hashed_pass . "','3' )");
    if (!$register) {
        echo mysqli_error($conn);
    }
    //get registered id
    $result = mysqli_query($conn, "SELECT id FROM users WHERE login = '{$login}'");
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    //INSERT INTO STUDENT
    $sql = "INSERT INTO student (user_id, nis, student_name, student_address, phone_number, class_id) VALUES('{$row['id']}', '{$login}', '{$name}', '{$address}', '{$phone_number}', '{$class_id}')";
    $insert_student = mysqli_query($conn, $sql);
    if (!$insert_student) {
        echo mysqli_error($conn);
    }
}

// mengambil data 1 murid dari database
if ($_GET['action'] == 'getMuridById') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM student WHERE id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);

    $data = array(
        'id' => $result['id'],
        'nis' => $result['nis'],
        'nama' => $result['student_name'],
        'alamat' => $result['student_address'],
        'no_hp' => $result['phone_number'],
        'kelas' => $result['class_id'],
    );
    echo json_encode($data);
}

// fungsi simpan edit murid
if ($_GET['action'] == 'editMurid') {
    $id = $_POST['id'];
    $student_name = $_POST['editNama'];
    $student_address = $_POST['editAlamat'];
    $phone_number = $_POST['edit_no_hp'];
    $class_id = $_POST['editKelas'];
    // var_dump($id);
    $sql = "UPDATE student set student_name = '{$student_name}', student_address = '{$student_address}', phone_number = '{$phone_number}', class_id = '{$class_id}' WHERE id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        echo mysqli_error($conn);
    }
}

// fungsi hapus murid
if ($_GET['action'] == 'hapusMurid') {
    $id = $_POST['id'];
    $sql = "SELECT user_id FROM student where id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($query, MYSQLI_ASSOC);

    $query = mysqli_query($conn, "DELETE FROM users WHERE id = '{$user['user_id']}'");
    if (!$query) {
        echo mysqli_error($conn);
    }

    $sql = "DELETE FROM student where id = '{$id}'";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        echo mysqli_error($conn);
    }
}

// fungsi mengambil hasil pertest murid untuk dataTable
if ($_GET['action'] == 'getHasilPretest') {
    $columns = array(
        0 => 'id',
        1 => 'student_name',
        2 => 'login',
        3 => 'student_address',
        4 => 'class_id',
        5 => 'action'
    );

    $sql = "SELECT * FROM student";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);

    $totalData = $count;
    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    // var_dump($order);
    if (empty($_POST['search']['value'])) {
        // $result = mysqli_query($conn, "SELECT * FROM student order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");
        $result = mysqli_query($conn, "SELECT * FROM student order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");
        if (!$result) {
            echo mysqli_error($conn);
        }
    } else {
        $search = $_POST['search']['value'];
        $result = mysqli_query($conn, "SELECT * FROM student WHERE student_name like '%{$search}%' order by {$order} {$dir} LIMIT {$limit} OFFSET {$start}");

        $count = mysqli_num_rows($result);
        $totalData = $count;
        $totalFiltered = $totalData;
    }

    $data = array();
    if (!empty($result)) {
        $no = $start + 1;
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($row as $r) {
            $query = mysqli_query($conn, "SELECT * FROM class where id = '{$r['class_id']}'");
            $kelas = mysqli_fetch_array($query, MYSQLI_ASSOC);

            $query = mysqli_query($conn, "SELECT login FROM users WHERE id = '{$r['user_id']}'");
            $nis = mysqli_fetch_array($query);

            //TAMBAHAN BARU SAYA - Survey Result
            $querySurvey = mysqli_query($conn, "SELECT * FROM survey_result WHERE student_id = '{$r['id']}'");
            if (mysqli_num_rows($querySurvey) > 0) {
                $surveyData = mysqli_fetch_array($querySurvey, MYSQLI_ASSOC);
                $hasilsurvei = 'Level ' . $surveyData['level_result'];
            } else {
                $hasilsurvei = 'Belum ambil survei';
            }

            //tambahan baru - Pre Test Result
            $queryPreTest = mysqli_query($conn, "SELECT * FROM pre_test_result WHERE student_id = '{$r['id']}'");
            if (mysqli_num_rows($queryPreTest) > 0) {
                $preTestData = mysqli_fetch_array($queryPreTest, MYSQLI_ASSOC);
                $hasilirt = 'Level ' . $preTestData['level'];
            } else {
                $queryAnswer = mysqli_query($conn, "SELECT * FROM pre_test_answer WHERE student_id = '{$r['id']}'");
                if (mysqli_num_rows($queryAnswer) > 0) {
                    $hasilirt = 'Level belum dihitung';
                } else {
                    $hasilirt = 'Belum ambil Pre Test';
                }
            }

            // Final Level Determination
            $queryLevel = mysqli_query($conn, "SELECT * FROM level_student WHERE student_id = '{$r['id']}'");
            if (mysqli_num_rows($queryLevel) > 0) {
                $levelData = mysqli_fetch_array($queryLevel, MYSQLI_ASSOC);
                $hasilPreTest = 'Level ' . $levelData['level'];
            } else {
                // Use pre_test_result if available
                $queryPreTestFinal = mysqli_query($conn, "SELECT * FROM pre_test_result WHERE student_id = '{$r['id']}'");
                if (mysqli_num_rows($queryPreTestFinal) > 0) {
                    $preTestFinalData = mysqli_fetch_array($queryPreTestFinal, MYSQLI_ASSOC);
                    $hasilPreTest = 'Level ' . $preTestFinalData['level'];
                } else {
                    $queryAnswerFinal = mysqli_query($conn, "SELECT * FROM pre_test_answer WHERE student_id = '{$r['id']}'");
                    if (mysqli_num_rows($queryAnswerFinal) > 0) {
                        $hasilPreTest = 'Level belum dihitung';
                    } else {
                        $hasilPreTest = 'Belum ambil Pre Test';
                    }
                }
            }

            
            $nestedData['no'] = $no;
            $nestedData['murid'] = $r["student_name"];
            $nestedData['nis'] = $nis['login'] ?? '-';
            $nestedData['kelas'] = $kelas['class_name'] ?? '-';
            $nestedData['hasilSurvei'] = $hasilsurvei;
            $nestedData['hasilIrt'] = $hasilirt;
            $nestedData['hasilPreTest'] = $hasilPreTest;
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
