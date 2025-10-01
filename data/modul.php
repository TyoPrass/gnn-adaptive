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
            // menghitung jumlah jawaban benar
            if ($question['answer'] == $p) {
                $jawaban_benar++;
            }
            $total_soal++;
        }
    }

    $presentasi = $jawaban_benar / $total_soal;
    $s_id = mysqli_real_escape_string($conn, $_SESSION['student_id']);
    $m_id = mysqli_real_escape_string($conn, $_POST['module']);
    if ($presentasi > 0.75) {
        // echo "LULUS POST TEST";
        $sql = "INSERT INTO module_learned (module_id, student_id) VALUES('{$m_id}', '{$s_id}')";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            header("location: ../student/modul.php");
        } else {
            echo mysqli_error($conn);
        }
    } else {
        if ($_SESSION['level'] > 1) {
            $sql = "SELECT * FROM gagal_post_test WHERE student_id = '{$s_id}' AND level='{$_SESSION['level']}'";
            $query = mysqli_query($conn, $sql);
            if (mysqli_num_rows($query) > 0) {
                $count_gagal = mysqli_fetch_array($query);
                if ($count_gagal['total'] == 2) {
                    /*
                        TODO: Mulai turun level
                    */
                    $level = $_SESSION['level'] - 1;
                    $sql = "UPDATE level_student set level = '{$level}' where student_id = '{$s_id}'";
                    $query = mysqli_query($conn, $sql);
                    if ($query) {
                        $sql = "DELETE FROM gagal_post_test WHERE student_id = '{$s_id}'";
                        $query = mysqli_query($conn, $sql);
                        if ($query) {
                            $_SESSION['level'] = $level;
                            $_SESSION['turun_level'] = true;
                            $_SESSION['cek_level']=true;
                            if ($level==2) {
                                header("location: ../student/gagal-post-test.php");
                            }else{
                            // $_SESSION['gagal_post_test'] = true;
                            header("location: ../student/modul.php");
                        }
                        }
                    }
                } else {
                    $_SESSION['cek_level']=false;
                    $total = $count_gagal['total'] + 1;
                    $sql = "UPDATE gagal_post_test set total = '{$total}' WHERE student_id = '{$s_id}'";
                    $query = mysqli_query($conn, $sql);
                    $_SESSION['gagal_post_test'] = true;
                    header("location: ../student/gagal-post-test.php");
                }
            } else {
                $_SESSION['cek_level']=false;
                $sql = "INSERT INTO gagal_post_test (student_id, level, total) VALUES('{$s_id}', '{$_SESSION['level']}', 1)";
                $query = mysqli_query($conn, $sql);
                if ($query) {
                    $_SESSION['gagal_post_test'] = true;
                    header("location: ../student/gagal-post-test.php");
                }
            }
            // var_dump($count_gagal);
        }
        $_SESSION['gagal_post_test'] = true;        
        header("location: ../student/gagal-post-test.php");
    }
}
