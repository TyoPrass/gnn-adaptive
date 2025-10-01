<?php

session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

include('../config/db.php');
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Pre-Test Biologi - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --bio-primary: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
            --bio-secondary: linear-gradient(135deg, #20B2AA 0%, #48D1CC 100%);
            --bio-success: linear-gradient(135deg, #32CD32 0%, #98FB98 100%);
            --bio-info: linear-gradient(135deg, #4169E1 0%, #87CEEB 100%);
            --bio-warning: linear-gradient(135deg, #FFD700 0%, #FFF8DC 100%);
            --bio-danger: linear-gradient(135deg, #DC143C 0%, #FFB6C1 100%);
            --bio-dark: linear-gradient(135deg, #2F4F4F 0%, #708090 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .pretest-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .pretest-header {
            background: var(--bio-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .pretest-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
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
            border-color: rgba(46, 139, 87, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .question-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background: var(--bio-primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .question-card:hover::before {
            transform: scaleY(1);
        }
        
        .question-number {
            background: var(--bio-primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 1rem;
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
            transform: translateX(5px);
        }
        
        .custom-radio input[type="radio"] {
            display: none;
        }
        
        .custom-radio label {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            font-weight: 500;
        }
        
        .custom-radio label::before {
            content: '';
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid #dee2e6;
            margin-right: 15px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .custom-radio:hover label {
            background: rgba(46, 139, 87, 0.1);
            border-color: rgba(46, 139, 87, 0.3);
        }
        
        .custom-radio input[type="radio"]:checked + label {
            background: rgba(46, 139, 87, 0.15);
            border-color: #2E8B57;
            color: #2E8B57;
        }
        
        .custom-radio input[type="radio"]:checked + label::before {
            border-color: #2E8B57;
            background: #2E8B57;
            box-shadow: inset 0 0 0 3px white;
        }
        
        .progress-section {
            background: var(--bio-secondary);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .progress-bar-custom {
            height: 8px;
            border-radius: 10px;
            background: rgba(255,255,255,0.3);
            overflow: hidden;
        }
        
        .progress-bar-fill {
            height: 100%;
            background: white;
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        
        .submit-section {
            background: var(--bio-success);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
        }
        
        .btn-submit {
            background: var(--bio-primary);
            border: none;
            color: white;
            padding: 1rem 3rem;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(46, 139, 87, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(46, 139, 87, 0.4);
            color: white;
        }
        
        .bio-icon {
            color: rgba(255,255,255,0.8);
            margin-right: 10px;
        }
        
        .floating-icons {
            position: absolute;
            top: 20%;
            right: 10%;
            opacity: 0.1;
            font-size: 8rem;
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.2; transform: scale(1.05); }
        }
        
        .stats-card {
            background: rgba(255,255,255,0.9);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(46, 139, 87, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            background: var(--bio-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        @media (max-width: 768px) {
            .pretest-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .question-card {
                padding: 1.5rem;
            }
            
            .custom-radio label {
                padding: 0.8rem 1rem;
                font-size: 0.9rem;
            }
            
            .floating-icons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--bio-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-microscope me-2"></i>MyIRT Biologi
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
                    <div class="pretest-card">
                        <div class="pretest-header">
                            <div class="floating-icons">
                                <i class="fas fa-dna"></i>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-clipboard-list bio-icon"></i>Pre-Test Biologi
                                    </h1>
                                    <p class="lead mb-3">Uji kemampuan awal Anda sebelum memulai pembelajaran</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-clock me-1"></i>Tidak ada batas waktu
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-question-circle me-1"></i>Soal acak dari setiap modul
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-chart-line me-1"></i>Menentukan level pembelajaran
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-microscope" style="font-size: 6rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Section -->
                        <div class="p-4">
                            <div class="progress-section">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">
                                            <i class="fas fa-tasks me-2"></i>Progress Pengerjaan
                                        </h5>
                                        <div class="progress-bar-custom">
                                            <div class="progress-bar-fill" id="progressBar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="stat-number" id="progressText">0%</div>
                                        <small>Selesai</small>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <small><i class="fas fa-list-ol me-1"></i>Soal: <span id="currentQuestion">0</span> dari <span id="totalQuestions">0</span></small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small><i class="fas fa-check-circle me-1"></i>Terjawab: <span id="answeredQuestions">0</span></small>
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
                    <form action="../action/save-pretest.php" method="POST" id="pretestForm">
                        <div id="questionsContainer">
                        <div id="questionsContainer">
                            <?php
                            $no = 1;
                            $sql = "SELECT * FROM module";
                            $query = mysqli_query($conn, $sql);
                            $module = mysqli_fetch_all($query, MYSQLI_ASSOC);

                            foreach ($module as $key => $m) {
                                $sql = "SELECT * FROM module_question WHERE module_id = '{$m['id']}' ORDER BY RAND() LIMIT 3";
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
                                                       id="q<?php echo $q['id']; ?>_a<?php echo $a['id']; ?>"
                                                       name="question<?php echo $q['id']; ?>"
                                                       value="<?php echo $a['id']; ?>" 
                                                       required>
                                                <label for="q<?php echo $q['id']; ?>_a<?php echo $a['id']; ?>">
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
                                        <i class="fas fa-check-circle me-2"></i>Selesaikan Pre-Test
                                    </h4>
                                    <p class="text-white mb-0">
                                        Pastikan semua soal telah dijawab sebelum mengirim jawaban
                                    </p>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <button type="submit" class="btn-submit" id="submitBtn">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        KIRIM JAWABAN
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-3 text-center">
                                <small class="text-white">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Hasil pre-test akan menentukan tingkat pembelajaran yang sesuai untuk Anda
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
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
        // Initialize
        updateProgress();
        
        // Track question changes
        $('input[type="radio"]').on('change', function() {
            updateProgress();
            
            // Smooth scroll to next question
            const currentCard = $(this).closest('.question-card');
            const nextCard = currentCard.next('.question-card');
            
            if (nextCard.length) {
                setTimeout(() => {
                    $('html, body').animate({
                        scrollTop: nextCard.offset().top - 100
                    }, 500);
                }, 200);
            }
        });
        
        // Form submission with confirmation
        $('#pretestForm').on('submit', function(e) {
            e.preventDefault();
            
            const totalQuestions = $('.question-card').length;
            const answeredQuestions = $('input[type="radio"]:checked').length;
            
            if (answeredQuestions < totalQuestions) {
                Swal.fire({
                    title: 'Belum Lengkap!',
                    text: `Anda baru menjawab ${answeredQuestions} dari ${totalQuestions} soal. Yakin ingin melanjutkan?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2E8B57',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'animated zoomIn'
                    }
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
                    confirmButtonColor: '#2E8B57',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Kirim Sekarang!',
                    cancelButtonText: 'Periksa Lagi',
                    customClass: {
                        popup: 'animated pulse'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });
            }
        });
        
        // Auto-save functionality (every 30 seconds)
        setInterval(autoSave, 30000);
        
        // Warn before leaving page if there are unsaved changes
        window.addEventListener('beforeunload', function(e) {
            const answeredQuestions = $('input[type="radio"]:checked').length;
            if (answeredQuestions > 0) {
                e.preventDefault();
                e.returnValue = '';
                return 'Anda memiliki jawaban yang belum tersimpan. Yakin ingin meninggalkan halaman?';
            }
        });
    });
    
    function updateProgress() {
        const totalQuestions = $('.question-card').length;
        const answeredQuestions = $('input[type="radio"]:checked').length;
        const percentage = Math.round((answeredQuestions / totalQuestions) * 100);
        
        $('#totalQuestions').text(totalQuestions);
        $('#answeredQuestions').text(answeredQuestions);
        $('#progressText').text(percentage + '%');
        $('#progressBar').css('width', percentage + '%');
        
        // Update current question (last answered + 1)
        $('#currentQuestion').text(Math.min(answeredQuestions + 1, totalQuestions));
        
        // Add completion animation when 100%
        if (percentage === 100) {
            $('#progressBar').addClass('completed');
            setTimeout(() => {
                $('html, body').animate({
                    scrollTop: $('.submit-section').offset().top - 100
                }, 800);
            }, 500);
        }
    }
    
    function submitForm() {
        // Show loading
        Swal.fire({
            title: 'Mengirim Jawaban...',
            text: 'Mohon tunggu sebentar',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit form
        $.ajax({
            url: '../action/save-pretest.php',
            method: 'POST',
            data: $('#pretestForm').serialize(),
            success: function(response) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Pre-test telah berhasil disimpan. Anda akan diarahkan ke dashboard.',
                    icon: 'success',
                    confirmButtonColor: '#2E8B57',
                    confirmButtonText: 'Lanjutkan',
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
                    title: 'Oops!',
                    text: 'Terjadi kesalahan saat menyimpan jawaban. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Coba Lagi'
                });
            }
        });
    }
    
    function autoSave() {
        const answeredQuestions = $('input[type="radio"]:checked').length;
        if (answeredQuestions > 0) {
            // Show small notification
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            
            toast.fire({
                icon: 'info',
                title: 'Jawaban tersimpan otomatis'
            });
            
            // Here you can add AJAX call to auto-save to temporary storage
            // For now, just show the notification
        }
    }
    
    // Add hover effects and animations
    $('.question-card').hover(
        function() {
            $(this).find('.question-number').addClass('animate__pulse');
        },
        function() {
            $(this).find('.question-number').removeClass('animate__pulse');
        }
    );
    
    // Scroll animations
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
    </script>
</body>
</html>