<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
    exit();
}

// mengambil data materi dari database
$module_id = mysqli_real_escape_string($conn, $_GET['module']);
$student_id = $_SESSION['student_id'];

$sql = "SELECT * FROM module WHERE id = '{$module_id}'";
$query = mysqli_query($conn, $sql);
$modul = mysqli_fetch_array($query, MYSQLI_ASSOC);

// Cek apakah user sudah pernah mengerjakan post-test untuk modul ini
$sql_check = "SELECT * FROM post_test_adaptive_result 
              WHERE student_id = '{$student_id}' 
              AND module_id = '{$module_id}' 
              ORDER BY id DESC LIMIT 1";
$query_check = mysqli_query($conn, $sql_check);
$has_taken = mysqli_num_rows($query_check) > 0;
$last_result = $has_taken ? mysqli_fetch_assoc($query_check) : null;

// Cek apakah user ingin retry untuk perbaiki nilai (dari parameter URL)
$allow_retry = isset($_GET['retry']) && $_GET['retry'] == '1';

// PROTEKSI: Jika sudah lulus dan TIDAK retry, redirect ke halaman hasil
if ($has_taken && $last_result['status'] == 'lulus' && !$allow_retry) {
    header('location: hasil-post-test.php?module=' . $module_id);
    exit();
}

// Set flag untuk tampilan
$is_retry_mode = ($has_taken && $last_result['status'] == 'lulus' && $allow_retry);
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Post Test - <?php echo $modul['module_desc']; ?> - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --posttest-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --posttest-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --posttest-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --posttest-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --posttest-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --posttest-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .posttest-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .posttest-header {
            background: var(--posttest-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .posttest-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: posttestFloat 12s ease-in-out infinite;
        }
        
        @keyframes posttestFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-10deg); }
        }
        
        .quiz-container {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid #00C851;
        }
        
        .question-item {
            background: #f8fff8;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .question-item:hover {
            border-color: rgba(0, 200, 81, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .question-number {
            background: var(--posttest-primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3436;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .choice-container {
            margin-left: 3.5rem;
        }
        
        .form-check {
            background: white;
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .form-check:hover {
            border-color: #00C851;
            background: #f0fff0;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 200, 81, 0.1);
        }
        
        .form-check-input {
            width: 20px;
            height: 20px;
            margin-right: 1.5rem;
            margin-top: 0;
            border: 2px solid #dee2e6;
            background-color: #fff;
            border-radius: 50%;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
        }
        
        .form-check-input:focus {
            border-color: #00C851;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 200, 81, 0.25);
        }
        
        .form-check-input:checked {
            background-color: #00C851;
            border-color: #00C851;
            background-image: none;
        }
        
        .form-check-input:checked::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
        
        .form-check:has(input:checked) {
            border-color: #00C851;
            background: linear-gradient(135deg, #f0fff0 0%, #e8f5e8 100%);
        }
        
        .form-check-input:checked + .form-check-label {
            color: #00C851;
            font-weight: 600;
        }
        
        .form-check-label {
            font-size: 1rem;
            line-height: 1.6;
            color: #495057;
            cursor: pointer;
            flex-grow: 1;
            margin-bottom: 0;
        }
        
        .submit-section {
            background: var(--posttest-success);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .submit-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: submitPulse 6s ease-in-out infinite;
        }
        
        @keyframes submitPulse {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.05); }
        }
        
        .btn-submit {
            background: white;
            color: #00bb2d;
            border: none;
            padding: 1.2rem 4rem;
            font-size: 1.3rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
            min-width: 250px;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
            color: #00bb2d;
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
        
        .progress-bar-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            height: 8px;
            margin-top: 2rem;
            overflow: hidden;
        }
        
        .progress-bar {
            background: white;
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .navigation-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .nav-button {
            background: var(--posttest-info);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .nav-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            color: white;
            text-decoration: none;
        }
        
        @media (max-width: 768px) {
            .posttest-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .quiz-container {
                padding: 2rem 1rem;
            }
            
            .question-item {
                padding: 1.5rem;
            }
            
            .choice-container {
                margin-left: 0;
                margin-top: 1rem;
            }
            
            .floating-icons {
                display: none;
            }
            
            .btn-submit {
                padding: 1rem 2rem;
                min-width: 200px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--posttest-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
                        <a class="nav-link text-white" href="modul.php">
                            <i class="fas fa-books me-1"></i>Modul
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
            <?php if ($is_retry_mode) { ?>
            <!-- Alert Mode Retry -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading">
                            <i class="fas fa-redo me-2"></i>
                            Mode Perbaikan Nilai
                        </h5>
                        <p class="mb-2">
                            <strong>Nilai Terakhir:</strong> <?php echo number_format($last_result['score'], 0); ?> | 
                            <strong>Status:</strong> LULUS ✓
                        </p>
                        <hr>
                        <p class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda sudah lulus, tetapi bisa mengerjakan ulang untuk memperbaiki nilai. 
                            <strong>Sistem akan menyimpan nilai TERBAIK</strong> antara nilai lama dan nilai baru Anda.
                        </p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
            <?php } elseif ($has_taken && $last_result) { ?>
            <!-- Alert Nilai Terakhir -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Nilai Terakhir
                        </h5>
                        <p class="mb-2">
                            <strong>Nilai:</strong> <?php echo number_format($last_result['score'], 0); ?> | 
                            <strong>Benar:</strong> <?php echo $last_result['correct_answers']; ?>/<?php echo $last_result['total_questions']; ?> | 
                            <strong>Status:</strong> <?php echo strtoupper($last_result['status']); ?>
                        </p>
                        <hr>
                        <p class="mb-0"><i class="fas fa-info-circle me-2"></i>Anda perlu nilai minimal 70 untuk lulus. Silakan coba lagi!</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="posttest-card">
                        <div class="posttest-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-clipboard-question"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <div class="floating-icons icon3">
                                <i class="fas fa-dna"></i>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-clipboard-check me-3"></i>Post Test
                                    </h1>
                                    <h3 class="mb-3"><?php echo $modul['module_desc']; ?></h3>
                                    <p class="lead mb-3">Uji pemahaman Anda setelah mempelajari materi ini</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-clock me-1"></i>Waktu Unlimited
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-question-circle me-1"></i>Multiple Choice
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-award me-1"></i>Passing Grade 70%
                                        </span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar" id="questionProgress" style="width: 0%;"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-brain" style="font-size: 6rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="navigation-card">
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <a href="module.php?module=<?php echo $_GET['module']; ?>" class="nav-button">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Materi
                            </a>
                            <div class="text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Dashboard > Modul > Materi > Post Test
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Section -->
            <div class="row">
                <div class="col-12">
                    <div class="quiz-container">
                        <form action="../action/save-post-test-adaptive.php" method="POST" id="postTestForm">
                            <input type="hidden" name="module_id" value="<?php echo $module_id ?>">
                            <?php
                            //GET QUESTION
                            $sql = "SELECT * FROM module_question WHERE module_id = '{$_GET['module']}' ORDER BY RAND()";
                            $query = mysqli_query($conn, $sql);
                            $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
                            $no = 1;
                            $totalQuestions = count($result);
                            
                            // perulangan untuk menampilkan pertanyaan
                            foreach ($result as $key => $r) { ?>
                                <div class="question-item" data-question="<?php echo $no; ?>">
                                    <div class="d-flex align-items-start">
                                        <div class="question-number">
                                            <?php echo $no; ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="question-text">
                                                <?php echo $r['question'] ?>
                                            </div>
                                            
                                            <div class="choice-container">
                                                <?php
                                                // menampilkan jawaban pertanyaan
                                                $sql = "SELECT * FROM module_question_choice WHERE question_id = '{$r['id']}'";
                                                $query = mysqli_query($conn, $sql);
                                                $answer = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                foreach ($answer as $key => $a) { ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" 
                                                               name="question<?php echo $r['id'] ?>" 
                                                               value="<?php echo $key ?>" 
                                                               id="q<?php echo $r['id'] ?>_a<?php echo $key ?>" 
                                                               required>
                                                        <label class="form-check-label" 
                                                               for="q<?php echo $r['id'] ?>_a<?php echo $key ?>">
                                                            <?php echo $a['answer_desc'] ?>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                $no++;
                            } ?>
                            
                            <!-- Submit Section -->
                            <div class="submit-section">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <h3 class="mb-3">
                                            <i class="fas fa-flag-checkered me-3"></i>Selesaikan Post Test
                                        </h3>
                                        <p class="lead mb-3">
                                            Pastikan semua jawaban sudah dipilih sebelum mengirim
                                        </p>
                                        <p class="mb-0" id="completionStatus">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span id="answeredCount">0</span> dari <?php echo $totalQuestions; ?> soal terjawab
                                        </p>
                                    </div>
                                    <div class="col-lg-4 text-center">
                                        <button type="submit" class="btn-submit" id="submitBtn" disabled>
                                            <i class="fas fa-paper-plane me-2"></i>
                                            KIRIM JAWABAN
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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
        const totalQuestions = <?php echo isset($totalQuestions) ? $totalQuestions : 0; ?>;
        
        // Initialize post test interactions
        initializePostTest();
        
        // Track answered questions
        $('input[type="radio"]').on('change', function() {
            updateProgress();
            updateSubmitButton();
        });
        
        // Form submission with confirmation
        $('#postTestForm').on('submit', function(e) {
            e.preventDefault();
            
            const answeredCount = getAnsweredCount();
            
            if (answeredCount < totalQuestions) {
                Swal.fire({
                    title: 'Belum Selesai!',
                    text: `Anda baru menjawab ${answeredCount} dari ${totalQuestions} soal. Yakin ingin mengirim?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF8800',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Periksa Lagi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Kirim Jawaban?',
                    text: 'Pastikan semua jawaban sudah benar sebelum mengirim.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#00C851',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Periksa Lagi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });
            }
        });
        
        // Question item hover effects
        $('.question-item').hover(
            function() {
                $(this).find('.question-number').addClass('animate__pulse');
            },
            function() {
                $(this).find('.question-number').removeClass('animate__pulse');
            }
        );
        
        // Choice selection effects
        $('.form-check').on('click', function() {
            const radio = $(this).find('input[type="radio"]');
            if (!radio.prop('checked')) {
                radio.prop('checked', true).trigger('change');
            }
        });
        
        // Auto-scroll to next unanswered question
        $('input[type="radio"]').on('change', function() {
            setTimeout(() => {
                const nextUnanswered = getNextUnansweredQuestion();
                if (nextUnanswered) {
                    $('html, body').animate({
                        scrollTop: nextUnanswered.offset().top - 100
                    }, 500);
                }
            }, 300);
        });
    });
    
    function initializePostTest() {
        // Add question animations
        $('.question-item').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
            $(this).addClass('animate-in');
        });
        
        // Initialize progress tracking
        updateProgress();
        updateSubmitButton();
        
        // Add keyboard navigation
        $(document).on('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                const nextQuestion = getNextUnansweredQuestion();
                if (nextQuestion) {
                    nextQuestion[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    function updateProgress() {
        const answeredCount = getAnsweredCount();
        const totalQuestions = <?php echo isset($totalQuestions) ? $totalQuestions : 0; ?>;
        const percentage = (answeredCount / totalQuestions) * 100;
        
        $('#questionProgress').css('width', percentage + '%');
        $('#answeredCount').text(answeredCount);
        
        // Update completion status
        if (answeredCount === totalQuestions) {
            $('#completionStatus').html('<i class="fas fa-check-circle me-2"></i>Semua soal sudah terjawab!');
            $('#completionStatus').removeClass('text-warning').addClass('text-success');
        } else {
            $('#completionStatus').html(`<i class="fas fa-info-circle me-2"></i><span id="answeredCount">${answeredCount}</span> dari ${totalQuestions} soal terjawab`);
            $('#completionStatus').removeClass('text-success').addClass('text-warning');
        }
    }
    
    function updateSubmitButton() {
        const answeredCount = getAnsweredCount();
        const totalQuestions = <?php echo isset($totalQuestions) ? $totalQuestions : 0; ?>;
        const submitBtn = $('#submitBtn');
        
        if (answeredCount === totalQuestions) {
            submitBtn.prop('disabled', false);
            submitBtn.removeClass('btn-secondary').addClass('btn-submit');
            submitBtn.html('<i class="fas fa-paper-plane me-2"></i>KIRIM JAWABAN');
        } else {
            submitBtn.prop('disabled', false); // Allow partial submission
            submitBtn.html(`<i class="fas fa-paper-plane me-2"></i>KIRIM (${answeredCount}/${totalQuestions})`);
        }
    }
    
    function getAnsweredCount() {
        const questionGroups = $('input[type="radio"][name^="question"]').map(function() {
            return this.name;
        }).get();
        
        const uniqueQuestions = [...new Set(questionGroups)];
        let answeredCount = 0;
        
        uniqueQuestions.forEach(function(questionName) {
            if ($('input[name="' + questionName + '"]:checked').length > 0) {
                answeredCount++;
            }
        });
        
        return answeredCount;
    }
    
    function getNextUnansweredQuestion() {
        const questions = $('.question-item');
        
        for (let i = 0; i < questions.length; i++) {
            const question = $(questions[i]);
            const radios = question.find('input[type="radio"]');
            const isAnswered = radios.filter(':checked').length > 0;
            
            if (!isAnswered) {
                return question;
            }
        }
        
        return null;
    }
    
    function submitForm() {
        // Show loading
        Swal.fire({
            title: 'Mengirim Jawaban...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit form
        document.getElementById('postTestForm').submit();
    }
    
    // Add CSS for animations and radio button fixes
    const style = document.createElement('style');
    style.textContent = `
        .animate-in {
            animation: slideInUp 0.6s ease-out forwards;
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
        
        .question-item {
            opacity: 0;
        }
        
        .form-check:hover .form-check-input {
            transform: scale(1.05);
            border-width: 3px;
        }
        
        .progress-bar {
            animation: progressGlow 2s ease-in-out infinite alternate;
        }
        
        @keyframes progressGlow {
            from { box-shadow: 0 0 5px rgba(255,255,255,0.5); }
            to { box-shadow: 0 0 15px rgba(255,255,255,0.8); }
        }
        
        /* Enhanced radio button styling for better consistency */
        .form-check-input[type="radio"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: #fff;
            margin: 0;
            color: currentColor;
            width: 20px;
            height: 20px;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            transform: translateY(0);
            display: grid;
            place-content: center;
        }
        
        .form-check-input[type="radio"]:checked::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transform: scale(1);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em #fff;
            background-color: #fff;
        }
        
        .form-check-input[type="radio"]:not(:checked)::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
        }
        
        /* Ensure consistent alignment */
        .form-check {
            align-items: flex-start !important;
            padding: 1.2rem 1.5rem;
            gap: 1.5rem;
        }
        
        .form-check-input {
            margin-top: 2px; /* Align with first line of text */
            margin-right: 0; /* Remove margin since we use gap */
        }
        
        /* Remove bootstrap default styles that might interfere */
        .form-check .form-check-input {
            float: none;
        }
        
        .form-check-label {
            padding-left: 0;
            line-height: 1.5;
        }
        
        /* Better text alignment and spacing */
        .choice-container .form-check {
            display: flex;
            align-items: flex-start;
            text-align: left;
        }
        
        .form-check-label {
            word-break: break-word;
            hyphens: auto;
        }
    `;
    document.head.appendChild(style);
    </script>
</body>

</html>