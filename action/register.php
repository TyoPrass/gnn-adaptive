<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}


include('../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check database connection
    if (!$conn) {
        $_SESSION['error_message'] = 'Database connection failed: ' . mysqli_connect_error();
        header('location: ../sign-up.php');
        exit();
    }
    
    // Validate required fields
    if (empty($_POST['level']) || empty($_POST['name']) || empty($_POST['address']) || 
        empty($_POST['phone_number']) || empty($_POST['password'])) {
        $_SESSION['error_message'] = 'Semua field wajib diisi.';
        header('location: ../sign-up.php');
        exit();
    }

    //GET DATA
    if ($_POST['level'] == 2) {
        if (empty($_POST['tipeguru'])) {
            $_SESSION['error_message'] = 'Pilih jenis guru terlebih dahulu.';
            header('location: ../sign-up.php');
            exit();
        }
        if ($_POST['tipeguru'] == 1) {
            if (empty($_POST['nip'])) {
                $_SESSION['error_message'] = 'NIP harus diisi untuk guru PNS.';
                header('location: ../sign-up.php');
                exit();
            }
            $login = mysqli_real_escape_string($conn, $_POST['nip']);
        } else {
            if (empty($_POST['email'])) {
                $_SESSION['error_message'] = 'Email harus diisi untuk guru honorer.';
                header('location: ../sign-up.php');
                exit();
            }
            $login = mysqli_real_escape_string($conn, $_POST['email']);
        }
        
        // Check if at least one class is selected for teacher
        $class_selected = false;
        for ($i = 1; $i <= 5; $i++) {
            if (isset($_POST['kelas' . $i])) {
                $class_selected = true;
                break;
            }
        }
        if (!$class_selected) {
            $_SESSION['error_message'] = 'Pilih minimal satu kelas yang diampu.';
            header('location: ../sign-up.php');
            exit();
        }
    } else {
        if (empty($_POST['nis']) || empty($_POST['class_id'])) {
            $_SESSION['error_message'] = 'NIS dan kelas harus diisi untuk murid.';
            header('location: ../sign-up.php');
            exit();
        }
        $login = mysqli_real_escape_string($conn, $_POST['nis']);
        $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    }
    
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
    
    // Check if login already exists
    $check_login = mysqli_query($conn, "SELECT login FROM users WHERE login = '" . $login . "'");
    if (mysqli_num_rows($check_login) > 0) {
        $_SESSION['error_message'] = 'Login sudah terdaftar. Gunakan NIS/NIP/Email yang lain.';
        header('location: ../sign-up.php');
        exit();
    }

    $register = mysqli_query($conn, "INSERT INTO users (login, password, level_user) VALUES ( '" . $login . "','" . $hashed_pass . "','" . $_POST['level'] . "' )");

    if (!$register) {
        $_SESSION['error_message'] = 'Gagal membuat user: ' . mysqli_error($conn);
        header('location: ../sign-up.php');
        exit();
    }

    //get registered id
    $result = mysqli_query($conn, "SELECT id FROM users WHERE login = '{$login}'");
    if (!$result) {
        $_SESSION['error_message'] = 'Gagal mengambil data user: ' . mysqli_error($conn);
        header('location: ../sign-up.php');
        exit();
    }
    
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    if ($_POST['level'] == 2) {
        // Insert teacher data
        if ($_POST['tipeguru'] == 1) {
            $sql = "INSERT INTO teachers (user_id, teacher_name, teacher_address, phone_number, nip, teacher_type) VALUES ('{$row['id']}', '{$name}', '{$address}', '{$phone_number}','{$login}', '{$_POST['tipeguru']}')";
        } else {
            $sql = "INSERT INTO teachers (user_id, teacher_name, teacher_address, phone_number, email, teacher_type) VALUES ('{$row['id']}', '{$name}', '{$address}', '{$phone_number}','{$login}', '{$_POST['tipeguru']}')";
        }

        $insert_guru = mysqli_query($conn, $sql);
        if (!$insert_guru) {
            $_SESSION['error_message'] = 'Gagal menyimpan data guru: ' . mysqli_error($conn);
            header('location: ../sign-up.php');
            exit();
        }

        //Get Teachers ID
        $sql = "SELECT id from teachers WHERE user_id = '{$row['id']}'";
        $result_teacher = mysqli_query($conn, $sql);
        if (!$result_teacher) {
            $_SESSION['error_message'] = 'Gagal mengambil data guru: ' . mysqli_error($conn);
            header('location: ../sign-up.php');
            exit();
        }
        
        $teacher = mysqli_fetch_assoc($result_teacher);

        // Insert class attendance for teacher
        for ($i = 1; $i <= 5; $i++) {
            if (isset($_POST['kelas' . $i])) {
                $sql = "INSERT INTO class_attendance (class_id, teacher_id) VALUES ('{$_POST['kelas' . $i]}', '{$teacher['id']}')";
                $insert_attendance = mysqli_query($conn, $sql);
                if (!$insert_attendance) {
                    $_SESSION['error_message'] = 'Gagal menyimpan data kelas: ' . mysqli_error($conn);
                    header('location: ../sign-up.php');
                    exit();
                }
            }
        }
    } else {
        // Save student
        $sql = "INSERT INTO student (user_id, nis, student_name, student_address, phone_number, class_id) VALUES('{$row['id']}', '{$login}', '{$name}', '{$address}', '{$phone_number}', '{$class_id}')";
        $insert_student = mysqli_query($conn, $sql);
        if (!$insert_student) {
            $_SESSION['error_message'] = 'Gagal menyimpan data murid: ' . mysqli_error($conn);
            header('location: ../sign-up.php');
            exit();
        }
    }

    if (!$register) {
        $_SESSION['error_message'] = 'Gagal mendaftar: ' . mysqli_error($conn);
        header('location: ../sign-up.php');
        exit();
    } else {
        $_SESSION['success_message'] = 'Registrasi berhasil! Silahkan login.';
        header('location: ../register-success.php');
        exit();
    }
}