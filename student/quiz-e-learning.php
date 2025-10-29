<?php

session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

include('../config/db.php');

$sql = "SELECT * FROM quiz_result_e_learning WHERE student_id = '{$_SESSION['student_id']}'";
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) > 0){
    $quiz_done = true;
}
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Quiz E-Learning Biologi - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --quiz-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --quiz-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --quiz-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --quiz-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --quiz-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --quiz-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
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
        
        .quiz-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: quizFloat 10s ease-in-out infinite;
        }
        
        @keyframes quizFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(-5deg); }
        }
        
        .question-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px solid transparent;
            transition: all 0.4s ease;
            position: relative;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        
        .question-card:hover {
            border-color: rgba(0, 200, 81, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.12);
        }
        
        .question-number {
            background: var(--quiz-primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
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
            animation: numberPulse 3s ease-in-out infinite;
        }
        
        @keyframes numberPulse {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 0.3; transform: scale(1.3); }
        }
        
        .question-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2E7D32;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .answer-option {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1.2rem 4rem 1.2rem 1.2rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: block;
            width: 100%;
        }
        
        .answer-option:hover {
            background: #e8f5e8;
            border-color: #00C851;
            transform: translateX(5px);
        }
        
        .answer-option.selected {
            background: var(--quiz-primary);
            border-color: #007E33;
            color: white;
        }
        
        .answer-option input[type="radio"] {
            position: absolute;
            left: -9999px;
            opacity: 0;
            width: 0;
            height: 0;
            visibility: hidden;
        }
        
        .answer-text {
            position: relative;
            z-index: 2;
            line-height: 1.5;
            font-weight: 500;
        }
        
        .answer-option .checkmark {
            position: absolute;
            top: 50%;
            right: 1.5rem;
            transform: translateY(-50%);
            height: 24px;
            width: 24px;
            background-color: white;
            border-radius: 50%;
            border: 3px solid #ddd;
            transition: all 0.3s ease;
            z-index: 3;
        }
        
        .answer-option:hover .checkmark {
            border-color: #00C851;
            transform: translateY(-50%) scale(1.1);
        }
        
        .answer-option.selected .checkmark {
            background-color: white;
            border-color: white;
            transform: translateY(-50%) scale(1.1);
        }
        
        .answer-option.selected .checkmark:after {
            content: "";
            position: absolute;
            display: block;
            left: 50%;
            top: 50%;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #00C851;
            transform: translate(-50%, -50%);
            animation: checkPulse 0.3s ease;
        }
        
        @keyframes checkPulse {
            0% { transform: translate(-50%, -50%) scale(0); }
            100% { transform: translate(-50%, -50%) scale(1); }
        }
        
        .quiz-progress {
            background: var(--quiz-info);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .progress-bar-custom {
            height: 8px;
            border-radius: 10px;
            background: rgba(255,255,255,0.3);
            overflow: hidden;
            margin: 1rem 0;
        }
        
        .progress-bar-fill {
            height: 100%;
            background: white;
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        
        .submit-section {
            background: var(--quiz-success);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            margin-top: 3rem;
            color: white;
        }
        
        .btn-submit {
            background: white;
            color: #00C851;
            border: none;
            padding: 1.2rem 4rem;
            font-size: 1.3rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
            color: #00C851;
        }
        
        .completed-section {
            background: var(--quiz-success);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
            border-radius: 20px;
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
            animation: completedGlow 6s ease-in-out infinite;
        }
        
        /* Fix untuk tombol yang tidak bisa diklik */
        .completed-section > * {
            position: relative;
            z-index: 10;
        }
        
        .completed-section .back-button {
            position: relative;
            z-index: 100;
            pointer-events: auto !important;
            cursor: pointer !important;
            font-weight: 600;
            padding: 1.2rem 2.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border: 2px solid transparent;
        }
        
        .completed-section .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
        }
        
        @keyframes completedGlow {
            0%, 100% { opacity: 0.1; transform: scale(1) rotate(0deg); }
            50% { opacity: 0.3; transform: scale(1.1) rotate(180deg); }
        }
        
        .floating-icons {
            position: absolute;
            font-size: 3rem;
            opacity: 0.1;
            animation: floatIcons 8s ease-in-out infinite;
        }
        
        .floating-icons.icon1 {
            top: 20%;
            right: 15%;
            animation-delay: 0s;
        }
        
        .floating-icons.icon2 {
            bottom: 25%;
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
            50% { transform: translateY(-15px) rotate(10deg); opacity: 0.15; }
        }
        
        .back-button {
            background: var(--quiz-info);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 1rem 2.5rem;
            font-weight: bold;
            transition: all 0.4s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .back-button:hover {
            background: var(--quiz-secondary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }
        
        @media (max-width: 768px) {
            .quiz-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .question-card {
                padding: 1.5rem;
            }
            
            .floating-icons {
                display: none;
            }
            
            .submit-section, .completed-section {
                padding: 2rem 1rem;
            }
            
            .btn-submit {
                padding: 1rem 2rem;
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--quiz-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
                        <a class="nav-link text-white" href="index-e-learning.php">
                            <i class="fas fa-laptop-code me-1"></i>E-Learning
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
                    <div class="quiz-card">
                        <div class="quiz-header">
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
                                        <i class="fas fa-clipboard-question me-3"></i>Quiz E-Learning Biologi
                                    </h1>
                                    <p class="lead mb-3">Uji pemahaman Anda tentang konsep-konsep biologi yang telah dipelajari</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-clock me-1"></i>Waktu Tidak Terbatas
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-question-circle me-1"></i>Multiple Choice
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-certificate me-1"></i>Sertifikat Digital
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-trophy" style="font-size: 6rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Content -->
            <?php if(isset($quiz_done)) : ?>
                <!-- Quiz Already Completed -->
                <div class="row">
                    <div class="col-12">
                        <div class="completed-section">
                            <div class="mb-4">
                                <i class="fas fa-trophy fa-5x mb-3"></i>
                            </div>
                            <h2 class="display-6 fw-bold mb-3">Selamat! 🎉</h2>
                            <h4 class="mb-4">Anda Telah Menyelesaikan Quiz E-Learning</h4>
                            <p class="lead mb-4">Terima kasih telah menyelesaikan seluruh pembelajaran e-learning biologi. Quiz Anda telah tersimpan dalam sistem.</p>
                            
                            <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                                <a href="index-e-learning.php" class="back-button" style="position: relative; z-index: 100;">
                                    <i class="fas fa-laptop-code me-2"></i>Kembali ke E-Learning
                                </a>
                                <a href="index.php" class="back-button" style="position: relative; z-index: 100;">
                                    <i class="fas fa-home me-2"></i>Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <!-- Quiz Form -->
                <form action="../action/save-quiz-e-learning.php" method="POST" id="quizForm">
                    <!-- Progress Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="quiz-progress">
                                <h5 class="mb-2">
                                    <i class="fas fa-chart-line me-2"></i>Progress Quiz
                                </h5>
                                <div class="progress-bar-custom">
                                    <div class="progress-bar-fill" id="progressBar" style="width: 0%;"></div>
                                </div>
                                <small id="progressText">0 dari ? pertanyaan dijawab</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Questions -->
                    <div class="row">
                        <div class="col-12">
                            <?php
                            $no = 1;
                            $total_questions = 0;
                            
                            // First, count total questions
                            $sql = "SELECT * FROM module";
                            $query = mysqli_query($conn, $sql);
                            $modules = mysqli_fetch_all($query, MYSQLI_ASSOC);
                            
                            foreach ($modules as $module) {
                                $sql = "SELECT COUNT(*) as count FROM module_question WHERE module_id = '{$module['id']}'";
                                $query = mysqli_query($conn, $sql);
                                $count_result = mysqli_fetch_assoc($query);
                                $questions_per_module = min(7, $count_result['count']);
                                $total_questions += $questions_per_module;
                            }
                            
                            // Now display questions
                            foreach ($modules as $key => $m) {
                                $sql = "SELECT * FROM module_question WHERE module_id = '{$m['id']}' ORDER BY RAND() LIMIT 7";
                                $query = mysqli_query($conn, $sql);
                                $questions = mysqli_fetch_all($query, MYSQLI_ASSOC);

                                foreach ($questions as $key => $q) {
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
                                            
                                            <div class="answers-section">
                                                <?php
                                                $sql = "SELECT * FROM module_question_choice WHERE question_id = '{$q['id']}'";
                                                $query = mysqli_query($conn, $sql);
                                                $answers = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                
                                                foreach ($answers as $key => $a) { ?>
                                                    <label class="answer-option" for="question_<?php echo $q['id']; ?>_<?php echo $a['id']; ?>">
                                                        <input type="radio" 
                                                               id="question_<?php echo $q['id']; ?>_<?php echo $a['id']; ?>"
                                                               name="question<?php echo $q['id']; ?>" 
                                                               value="<?php echo $key ?>" 
                                                               required
                                                               onchange="updateProgress(); selectAnswer(this);">
                                                        <span class="answer-text"><?php echo $a['answer_desc']; ?></span>
                                                        <span class="checkmark"></span>
                                                    </label>
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
                    </div>
                    
                    <!-- Submit Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="submit-section">
                                <h3 class="mb-4">
                                    <i class="fas fa-paper-plane me-2"></i>Selesaikan Quiz
                                </h3>
                                <p class="lead mb-4">Pastikan semua pertanyaan telah dijawab sebelum mengirim quiz</p>
                                
                                <div class="d-flex flex-wrap gap-3 justify-content-center">
                                    <button type="submit" class="btn-submit" id="submitBtn" disabled>
                                        <i class="fas fa-check me-2"></i>KIRIM JAWABAN
                                    </button>
                                    <a href="index-e-learning.php" class="back-button">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                                
                                <div class="mt-3">
                                    <small id="submitStatus">Jawab semua pertanyaan untuk mengaktifkan tombol kirim</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        const totalQuestions = <?php echo isset($total_questions) ? $total_questions : 0; ?>;
        let answeredQuestions = 0;
        
        function updateProgress() {
            answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
            const percentage = totalQuestions > 0 ? (answeredQuestions / totalQuestions) * 100 : 0;
            
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = `${answeredQuestions} dari ${totalQuestions} pertanyaan dijawab`;
            
            const submitBtn = document.getElementById('submitBtn');
            const submitStatus = document.getElementById('submitStatus');
            
            if (answeredQuestions === totalQuestions) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitStatus.textContent = 'Semua pertanyaan telah dijawab. Siap untuk mengirim!';
                submitStatus.style.color = '#00C851';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitStatus.textContent = `Jawab ${totalQuestions - answeredQuestions} pertanyaan lagi untuk mengaktifkan tombol kirim`;
                submitStatus.style.color = '#666';
            }
        }
        
        function selectAnswer(radio) {
            // Remove selected class from all options in this question
            const questionCard = radio.closest('.question-card');
            const allOptions = questionCard.querySelectorAll('.answer-option');
            allOptions.forEach(option => option.classList.remove('selected'));
            
            // Add selected class to chosen option
            radio.closest('.answer-option').classList.add('selected');
        }
        
        // Form submission with confirmation
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (answeredQuestions !== totalQuestions) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Quiz Belum Lengkap',
                    text: `Masih ada ${totalQuestions - answeredQuestions} pertanyaan yang belum dijawab.`,
                    confirmButtonColor: '#FF8800'
                });
                return;
            }
            
            Swal.fire({
                title: 'Kirim Quiz?',
                text: 'Pastikan semua jawaban sudah benar. Setelah dikirim, quiz tidak dapat diubah lagi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00C851',
                cancelButtonColor: '#FF8800',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Periksa Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Mengirim Quiz...',
                        text: 'Mohon tunggu, jawaban Anda sedang diproses.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    this.submit();
                }
            });
        });
        
        // Initialize progress on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>