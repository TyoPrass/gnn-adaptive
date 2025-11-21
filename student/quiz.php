<?php

session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

include('../config/db.php');

$sql = "SELECT * FROM quiz_result WHERE student_id = '{$_SESSION['student_id']}'";
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) > 0){
    $quiz_done = true;
    $quiz_result = mysqli_fetch_assoc($query);
    $quiz_score = $quiz_result['nilai'];
}
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Quiz Biologi - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --quiz-primary: linear-gradient(135deg, #4169E1 0%, #6495ED 100%);
            --quiz-secondary: linear-gradient(135deg, #FF6347 0%, #FF7F50 100%);
            --quiz-success: linear-gradient(135deg, #32CD32 0%, #98FB98 100%);
            --quiz-warning: linear-gradient(135deg, #FFD700 0%, #FFF8DC 100%);
            --quiz-info: linear-gradient(135deg, #20B2AA 0%, #48D1CC 100%);
            --quiz-completed: linear-gradient(135deg, #9370DB 0%, #DDA0DD 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E6F3FF 0%, #F0F8FF 50%, #E0F6FF 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .quiz-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .quiz-header {
            background: var(--quiz-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .quiz-header.completed {
            background: var(--quiz-completed);
        }
        
        .quiz-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            animation: quizFloat 10s ease-in-out infinite;
        }
        
        @keyframes quizFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(10deg); }
        }
        
        .question-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .question-card:hover {
            border-color: rgba(65, 105, 225, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .question-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background: var(--quiz-primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .question-card:hover::before {
            transform: scaleY(1);
        }
        
        .question-number {
            background: var(--quiz-primary);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .question-number::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--quiz-secondary);
            opacity: 0;
            transform: scale(0);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 0; transform: scale(0); }
            50% { opacity: 0.3; transform: scale(1.2); }
            100% { opacity: 0; transform: scale(1.5); }
        }
        
        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2F4F4F;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .custom-radio {
            position: relative;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .custom-radio:hover {
            transform: translateX(8px);
        }
        
        .custom-radio input[type="radio"] {
            display: none;
        }
        
        .custom-radio label {
            display: flex;
            align-items: center;
            padding: 1.2rem 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .custom-radio label::before {
            content: '';
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 3px solid #dee2e6;
            margin-right: 15px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .custom-radio label::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 40%, rgba(65, 105, 225, 0.1) 50%, transparent 60%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        
        .custom-radio:hover label {
            background: rgba(65, 105, 225, 0.1);
            border-color: rgba(65, 105, 225, 0.3);
        }
        
        .custom-radio:hover label::after {
            transform: translateX(100%);
        }
        
        .custom-radio input[type="radio"]:checked + label {
            background: rgba(65, 105, 225, 0.15);
            border-color: #4169E1;
            color: #4169E1;
            box-shadow: 0 3px 15px rgba(65, 105, 225, 0.2);
        }
        
        .custom-radio input[type="radio"]:checked + label::before {
            border-color: #4169E1;
            background: #4169E1;
            box-shadow: inset 0 0 0 3px white;
        }
        
        .progress-section {
            background: var(--quiz-info);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .progress-bar-custom {
            height: 10px;
            border-radius: 15px;
            background: rgba(255,255,255,0.3);
            overflow: hidden;
        }
        
        .progress-bar-fill {
            height: 100%;
            background: white;
            border-radius: 15px;
            transition: width 0.6s ease;
            position: relative;
        }
        
        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.3) 50%, transparent 60%);
            animation: shimmer 2s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .submit-section {
            background: var(--quiz-success);
            border-radius: 15px;
            padding: 2.5rem;
            text-align: center;
            margin-top: 2rem;
        }
        
        .btn-submit {
            background: var(--quiz-primary);
            border: none;
            color: white;
            padding: 1.2rem 3.5rem;
            font-size: 1.3rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(65, 105, 225, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-submit:hover::before {
            left: 100%;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 35px rgba(65, 105, 225, 0.4);
            color: white;
        }
        
        .completed-section {
            background: var(--quiz-completed);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .completed-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: celebration 3s ease-in-out infinite;
        }
        
        .completed-section .btn {
            position: relative;
            z-index: 10;
            font-weight: 600;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            border: 2px solid transparent;
        }
        
        .completed-section .btn-light {
            background: white;
            color: #9370DB;
            border-color: white;
        }
        
        .completed-section .btn-light:hover {
            background: #f8f9fa;
            color: #9370DB;
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }
        
        .completed-section .btn-outline-light {
            background: transparent;
            color: white;
            border-color: white;
        }
        
        .completed-section .btn-outline-light:hover {
            background: white;
            color: #9370DB;
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }
        
        @keyframes celebration {
            0%, 100% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
        }
        
        /* Fix untuk tombol yang tidak bisa diklik */
        .completed-section > * {
            position: relative;
            z-index: 10;
        }
        
        .completed-section .btn {
            pointer-events: auto !important;
            cursor: pointer !important;
        }
        
        .floating-icons {
            position: absolute;
            font-size: 6rem;
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
        }
        
        .floating-icons.icon1 {
            top: 10%;
            right: 10%;
            animation-delay: 0s;
        }
        
        .floating-icons.icon2 {
            bottom: 10%;
            left: 10%;
            animation-delay: 2s;
        }
        
        .floating-icons.icon3 {
            top: 50%;
            right: 5%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.1; }
            50% { transform: translateY(-20px) rotate(5deg); opacity: 0.2; }
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
        
        .timer-section {
            background: var(--quiz-warning);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .timer-display {
            font-size: 1.5rem;
            font-weight: bold;
            color: #FF6347;
        }
        
        @media (max-width: 768px) {
            .quiz-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .question-card {
                padding: 1.5rem;
            }
            
            .custom-radio label {
                padding: 1rem;
                font-size: 0.9rem;
            }
            
            .floating-icons {
                display: none;
            }
            
            .completed-section {
                padding: 3rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--quiz-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-brain me-2"></i>MyIRT Quiz
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
            <?php if(isset($quiz_done) && $quiz_done == true) : ?>
            <!-- Quiz Completed State -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="quiz-card">
                        <div class="quiz-header completed">
                            <div class="floating-icons icon1">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="floating-icons icon3">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-check-circle" style="font-size: 5rem; margin-bottom: 1rem; opacity: 0.9;"></i>
                                <h1 class="display-4 fw-bold mb-3">Selamat! 🎉</h1>
                                <p class="lead mb-3">Anda telah berhasil menyelesaikan Quiz Biologi</p>
                                <?php if (isset($quiz_score)) { ?>
                                <div class="mt-3">
                                    <span class="badge" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white; font-size: 1.5rem; padding: 1rem 2rem; box-shadow: 0 5px 20px rgba(255,165,0,0.4);">
                                        <i class="fas fa-award me-2"></i>Nilai Anda: <?php echo number_format($quiz_score, 0); ?>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Completed Section with proper spacing -->
            <div class="row">
                <div class="col-12">
                    <div class="completed-section">
                        <h2 class="mb-4">
                            <i class="fas fa-clipboard-check me-3"></i>Quiz Telah Selesai!
                        </h2>
                        
                        <div class="stats-row">
                            <div class="stat-item">
                                <span class="stat-number">✓</span>
                                <small>Quiz Selesai</small>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">7</span>
                                <small>Soal per Modul</small>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">100%</span>
                                <small>Partisipasi</small>
                            </div>
                        </div>
                        
                        <p class="lead mb-4">
                            Terima kasih telah menyelesaikan quiz dengan baik. 
                            Hasil quiz akan membantu menentukan materi pembelajaran yang sesuai untuk Anda.
                        </p>
                        
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="index.php" class="btn btn-light btn-lg px-4" style="position: relative; z-index: 100;">
                                <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                            </a>
                            <a href="topik.php" class="btn btn-outline-light btn-lg px-4" style="position: relative; z-index: 100;">
                                <i class="fas fa-book me-2"></i>Lihat Materi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else : ?>
            <!-- Quiz Not Completed State - Show Questions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="quiz-card">
                        <div class="quiz-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-brain"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-clipboard-question me-3"></i>Quiz Biologi
                                    </h1>
                                    <p class="lead mb-3">Uji pemahaman Anda tentang materi biologi yang telah dipelajari</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-clock me-1"></i>Tidak ada batas waktu
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-random me-1"></i>7 soal per modul
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-award me-1"></i>Evaluasi pembelajaran
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-brain" style="font-size: 6rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Section -->
                        <div class="p-4">
                            <div class="progress-section">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">
                                            <i class="fas fa-chart-line me-2"></i>Progress Quiz
                                        </h5>
                                        <div class="progress-bar-custom">
                                            <div class="progress-bar-fill" id="quizProgressBar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="stat-number" id="quizProgressText">0%</div>
                                        <small>Selesai</small>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <small><i class="fas fa-list-ol me-1"></i>Soal: <span id="currentQuizQuestion">0</span> dari <span id="totalQuizQuestions">0</span></small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small><i class="fas fa-check-double me-1"></i>Terjawab: <span id="answeredQuizQuestions">0</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Form -->
            <div class="row">
                <div class="col-12">
                    <form action="../action/save-quiz.php" method="POST" id="quizForm">
                        <div id="quizQuestionsContainer">
                            <?php
                            $no = 1;
                            $sql = "SELECT * FROM module";
                            $query = mysqli_query($conn, $sql);
                            $module = mysqli_fetch_all($query, MYSQLI_ASSOC);

                            foreach ($module as $key => $m) {
                                $sql = "SELECT * FROM module_question WHERE module_id = '{$m['id']}' ORDER BY RAND() LIMIT 7";
                                $query = mysqli_query($conn, $sql);
                                $question = mysqli_fetch_all($query, MYSQLI_ASSOC);

                                foreach ($question as $key => $q) {
                            ?>
                            <div class="question-card" data-question="<?php echo $no; ?>">
                                <div class="d-flex align-items-start">
                                    <div class="question-number">
                                        <?php echo $no; ?>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="question-text">
                                            <?php echo $q['question']; ?>
                                        </div>
                                        
                                        <div class="options-container">
                                            <?php
                                            $sql = "SELECT * FROM module_question_choice WHERE question_id = '{$q['id']}'";
                                            $query = mysqli_query($conn, $sql);
                                            $answer = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                            foreach ($answer as $key => $a) { 
                                            ?>
                                            <div class="custom-radio">
                                                <input type="radio" 
                                                       id="quiz_q<?php echo $q['id']; ?>_a<?php echo $a['id']; ?>"
                                                       name="question<?php echo $q['id']; ?>"
                                                       value="<?php echo $key ?>" 
                                                       required>
                                                <label for="quiz_q<?php echo $q['id']; ?>_a<?php echo $a['id']; ?>">
                                                    <?php echo $a['answer_desc']; ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                $no++;
                                }
                            }
                            ?>
                        </div>

                        <!-- Submit Section -->
                        <div class="submit-section">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h4 class="text-white mb-2">
                                        <i class="fas fa-rocket me-2"></i>Selesaikan Quiz Biologi
                                    </h4>
                                    <p class="text-white mb-0">
                                        Periksa kembali jawaban Anda sebelum mengirim quiz
                                    </p>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <button type="submit" class="btn-submit" id="quizSubmitBtn">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        KIRIM QUIZ
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-3 text-center">
                                <small class="text-white">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Quiz ini akan membantu evaluasi pemahaman Anda tentang materi biologi
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        // Initialize quiz
        updateQuizProgress();
        
        // Track quiz question changes
        $('input[type="radio"]').on('change', function() {
            updateQuizProgress();
            
            // Add visual feedback
            const card = $(this).closest('.question-card');
            card.addClass('answered');
            
            // Smooth scroll to next question
            const currentCard = $(this).closest('.question-card');
            const nextCard = currentCard.next('.question-card');
            
            if (nextCard.length) {
                setTimeout(() => {
                    $('html, body').animate({
                        scrollTop: nextCard.offset().top - 100
                    }, 600);
                }, 300);
            } else {
                // Last question, scroll to submit
                setTimeout(() => {
                    $('html, body').animate({
                        scrollTop: $('.submit-section').offset().top - 100
                    }, 600);
                }, 300);
            }
        });
        
        // Quiz form submission with confirmation
        $('#quizForm').on('submit', function(e) {
            e.preventDefault();
            
            const totalQuestions = $('.question-card').length;
            const answeredQuestions = $('input[type="radio"]:checked').length;
            
            if (answeredQuestions < totalQuestions) {
                Swal.fire({
                    title: 'Quiz Belum Lengkap!',
                    text: `Anda baru menjawab ${answeredQuestions} dari ${totalQuestions} soal. Yakin ingin melanjutkan?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4169E1',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Kirim Quiz!',
                    cancelButtonText: 'Lanjut Mengerjakan',
                    customClass: {
                        popup: 'animated zoomIn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitQuiz();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Kirim Quiz Sekarang?',
                    text: 'Pastikan semua jawaban sudah benar sebelum mengirim.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4169E1',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Kirim Quiz!',
                    cancelButtonText: 'Periksa Lagi',
                    customClass: {
                        popup: 'animated pulse'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitQuiz();
                    }
                });
            }
        });
        
        // Auto-save functionality (every 45 seconds)
        setInterval(autoSaveQuiz, 45000);
        
        // Warn before leaving page
        window.addEventListener('beforeunload', function(e) {
            const answeredQuestions = $('input[type="radio"]:checked').length;
            if (answeredQuestions > 0) {
                e.preventDefault();
                e.returnValue = '';
                return 'Anda memiliki jawaban quiz yang belum tersimpan. Yakin ingin meninggalkan halaman?';
            }
        });
        
        // Add answered class styling
        $('.question-card').each(function() {
            $(this).hover(
                function() {
                    $(this).find('.question-number').addClass('animate__pulse');
                },
                function() {
                    $(this).find('.question-number').removeClass('animate__pulse');
                }
            );
        });
    });
    
    function updateQuizProgress() {
        const totalQuestions = $('.question-card').length;
        const answeredQuestions = $('input[type="radio"]:checked').length;
        const percentage = Math.round((answeredQuestions / totalQuestions) * 100);
        
        $('#totalQuizQuestions').text(totalQuestions);
        $('#answeredQuizQuestions').text(answeredQuestions);
        $('#quizProgressText').text(percentage + '%');
        $('#quizProgressBar').css('width', percentage + '%');
        
        // Update current question (last answered + 1)
        $('#currentQuizQuestion').text(Math.min(answeredQuestions + 1, totalQuestions));
        
        // Add completion effects when 100%
        if (percentage === 100) {
            $('#quizProgressBar').addClass('completed');
            
            // Celebration effect
            setTimeout(() => {
                const submitSection = $('.submit-section');
                submitSection.addClass('animate__animated animate__pulse');
                
                // Scroll to submit
                $('html, body').animate({
                    scrollTop: submitSection.offset().top - 100
                }, 800);
            }, 500);
        }
    }
    
    function submitQuiz() {
        // Show loading with quiz theme
        Swal.fire({
            title: 'Mengirim Quiz...',
            html: '<div class="d-flex align-items-center justify-content-center"><i class="fas fa-brain fa-2x text-primary me-3"></i><span>Memproses jawaban quiz Anda</span></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit quiz via AJAX
        $.ajax({
            url: '../action/save-quiz.php',
            method: 'POST',
            data: $('#quizForm').serialize(),
            success: function(response) {
                Swal.fire({
                    title: 'Quiz Berhasil Dikirim! 🎉',
                    html: '<div class="text-center"><i class="fas fa-trophy fa-3x text-warning mb-3"></i><br>Quiz biologi Anda telah berhasil disimpan.<br><small class="text-muted">Hasil akan membantu personalisasi pembelajaran Anda</small></div>',
                    icon: 'success',
                    confirmButtonColor: '#4169E1',
                    confirmButtonText: 'Lihat Dashboard',
                    customClass: {
                        popup: 'animated bounceIn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Oops! Terjadi Kesalahan',
                    text: 'Tidak dapat menyimpan quiz. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Coba Lagi'
                });
            }
        });
    }
    
    function autoSaveQuiz() {
        const answeredQuestions = $('input[type="radio"]:checked').length;
        if (answeredQuestions > 0) {
            // Show subtle auto-save notification
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            
            toast.fire({
                icon: 'info',
                title: '<i class="fas fa-save me-2"></i>Quiz tersimpan otomatis',
                background: '#4169E1',
                color: 'white'
            });
        }
    }
    
    // Scroll animations for question cards
    $(window).scroll(function() {
        $('.question-card').each(function() {
            const elementTop = $(this).offset().top;
            const elementBottom = elementTop + $(this).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('in-view');
            }
        });
    });
    
    // Add visual feedback for answered questions
    $('input[type="radio"]').on('change', function() {
        const questionCard = $(this).closest('.question-card');
        questionCard.addClass('answered');
        
        // Add checkmark animation
        const questionNumber = questionCard.find('.question-number');
        questionNumber.html('<i class="fas fa-check"></i>');
        
        setTimeout(() => {
            const originalNumber = questionCard.data('question');
            questionNumber.text(originalNumber);
        }, 1000);
    });
    </script>
</body>
</html>