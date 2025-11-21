<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// Cek apakah user sudah mengerjakan pre-test e-learning
$sql_pretest = "SELECT * FROM quiz_result_e_learning WHERE student_id = '{$_SESSION['student_id']}'";
$query_pretest = mysqli_query($conn, $sql_pretest);

if (mysqli_num_rows($query_pretest) > 0) {
    $pretest_result = mysqli_fetch_assoc($query_pretest);
    $pretest_score = $pretest_result['nilai'];
} else {
    // Jika belum mengerjakan pre-test, redirect ke dashboard
    header('location: index.php');
    exit();
}

// mengambil data jawaban pre_test dari murid
$pre_test = mysqli_query($conn, "SELECT * FROM pre_test_answer where student_id = '{$_SESSION['student_id']}'");
$pre_test_row = mysqli_num_rows($pre_test);
// echo $_SESSION['student_id'];

// mengambil data hasil survey dari murid
$survey = mysqli_query($conn, "SELECT * FROM survey_result where student_id = '{$_SESSION['student_id']}'");
$survey_row = mysqli_num_rows($survey);

// mengecek jika murid sudah mengambil pre test dan survey
// if ($pre_test_row > 0 || $survey_row > 0) {
//     // mengambil data level murid dari database
//     $query = mysqli_query($conn, "SELECT * FROM level_student where student_id = '{$_SESSION['student_id']}'");
//     $_SESSION['survey_taken'] = true;
//     // jika data ada maka tes sudah di proses
//     if (mysqli_num_rows($query) > 0) {
//         $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
//         $level_user = $result['level'];
//         // setting level modul sesuai level user
//         if ($level_user == 1) {
//             $level_modul = [1, 2, 3];
//         } else if ($level_user == 2) {
//             $level_modul = [1, 2, 3];
//         } else {
//             $level_modul = [1, 2 ,3];
//         }
//         $_SESSION['test_processed'] = true;
//     } else {
//         // pre test belum diproses
//         $_SESSION['test_processed'] = false;
//     }
// } else {
//     $_SESSION['survey_taken'] = false;
// }

$level_modul = [1, 2, 3];

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>E-Learning Biologi - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --elearning-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --elearning-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --elearning-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --elearning-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --elearning-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --elearning-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .elearning-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .elearning-header {
            background: var(--elearning-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .elearning-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: elearningFloat 12s ease-in-out infinite;
        }
        
        @keyframes elearningFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-10deg); }
        }
        
        .module-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 2px solid transparent;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        
        .module-card:hover {
            border-color: rgba(0, 200, 81, 0.4);
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .module-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 6px;
            background: var(--elearning-primary);
            transform: scaleY(0);
            transition: transform 0.4s ease;
        }
        
        .module-card:hover::before {
            transform: scaleY(1);
        }
        
        .module-number {
            background: var(--elearning-primary);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .module-number::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 15px;
            background: var(--elearning-secondary);
            opacity: 0;
            transform: scale(0);
            animation: moduleGlow 3s ease-in-out infinite;
        }
        
        @keyframes moduleGlow {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 0.3; transform: scale(1.2); }
        }
        
        .module-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #2E7D32;
            margin-bottom: 0.5rem;
        }
        
        .module-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        
        .module-status {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
        }
        
        .status-completed {
            background: var(--elearning-success);
            color: white;
        }
        
        .status-available {
            background: var(--elearning-info);
            color: white;
        }
        
        .status-locked {
            background: var(--elearning-warning);
            color: white;
        }
        
        .locked-card {
            opacity: 0.6;
            filter: grayscale(50%);
            position: relative;
        }
        
        .lock-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            z-index: 10;
            color: #666;
        }
        
        .lock-overlay i {
            color: #999;
        }
        
        .lock-overlay p {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .progress-section {
            background: var(--elearning-info);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .progress-bar-custom {
            height: 12px;
            border-radius: 15px;
            background: rgba(255,255,255,0.3);
            overflow: hidden;
        }
        
        .progress-bar-fill {
            height: 100%;
            background: white;
            border-radius: 15px;
            transition: width 0.8s ease;
            position: relative;
        }
        
        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.4) 50%, transparent 60%);
            animation: progressShimmer 2s ease-in-out infinite;
        }
        
        @keyframes progressShimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .quiz-section {
            background: var(--elearning-primary);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            margin-top: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .quiz-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: quizPulse 4s ease-in-out infinite;
        }
        
        @keyframes quizPulse {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.1); }
        }
        
        .btn-quiz {
            background: white;
            color: #00C851;
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
            color: #00C851;
        }
        
        .btn-quiz:disabled {
            background: #f5f5f5;
            color: #999;
            cursor: not-allowed;
        }
        
        .btn-quiz::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(0,200,81,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-quiz:hover:not(:disabled)::before {
            left: 100%;
        }
        
        .stats-row {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
            margin: 2rem 0;
        }
        
        .stat-item {
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            padding: 1.5rem;
            min-width: 150px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .floating-icons {
            position: absolute;
            font-size: 4rem;
            opacity: 0.1;
            animation: floatIcons 6s ease-in-out infinite;
        }
        
        .floating-icons.icon1 {
            top: 15%;
            right: 15%;
            animation-delay: 0s;
        }
        
        .floating-icons.icon2 {
            bottom: 20%;
            left: 10%;
            animation-delay: 2s;
        }
        
        .floating-icons.icon3 {
            top: 60%;
            right: 5%;
            animation-delay: 4s;
        }
        
        @keyframes floatIcons {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.1; }
            50% { transform: translateY(-15px) rotate(5deg); opacity: 0.2; }
        }
        
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .alert-danger-custom {
            background: var(--elearning-danger);
            color: white;
        }
        
        .completion-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--elearning-success);
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .elearning-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .module-card {
                padding: 1.5rem;
                text-align: center;
            }
            
            .module-card:hover {
                transform: translateY(-5px);
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
    <nav class="navbar navbar-expand-lg" style="background: var(--elearning-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-leaf me-2"></i>MyIRT E-Learning
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
                    <div class="elearning-card">
                        <div class="elearning-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-dna"></i>
                            </div>
                            <div class="floating-icons icon3">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-laptop-code me-3"></i>E-Learning Biologi
                                    </h1>
                                    <p class="lead mb-3">Jelajahi dunia biologi melalui pembelajaran interaktif dan menyenangkan</p>
                                    
                                    <?php if (isset($pretest_score)) { ?>
                                    <div class="mb-3">
                                        <span class="badge" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white; font-size: 1.2rem; padding: 0.8rem 1.5rem; box-shadow: 0 4px 15px rgba(255,165,0,0.3);">
                                            <i class="fas fa-trophy me-2"></i>Nilai Pre-Test E-Learning: <?php echo number_format($pretest_score, 0); ?>
                                        </span>
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-play me-1"></i>Pembelajaran Interaktif
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-chart-line me-1"></i>Tracking Progress
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-certificate me-1"></i>Sertifikat Digital
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-leaf" style="font-size: 6rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Alert - Locked Module -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 'locked') { ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 15px; border-left: 5px solid #FF8800;">
                        <h5 class="alert-heading">
                            <i class="fas fa-lock me-2"></i>Modul Terkunci!
                        </h5>
                        <p class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda harus menyelesaikan modul sebelumnya terlebih dahulu. Pastikan Anda sudah <strong>lulus post-test</strong> untuk membuka modul berikutnya.
                        </p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Error Alert -->
            <?php if (isset($_SESSION['gagal_post_test'])) { ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-danger-custom alert-custom" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Gagal Lulus Post Test!</strong> Silakan ulangi kembali untuk melanjutkan pembelajaran.
                        <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <?php }
            unset($_SESSION['gagal_post_test']);
            ?>

            <!-- Stats Section -->
            <?php
            // Mengambil jumlah modul yang tersedia
            $sql_modules = "SELECT COUNT(*) as total FROM module";
            $query_modules = mysqli_query($conn, $sql_modules);
            $module_result = mysqli_fetch_assoc($query_modules);
            $total_modules = $module_result['total'] ?? 7;


            
            // Cek modul yang sudah dipelajari (jika ada)
            $sql_learned = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}'";
            $query_learned = mysqli_query($conn, $sql_learned);
            $learned_module = mysqli_num_rows($query_learned);
            ?>
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="progress-section">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="stat-number"><?php echo $total_modules; ?></div>
                                <small>Total Modul</small>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-number"><?php echo $learned_module; ?></div>
                                <small>Modul Dipelajari</small>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-number">1</div>
                                <small>Quiz Tersedia</small>
                            </div>
                        </div>
                        <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                        <div class="text-center">
                            <h5 class="mb-0">
                                <i class="fas fa-graduation-cap me-2"></i>
                                Pembelajaran E-Learning Biologi
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modules Section -->
            <div class="row">
                <?php
                if (isset($level_modul)) {
                    $sql = "SELECT * FROM module ORDER BY id ASC";
                    $query = mysqli_query($conn, $sql);
                    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
                    
                    // HANYA CEK dari post_test_e_learning_result yang status LULUS
                    $passed_modules_query = mysqli_query($conn, "SELECT DISTINCT module_id FROM post_test_e_learning_result WHERE student_id = '{$_SESSION['student_id']}' AND status = 'lulus'");
                    $completed_modules = [];
                    while ($row = mysqli_fetch_assoc($passed_modules_query)) {
                        $completed_modules[] = $row['module_id'];
                    }
                    
                    foreach ($result as $key => $r) {
                        $is_completed = in_array($r['id'], $completed_modules);
                        
                        // Cek apakah modul sebelumnya sudah selesai (untuk modul 2 dst)
                        $is_locked = false;
                        if ($key > 0) {
                            $previous_module_id = $result[$key - 1]['id'];
                            $is_locked = !in_array($previous_module_id, $completed_modules);
                        }
                        
                        // Set status
                        if ($is_completed) {
                            $status_class = 'status-completed';
                            $status_text = 'Selesai';
                            $status_icon = 'fas fa-check-circle';
                        } elseif ($is_locked) {
                            $status_class = 'status-locked';
                            $status_text = 'Terkunci';
                            $status_icon = 'fas fa-lock';
                        } else {
                            $status_class = 'status-available';
                            $status_text = 'Tersedia';
                            $status_icon = 'fas fa-play-circle';
                        }
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <?php if (!$is_locked) { ?>
                    <a href="module-e-learning.php?module=<?php echo $r['id']; ?>" class="text-decoration-none">
                    <?php } else { ?>
                    <div class="text-decoration-none" style="cursor: not-allowed;">
                    <?php } ?>
                        <div class="module-card <?php echo $is_locked ? 'locked-card' : ''; ?>">
                            <?php if ($is_completed) { ?>
                            <div class="completion-badge">
                                <i class="fas fa-check"></i>
                            </div>
                            <?php } ?>
                            
                            <?php if ($is_locked) { ?>
                            <div class="lock-overlay">
                                <i class="fas fa-lock fa-3x"></i>
                                <p class="mt-2 mb-0">Selesaikan modul sebelumnya</p>
                            </div>
                            <?php } ?>
                            
                            <div class="module-number">
                                <?php echo $r['number']; ?>
                            </div>
                            
                            <div class="module-title">
                                Modul <?php echo $r['number']; ?>
                            </div>
                            
                            <div class="module-description">
                                <?php echo $r['module_desc']; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="module-status <?php echo $status_class; ?>">
                                    <i class="<?php echo $status_icon; ?> me-1"></i>
                                    <?php echo $status_text; ?>
                                </span>
                                
                                <div class="text-end">
                                    <?php if (!$is_locked) { ?>
                                    <i class="fas fa-arrow-right" style="color: #00C851;"></i>
                                    <?php } else { ?>
                                    <i class="fas fa-lock" style="color: #999;"></i>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php if (!$is_locked) { ?>
                    </a>
                    <?php } else { ?>
                    </div>
                    <?php } ?>
                </div>
                <?php
                    }
                } else {
                ?>
                <div class="col-12">
                    <div class="elearning-card p-5 text-center">
                        <i class="fas fa-clock fa-5x text-muted mb-3"></i>
                        <h3 class="text-muted">Proses Penghitungan Pre-Test</h3>
                        <p class="lead text-muted">Silahkan tunggu hasil pre-test yang masih diproses</p>
                        <a href="index.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        // Initialize progress tracking
        updateProgress();
        
        // Module card hover effects
        $('.module-card').hover(
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
        
        // Scroll animations
        $(window).scroll(function() {
            $('.module-card').each(function() {
                const elementTop = $(this).offset().top;
                const elementBottom = elementTop + $(this).outerHeight();
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();
                
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('in-view');
                }
            });
        });
        
        // Module progress tracking
        $('.module-card').on('click', function(e) {
            const href = $(this).parent('a').attr('href');
            if (href) {
                // Show loading indication
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                
                toast.fire({
                    icon: 'info',
                    title: 'Memuat modul...',
                    background: '#00C851',
                    color: 'white'
                });
            }
        });
    });
    
    function updateProgress() {
        // Animate progress bar
        const progressBar = $('.progress-bar-fill');
        const percentage = progressBar.css('width');
        
        progressBar.css('width', '0%').animate({
            width: percentage
        }, 1500);
        
        // Animate stat numbers
        $('.stat-number').each(function() {
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
    
    // Quiz button animation
    function animateQuizButton() {
        $('.btn-quiz').addClass('animate__pulse');
        setTimeout(() => {
            $('.btn-quiz').removeClass('animate__pulse');
        }, 1000);
    }
    
    // Animate stats on page load
    function animateStats() {
        $('.stat-number').each(function(index) {
            const $this = $(this);
            const finalText = $this.text();
            
            // Skip infinity symbol
            if (finalText === '∞') return;
            
            const target = parseInt(finalText) || 0;
            let current = 0;
            
            const increment = target / 20;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                $this.text(Math.floor(current));
            }, 50);
        });
    }
    
    // Call animations on ready
    $(document).ready(function() {
        setTimeout(animateStats, 500);
        setTimeout(animateQuizButton, 2000);
    });
    </script>
</body>
</html>