<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: ../index.php'));
}


include('../config/db.php');
session_start();

// fungsi menyimpan data modul yang sudah selesai dipelajari
// if ($_GET['action'] == 'selesai') {
//     $s_id = mysqli_real_escape_string($conn, $_SESSION['student_id']);
//     $m_id = mysqli_real_escape_string($conn, $_POST['module']);
//     $sql = "INSERT INTO module_learned (module_id, student_id) VALUES('{$m_id}', '{$s_id}')";
//     $query = mysqli_query($conn, $sql);
//     if ($query) {
//         header("location: ../student/modul.php");
//     } else {
//         echo mysqli_error($conn);
//     }
// }

// fungsi menyimpan data post test murid
if ($_GET['action'] == 'submitPostTest') {

    // inisiasi variable
    $_SESSION['cek_level']=false;
    $total_soal = 0;
    $jawaban_benar = 0;

    // perulangan untuk menghitung jawaban
    foreach ($_POST as $key => $p) {
        if ($key == 'module') {
        } else {
            // mengambil data pertanyaan dari submit
            $question_id = substr($key, 8);

            // mencari data pertanyaan di database
            $sql = "SELECT * FROM module_question WHERE id = '{$question_id}'";
            $query = mysqli_query($conn, $sql);
            $question = mysqli_fetch_array($query, MYSQLI_ASSOC);
            
            // Konversi ke string dan gunakan strict comparison
            $correct_answer = (string)$question['answer']; // Cast ke string
            $user_answer = (string)$p; // Cast ke string

            if ($correct_answer === $user_answer) {
                $jawaban_benar++;
            }
            $total_soal++;
        }
    }

    $presentasi = $jawaban_benar / $total_soal;
    $s_id = mysqli_real_escape_string($conn, $_SESSION['student_id']);
    $m_id = mysqli_real_escape_string($conn, $_POST['module']);
    
    if ($presentasi > 0.75) {
        // LULUS POST TEST - Simpan ke database
        $sql = "INSERT INTO module_learned (module_id, student_id) VALUES('{$m_id}', '{$s_id}')";
        $query = mysqli_query($conn, $sql);
        
        if ($query) {
            // Redirect ke halaman sukses dengan parameter
            header("location: ../student/berhasil-post-test.php?module_id={$m_id}");
            exit();
        } else {
            echo mysqli_error($conn);
        }
    } else {
        $sql = "SELECT * FROM gagal_post_test WHERE student_id = '{$s_id}' AND level='{$_SESSION['level']}'";
        $query = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($query) > 0) {
            $count_gagal = mysqli_fetch_array($query);
            
            if ($count_gagal['total'] >= 2) {
                // GAGAL 3X ATAU LEBIH (total sudah 2, sekarang jadi 3)
                
                if ($_SESSION['level'] > 1) {
                    // ⬇️ TURUN LEVEL (jika bukan level terendah)
                    $level = $_SESSION['level'] - 1;
                    $sql = "UPDATE level_student SET level = '{$level}' WHERE student_id = '{$s_id}'";
                    $query = mysqli_query($conn, $sql);
                    
                    if ($query) {
                        // Hapus riwayat gagal level lama, mulai fresh di level baru
                        $sql = "DELETE FROM gagal_post_test WHERE student_id = '{$s_id}' AND level = '{$_SESSION['level']}'";
                        mysqli_query($conn, $sql);
                        
                        $_SESSION['level'] = $level;
                        $_SESSION['turun_level'] = true;
                        $_SESSION['cek_level'] = true;
                        
                        header("location: ../student/gagal-post-test.php?action=turun_level");
                        exit();
                    }
                } else {
                    // ⚠️ SUDAH DI LEVEL TERENDAH (Level 1)
                    // Tetap simpan riwayat gagal, tapi TIDAK bisa turun lagi
                    $total = $count_gagal['total'] + 1;
                    $sql = "UPDATE gagal_post_test SET total = '{$total}' WHERE student_id = '{$s_id}' AND level = '{$_SESSION['level']}'";
                    mysqli_query($conn, $sql);
                    
                    $_SESSION['gagal_post_test'] = true;
                    $_SESSION['level_terendah'] = true;
                    
                    header("location: ../student/gagal-post-test.php?action=level_terendah");
                    exit();
                }
            } else {
                // GAGAL 2X (update total menjadi 2)
                $total = $count_gagal['total'] + 1;
                $sql = "UPDATE gagal_post_test SET total = '{$total}' WHERE student_id = '{$s_id}' AND level = '{$_SESSION['level']}'";
                $query = mysqli_query($conn, $sql);
                
                $_SESSION['cek_level'] = false;
                $_SESSION['gagal_post_test'] = true;
                
                header("location: ../student/gagal-post-test.php");
                exit();
            }
        } else {
            // ✅ PERTAMA KALI GAGAL di level ini (untuk SEMUA level termasuk level 1)
            $sql = "INSERT INTO gagal_post_test (student_id, level, total) VALUES('{$s_id}', '{$_SESSION['level']}', 1)";
            $query = mysqli_query($conn, $sql);
            
            if ($query) {
                $_SESSION['cek_level'] = false;
                $_SESSION['gagal_post_test'] = true;
                
                header("location: ../student/gagal-post-test.php");
                exit();
            }
        }
    }
}
