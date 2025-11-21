<?php

session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
    exit();
}

include('../config/db.php');

// Ambil module_id dari URL
$module_id = mysqli_real_escape_string($conn, $_GET['module']);

// Cek apakah user sudah lulus post-test untuk modul ini
$sql_check = "SELECT * FROM post_test_e_learning_result WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$module_id}' AND status = 'lulus'";
$query_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($query_check) > 0) {
    // Jika sudah lulus, redirect ke index-e-learning
    header('location: index-e-learning.php');
    exit();
}

// Ambil informasi modul
$sql_module = "SELECT * FROM module WHERE id = '{$module_id}'";
$query_module = mysqli_query($conn, $sql_module);
$module_info = mysqli_fetch_assoc($query_module);

// Ambil hasil post-test terakhir jika ada (untuk menampilkan nilai)
$sql_last_result = "SELECT * FROM post_test_e_learning_result WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$module_id}' ORDER BY id DESC LIMIT 1";
$query_last_result = mysqli_query($conn, $sql_last_result);
$last_result = mysqli_fetch_assoc($query_last_result);
$last_score = $last_result ? $last_result['nilai'] : null;
$attempt_count = $last_result ? $last_result['attempt'] : 0;

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Post-Test Modul <?php echo $module_info['number']; ?> - MyIRT E-Learning</title>
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
            --posttest-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
            --posttest-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
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
            animation: posttestFloat 10s ease-in-out infinite;
        }
        
        @keyframes posttestFloat {
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
            background: var(--posttest-primary);
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
            background: var(--posttest-primary);
            border-color: #007E33;
            color: white;
        }
        
        .answer-option input[type="radio"] {
            position: absolute;
            left: -9999px;
            opacity: 0;
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
        }
        
        .answer-option:hover .checkmark {
            border-color: #00C851;
            transform: translateY(-50%) scale(1.1);
        }
        
        .answer-option.selected .checkmark {
            background-color: white;
            border-color: white;
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
        
        .btn-submit:disabled {
            background: #f5f5f5;
            color: #999;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .progress-section {
            background: var(--posttest-info);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .score-badge {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 1rem;
            box-shadow: 0 5px 20px rgba(255,165,0,0.4);
        }
        
        .alert-warning-custom {
            background: var(--posttest-secondary);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .submit-section {
            background: var(--posttest-success);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            margin-top: 3rem;
            color: white;
        }
        
        @media (max-width: 768px) {
            .posttest-header {
                padding: 2rem 1rem;
            }
            
            .question-card {
                padding: 1.5rem;
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
    <nav class="navbar navbar-expand-lg" style="background: var(--posttest-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
                        <a class="nav-link text-white" href="index-e-learning.php">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-user me-1"></i><?php echo $_SESSION['name']; ?>
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
                    <div class="posttest-card">
                        <div class="posttest-header">
                            <div class="text-center">
                                <h1 class="display-5 fw-bold mb-3">
                                    <i class="fas fa-clipboard-check me-3"></i>Post-Test Modul <?php echo $module_info['number']; ?>
                                </h1>
                                <p class="lead mb-3"><?php echo $module_info['module_desc']; ?></p>
                                
                                <?php if ($last_score !== null) { ?>
                                <div class="mt-3">
                                    <span class="score-badge">
                                        <i class="fas fa-chart-line me-2"></i>Nilai Terakhir: <?php echo number_format($last_score, 0); ?>
                                    </span>
                                    <p class="mt-2 mb-0">Percobaan ke-<?php echo $attempt_count; ?> | Nilai Minimum: 70</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($last_score !== null && $last_score < 70) { ?>
            <!-- Warning Alert -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert-warning-custom">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Nilai Belum Mencukupi</h5>
                        <p class="mb-0">Anda perlu mendapatkan nilai minimal 70 untuk melanjutkan ke modul berikutnya. Silakan coba lagi!</p>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Quiz Form -->
            <form action="../action/save-post-test-e-learning.php" method="POST" id="posttestForm">
                <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
                <input type="hidden" name="attempt" value="<?php echo $attempt_count + 1; ?>">
                
                <!-- Progress Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="progress-section">
                            <h5 class="mb-2">
                                <i class="fas fa-tasks me-2"></i>Progress Post-Test
                            </h5>
                            <small id="progressText">0 dari 7 pertanyaan dijawab</small>
                        </div>
                    </div>
                </div>
                
                <!-- Questions -->
                <div class="row">
                    <div class="col-12">
                        <?php
                        $no = 1;
                        
                        // Ambil 7 soal random dari modul ini
                        $sql = "SELECT * FROM module_question WHERE module_id = '{$module_id}' ORDER BY RAND() LIMIT 7";
                        $query = mysqli_query($conn, $sql);
                        
                        while ($question = mysqli_fetch_assoc($query)) {
                        ?>
                            <div class="question-card" data-question="<?php echo $no; ?>">
                                <div class="d-flex align-items-start">
                                    <div class="question-number">
                                        <?php echo $no; ?>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="question-text">
                                            <?php echo $question['question']; ?>
                                        </div>
                                        
                                        <div class="answers-section">
                                            <?php
                                            $sql_choices = "SELECT * FROM module_question_choice WHERE question_id = '{$question['id']}'";
                                            $query_choices = mysqli_query($conn, $sql_choices);
                                            $choice_index = 0;
                                            
                                            while ($choice = mysqli_fetch_assoc($query_choices)) {
                                            ?>
                                                <label class="answer-option">
                                                    <input type="radio" 
                                                           name="question_<?php echo $question['id']; ?>" 
                                                           value="<?php echo $choice_index; ?>" 
                                                           onchange="selectAnswer(this); updateProgress();">
                                                    <span class="answer-text"><?php echo $choice['answer_desc']; ?></span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            <?php
                                                $choice_index++;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            $no++;
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Submit Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="submit-section">
                            <h3 class="mb-4">
                                <i class="fas fa-paper-plane me-2"></i>Selesaikan Post-Test
                            </h3>
                            <p class="lead mb-4">Pastikan semua pertanyaan telah dijawab sebelum mengirim</p>
                            
                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                                    <i class="fas fa-check me-2"></i>KIRIM JAWABAN
                                </button>
                                <a href="index-e-learning.php" class="btn btn-light btn-lg px-4">
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
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const totalQuestions = 7;
        let answeredQuestions = 0;
        
        function updateProgress() {
            answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
            
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
            const questionCard = radio.closest('.question-card');
            const allOptions = questionCard.querySelectorAll('.answer-option');
            allOptions.forEach(option => option.classList.remove('selected'));
            radio.closest('.answer-option').classList.add('selected');
        }
        
        // Form submission with confirmation
        document.getElementById('posttestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (answeredQuestions !== totalQuestions) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Post-Test Belum Lengkap',
                    text: `Masih ada ${totalQuestions - answeredQuestions} pertanyaan yang belum dijawab.`,
                    confirmButtonColor: '#FF8800'
                });
                return;
            }
            
            Swal.fire({
                title: 'Kirim Post-Test?',
                text: 'Pastikan semua jawaban sudah benar. Anda perlu nilai minimal 70 untuk lulus.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00C851',
                cancelButtonColor: '#FF8800',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Periksa Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim...',
                        text: 'Mohon tunggu',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    this.submit();
                }
            });
        });
        
        // Initialize progress on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
        });
    </script>
</body>

</html>
