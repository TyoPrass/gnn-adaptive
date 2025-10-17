<?php
include('../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check database connection
    if (!$conn) {
        $_SESSION['error_sign_in'] = 'Database connection failed: ' . mysqli_connect_error();
        header('location: ../sign-in.php');
        exit();
    }
    
    // Validate input
    if (empty($_POST['login']) || empty($_POST['password'])) {
        $_SESSION['error_sign_in'] = 'Username dan password harus diisi.';
        header('location: ../sign-in.php');
        exit();
    }

    //GET login and password
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE login = '$login'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        $_SESSION['error_sign_in'] = 'Database query error: ' . mysqli_error($conn);
        header('location: ../sign-in.php');
        exit();
    }
    
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    //check if result = 1
    if (mysqli_num_rows($result) == 1) {
        //verify password
        if (password_verify($password, $row['password'])) {
            // Clear any previous error messages
            unset($_SESSION['error_sign_in']);
            
            $_SESSION['login_user'] = $login;
            $_SESSION['level_user'] = $row['level_user'];
            $_SESSION['user_id'] = $row['id'];
            
            //get user detail based on level
            if ($row['level_user'] == 1) {
                // Admin
                $result = mysqli_query($conn, "SELECT * FROM admin WHERE user_id = " . $row['id']);
                if ($result && mysqli_num_rows($result) > 0) {
                    $user_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $_SESSION['name'] = $user_data['admin_name'];
                    $_SESSION['login'] = $user_data['email'];
                    header("location: ../admin");
                    exit();
                } else {
                    $_SESSION['error_sign_in'] = 'Data admin tidak ditemukan.';
                    header('location: ../sign-in.php');
                    exit();
                }
            } elseif ($row['level_user'] == 2) {
                // Teacher
                $result = mysqli_query($conn, "SELECT * FROM teachers WHERE user_id = " . $row['id']);
                if ($result && mysqli_num_rows($result) > 0) {
                    $user_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $_SESSION['name'] = $user_data['teacher_name'];
                    $_SESSION['teacher_id'] = $user_data['id'];
                    if ($user_data['teacher_type'] == 1) {
                        $_SESSION['login'] = $user_data['nip'];
                    } else {
                        $_SESSION['login'] = $user_data['email'];
                    }
                    header("location: ../guru");
                    exit();
                } else {
                    $_SESSION['error_sign_in'] = 'Data guru tidak ditemukan.';
                    header('location: ../sign-in.php');
                    exit();
                }
            } else {
                // Student
                $result = mysqli_query($conn, "SELECT * FROM student WHERE user_id = " . $row['id']);
                if ($result && mysqli_num_rows($result) > 0) {
                    $user_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $_SESSION['name'] = $user_data['student_name'];
                    $_SESSION['id'] = $user_data['id'];
                    $_SESSION['student_id'] = $user_data['user_id'];
                    $_SESSION['login'] = $user_data['nis'];
                    $_SESSION['class_id'] = $user_data['class_id'];
                    
                    header("location: ../student");
                    exit();
                } else {
                    $_SESSION['error_sign_in'] = 'Data siswa tidak ditemukan.';
                    header('location: ../sign-in.php');
                    exit();
                }
            }
        } else {
            $_SESSION['error_sign_in'] = "Password yang Anda masukkan salah.";
            header("location: ../sign-in.php");
            exit();
        }
    } else {
        $_SESSION['error_sign_in'] = "Username/NIS/NIP/Email tidak ditemukan.";
        header("location: ../sign-in.php");
        exit();
    }
}
?>