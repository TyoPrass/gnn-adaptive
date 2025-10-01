<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// mengambil data jawaban pre_test dari murid
$pre_test = mysqli_query($conn, "SELECT * FROM pre_test_answer where student_id = '{$_SESSION['student_id']}'");
$pre_test_row = mysqli_num_rows($pre_test);

// mengambil data hasil survey dari murid
$survey = mysqli_query($conn, "SELECT * FROM survey_result where student_id = '{$_SESSION['student_id']}'");
$survey_row = mysqli_num_rows($survey);

// mengecek jika murid sudah mengambil pre test dan survey
if ($pre_test_row > 0 || $survey_row > 0) {
    // mengambil data level murid dari database
    $query = mysqli_query($conn, "SELECT * FROM level_student where student_id = '{$_SESSION['student_id']}'");
    $_SESSION['survey_taken'] = true;
    // jika data ada maka tes sudah di proses
    if (mysqli_num_rows($query) > 0) {
        $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
        $level_user = $result['level'];
        // setting level modul sesuai level user
        if ($level_user == 1) {
            $level_modul = [1, 2, 3];
        } else if ($level_user == 2) {
            $level_modul = [2, 3, 1];
        } else {
            $level_modul = [3, 1, 2];
        }
        $_SESSION['test_processed'] = true;
    } else {
        // pre test belum diproses
        $_SESSION['test_processed'] = false;
    }
} else {
    $_SESSION['survey_taken'] = false;
}

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Modul Pembelajaran Biologi - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --modul-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --modul-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --modul-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --modul-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --modul-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --modul-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
            --modul-locked: linear-gradient(135deg, #666666 0%, #999999 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .modul-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .modul-header {
            background: var(--modul-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .modul-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: modulFloat 12s ease-in-out infinite;
        }
        
        @keyframes modulFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-10deg); }
        }
        
        .level-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 3rem;
            border-left: 5px solid #00C851;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        
        .level-title {
            color: #2E7D32;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
        }
        
        .level-badge {
            background: var(--modul-primary);
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }
        
        .module-item {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid transparent;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .module-item:hover {
            border-color: rgba(0, 200, 81, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            text-decoration: none;
        }
        
        .module-item.available {
            border-color: #00C851;
            background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);
        }
        
        .module-item.completed {
            background: var(--modul-success);
            color: white;
            border-color: #009688;
        }
        
        .module-item.locked {
            background: var(--modul-locked);
            color: white;
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .module-item.locked:hover {
            transform: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .module-number {
            background: var(--modul-primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        
        .module-item.completed .module-number {
            background: rgba(255,255,255,0.3);
        }
        
        .module-item.locked .module-number {
            background: rgba(255,255,255,0.2);
        }
        
        .module-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #2E7D32;
        }
        
        .module-item.completed .module-title,
        .module-item.locked .module-title {
            color: white;
        }
        
        .module-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .module-item.completed .module-description,
        .module-item.locked .module-description {
            color: rgba(255,255,255,0.9);
        }
        
        .module-status {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
        }
        
        .quiz-section {
            background: var(--modul-warning);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            margin-top: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .quiz-section.disabled {
            background: #cccccc;
            opacity: 0.7;
        }
        
        .quiz-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: quizPulse 6s ease-in-out infinite;
        }
        
        @keyframes quizPulse {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.05); }
        }
        
        .btn-quiz {
            background: white;
            color: #FF8800;
            border: none;
            padding: 1.2rem 4rem;
            font-size: 1.4rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .btn-quiz:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
            color: #FF8800;
        }
        
        .btn-quiz:disabled {
            background: #f5f5f5;
            color: #999;
            cursor: not-allowed;
        }
        
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .alert-danger-custom {
            background: var(--modul-danger);
            color: white;
        }
        
        .floating-icons {
            position: absolute;
            font-size: 4rem;
            opacity: 0.1;
            animation: floatIcons 8s ease-in-out infinite;
        }
        
        .floating-icons.icon1 {
            top: 15%;
            right: 15%;
            animation-delay: 0s;
        }
        
        .floating-icons.icon2 {
            bottom: 20%;
            left: 10%;
            animation-delay: 3s;
        }
        
        .floating-icons.icon3 {
            top: 60%;
            right: 5%;
            animation-delay: 6s;
        }
        
        @keyframes floatIcons {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.05; }
            50% { transform: translateY(-20px) rotate(10deg); opacity: 0.15; }
        }
        
        .progress-info {
            background: var(--modul-info);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .progress-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 1rem;
        }
        
        .progress-stat {
            text-align: center;
        }
        
        .progress-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }
        
        .waiting-section {
            background: var(--modul-info);
            color: white;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .modul-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .level-section {
                padding: 1.5rem;
            }
            
            .module-item {
                padding: 1rem;
            }
            
            .floating-icons {
                display: none;
            }
            
            .quiz-section {
                padding: 2rem 1rem;
            }
            
            .btn-quiz {
                padding: 1rem 2rem;
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--modul-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-leaf me-2"></i>MyIRT Adaptive Learning
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-user me-1"></i><?php echo $_SESSION['name']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../sign-out.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modul-card">
                        <div class="modul-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-dna"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <div class="floating-icons icon3">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-books me-3"></i>Rekomendasi Modul Biologi
                                    </h1>
                                    <p class="lead mb-3">Pembelajaran adaptif disesuaikan dengan tingkat kemampuan dan gaya belajar Anda</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-brain me-1"></i>Pembelajaran Adaptif
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-chart-line me-1"></i>Progress Tracking
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-award me-1"></i>Level Based
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-graduation-cap" style="font-size: 6rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="row">
                <div class="col-12">
                    <?php
                    // Error Messages
                    if (isset($_SESSION['gagal_post_test'])) { ?>
                        <div class="alert alert-danger-custom alert-custom" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Gagal Lulus Post Test!</strong> Silakan ulangi kembali untuk melanjutkan pembelajaran.
                            <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php }
                    unset($_SESSION['gagal_post_test']);
                    
                    if (isset($_SESSION['turun_level'])) { ?>
                        <div class="alert alert-danger-custom alert-custom" role="alert">
                            <i class="fas fa-level-down-alt me-2"></i>
                            <strong>Level Turun!</strong> Anda gagal mengerjakan post test sebanyak 3x. Level Anda otomatis turun 1 tingkat.
                            <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php }
                    unset($_SESSION['turun_level']);
                    
                    if ($_SESSION['survey_taken']) {
                        if (isset($level_modul)) {
                            // Progress Info
                            $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}'";
                            $query = mysqli_query($conn, $sql);
                            $learned_module = mysqli_num_rows($query);
                            $total_modules = 7;
                            ?>
                            
                            <div class="progress-info">
                                <h5 class="mb-2">
                                    <i class="fas fa-chart-bar me-2"></i>Progress Pembelajaran Anda
                                </h5>
                                <div class="progress-stats">
                                    <div class="progress-stat">
                                        <span class="progress-number"><?php echo $learned_module; ?></span>
                                        <small>Modul Selesai</small>
                                    </div>
                                    <div class="progress-stat">
                                        <span class="progress-number"><?php echo $total_modules; ?></span>
                                        <small>Total Modul</small>
                                    </div>
                                    <div class="progress-stat">
                                        <span class="progress-number"><?php echo round(($learned_module/$total_modules)*100); ?>%</span>
                                        <small>Kemajuan</small>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            foreach ($level_modul as $l) { ?>
                                <div class="level-section">
                                    <div class="level-title">
                                        <div class="level-badge"><?php echo $l; ?></div>
                                        <span>Level <?php echo $l; ?> - 
                                        <?php 
                                        if ($l == 1) echo "Dasar";
                                        else if ($l == 2) echo "Menengah";
                                        else echo "Lanjutan";
                                        ?>
                                        </span>
                                    </div>
                                    
                                    <div class="row">
                                        <?php
                                        $sql = "SELECT * FROM module WHERE module_level = '{$l}'";
                                        $query = mysqli_query($conn, $sql);
                                        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                        $last_key = 0;
                                        $last_learned = 0;
                                        
                                        foreach ($result as $key => $r) {
                                            $disabled = false;
                                            $module_learned = false;
                                            
                                            // Logic untuk menentukan status module (sama seperti original)
                                            if ($l == $level_user) {
                                                if ($key == array_key_first($result)) {
                                                    $disabled = false;
                                                } else {
                                                    $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' order by id DESC limit 1";
                                                    $query = mysqli_query($conn, $sql);
                                                    
                                                    if (mysqli_num_rows($query) > 0) {
                                                        $modul = mysqli_fetch_array($query, MYSQLI_ASSOC);
                                                        if ($modul['module_id'] == $last_key) {
                                                            $disabled = false;
                                                        } else {
                                                            $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$last_key}'";
                                                            $query = mysqli_query($conn, $sql);
                                                            if (mysqli_num_rows($query) > 0) {
                                                                $disabled = false;
                                                            } else {
                                                                $disabled = true;
                                                            }
                                                        }
                                                    } else {
                                                        $disabled = true;
                                                    }
                                                }
                                            } else if ($l < $level_user) {
                                                $disabled = false;
                                            } else {
                                                if (isset($level_done)) {
                                                    if ($level_done == ($l - 1)) {
                                                        if ($key == array_key_first($result)) {
                                                            $disabled = false;
                                                        } else {
                                                            $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' order by id DESC limit 1";
                                                            $query = mysqli_query($conn, $sql);
                                                            $modul = mysqli_fetch_array($query, MYSQLI_ASSOC);
                                                            if ($modul['module_id'] == $last_key) {
                                                                $disabled = false;
                                                            } else {
                                                                $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$last_key}'";
                                                                $query = mysqli_query($conn, $sql);
                                                                if (mysqli_num_rows($query) > 0) {
                                                                    $disabled = false;
                                                                } else {
                                                                    $disabled = true;
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $disabled = true;
                                                    }
                                                } else {
                                                    $disabled = true;
                                                }
                                            }
                                            
                                            $last_key = $r['id'];
                                            $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$r['id']}'";
                                            $query = mysqli_query($conn, $sql);
                                            if (mysqli_num_rows($query) > 0) {
                                                $module_learned = true;
                                            }
                                            
                                            if ($key == array_key_last($result) && $module_learned == true) {
                                                $level_done = $l;
                                            }
                                            
                                            $status_class = $disabled ? 'locked' : ($module_learned ? 'completed' : 'available');
                                            $status_icon = $disabled ? 'fas fa-lock' : ($module_learned ? 'fas fa-check-circle' : 'fas fa-play-circle');
                                        ?>
                                        
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <a href="<?php echo $disabled ? '#' : 'module.php?module=' . $r['id']; ?>" 
                                               class="module-item <?php echo $status_class; ?>" 
                                               <?php if ($disabled) echo 'onclick="return false;"'; ?>>
                                               
                                                <div class="module-status">
                                                    <i class="<?php echo $status_icon; ?>"></i>
                                                </div>
                                                
                                                <div class="module-number">
                                                    <?php echo $r['number']; ?>
                                                </div>
                                                
                                                <div class="module-title">
                                                    Modul <?php echo $r['number']; ?>
                                                </div>
                                                
                                                <div class="module-description">
                                                    <?php echo $r['module_desc']; ?>
                                                </div>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <!-- Quiz Section -->
                            <div class="quiz-section">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <h2 class="mb-3">
                                            <i class="fas fa-clipboard-question me-3"></i>Quiz Akhir
                                        </h2>
                                        <p class="lead mb-3">
                                            Uji pemahaman Anda dengan quiz biologi yang menarik
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-brain me-2"></i>
                                            Quiz tersedia kapan saja untuk mengukur kemampuan Anda
                                        </p>
                                    </div>
                                    <div class="col-lg-4 text-center">
                                        <a href="quiz.php" class="btn-quiz">
                                            <i class="fas fa-play me-2"></i>
                                            MULAI QUIZ
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                        <?php } else { ?>
                            <!-- Waiting Section -->
                            <div class="waiting-section">
                                <i class="fas fa-hourglass-half fa-5x mb-4"></i>
                                <h2 class="mb-3">Proses Penghitungan Pre-Test</h2>
                                <p class="lead mb-4">
                                    Silahkan tunggu hasil pre-test yang masih diproses oleh sistem
                                </p>
                                <a href="index.php" class="btn btn-light btn-lg">
                                    <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                                </a>
                            </div>
                        <?php }
                    } else { ?>
                        <!-- No Survey Section -->
                        <div class="waiting-section">
                            <i class="fas fa-clipboard-question fa-5x mb-4"></i>
                            <h2 class="mb-3">Pre-Test Diperlukan</h2>
                            <p class="lead mb-4">
                                Anda belum melakukan pre-test. Silakan lakukan pre-test terlebih dahulu untuk mendapatkan rekomendasi modul pembelajaran.
                            </p>
                            <a href="index.php" class="btn btn-light btn-lg">
                                <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        // Initialize module interactions
        initializeModuleInteractions();
        
        // Module hover effects
        $('.module-item').hover(
            function() {
                $(this).find('.module-number').addClass('animate__pulse');
            },
            function() {
                $(this).find('.module-number').removeClass('animate__pulse');
            }
        );
        
        // Quiz button interactions
        $('.btn-quiz:not([style*="pointer-events"])').hover(
            function() {
                $(this).find('i').addClass('fa-bounce');
            },
            function() {
                $(this).find('i').removeClass('fa-bounce');
            }
        );
        
        // Locked module click handler
        $('.module-item.locked').on('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Modul Terkunci',
                text: 'Selesaikan modul sebelumnya untuk membuka modul ini.',
                icon: 'warning',
                confirmButtonColor: '#FF8800',
                confirmButtonText: 'Mengerti'
            });
            
            return false;
        });
        
        // Module completion celebration
        $('.module-item.completed').each(function() {
            $(this).append('<div class="completion-sparkle"><i class="fas fa-star"></i></div>');
        });
        
        // Progress animation
        animateProgress();
    });
    
    function initializeModuleInteractions() {
        // Add click tracking for analytics
        $('.module-item:not(.locked)').on('click', function() {
            const moduleNumber = $(this).find('.module-number').text();
            
            // Show loading indication
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
            
            toast.fire({
                icon: 'info',
                title: `Memuat Modul ${moduleNumber}...`,
                background: '#00C851',
                color: 'white'
            });
        });
    }
    
    function animateProgress() {
        // Animate progress numbers
        $('.progress-number').each(function() {
            const $this = $(this);
            const target = parseInt($this.text().replace(/\D/g, ''));
            let current = 0;
            
            if (target > 0) {
                const increment = target / 30;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                        $this.text(target + ($this.text().includes('%') ? '%' : ''));
                    } else {
                        $this.text(Math.floor(current) + ($this.text().includes('%') ? '%' : ''));
                    }
                }, 50);
            }
        });
    }
    
    // Scroll animations
    $(window).scroll(function() {
        $('.level-section').each(function() {
            const elementTop = $(this).offset().top;
            const elementBottom = elementTop + $(this).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animate-in');
            }
        });
    });
    
    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        .completion-sparkle {
            position: absolute;
            top: 10px;
            left: 10px;
            color: gold;
            animation: sparkle 2s ease-in-out infinite;
        }
        
        @keyframes sparkle {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }
        
        .animate-in {
            animation: slideInUp 0.6s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>

</html>