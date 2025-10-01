<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// set variable
$level_student = 0;

// mengambil data level student dari database
$query = mysqli_query($conn, "SELECT * FROM level_student WHERE student_id = '{$_SESSION['student_id']}'");
if (mysqli_num_rows($query) > 0) {
    // jika data ada set level student
    $result = mysqli_fetch_array($query);
    $level_student = $result['level'];
    $_SESSION['level'] = $result['level'];
    $_SESSION['test_processed'] = true;
} else {
    // jika tidak ada maka tes belum diproses
    $_SESSION['test_processed'] = false;
}

// mengambil data hasil survey murid
$survey = mysqli_query($conn, "SELECT * FROM survey_result where student_id = '{$_SESSION['student_id']}'");
$survey_row = mysqli_num_rows($survey);
// jika ada hasil survey
if ($survey_row == 1) {
    // set hasil survey level
    $_SESSION['survey_taken'] = true;
    $level = mysqli_fetch_array($survey, MYSQLI_ASSOC);
    $_SESSION['levels'] = $survey;
} else {
    $_SESSION['survey_taken'] = false;
}

// mengambil data jawaban pre test untuk mengecek apakah murid sudah mengambil pretest
$sql = "SELECT * FROM pre_test_answer WHERE student_id = '{$_SESSION['student_id']}'";
$query = mysqli_query($conn, $sql);
if (mysqli_num_rows($query) > 0) {
    $_SESSION['pre_test_taken'] = true;
} else {
    $_SESSION['pre_test_taken'] = false;
}


?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard Adaptive Learning - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --dashboard-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --dashboard-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --dashboard-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --dashboard-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --dashboard-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --dashboard-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .dashboard-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .welcome-header {
            background: var(--dashboard-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: welcomeFloat 12s ease-in-out infinite;
        }
        
        @keyframes welcomeFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-10deg); }
        }
        
        .status-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border-left: 5px solid #00C851;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .status-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .status-card.warning {
            border-left-color: #FF8800;
            background: linear-gradient(135deg, #fff8f0 0%, #ffffff 100%);
        }
        
        .status-card.success {
            border-left-color: #00C851;
            background: linear-gradient(135deg, #f0fff0 0%, #ffffff 100%);
        }
        
        .status-card.info {
            border-left-color: #33b5e5;
            background: linear-gradient(135deg, #f0f8ff 0%, #ffffff 100%);
        }
        
        .level-badge {
            background: var(--dashboard-primary);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.2rem;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(0,200,81,0.3);
        }
        
        .action-button {
            background: var(--dashboard-secondary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255,136,0,0.3);
            position: relative;
            overflow: hidden;
        }
        
        .action-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255,136,0,0.4);
            color: white;
            text-decoration: none;
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        
        .action-button:hover::before {
            left: 100%;
        }
        
        .start-learning-btn {
            background: var(--dashboard-success);
            color: white;
            border: none;
            padding: 1.5rem 4rem;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.4rem;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,187,45,0.3);
            position: relative;
            overflow: hidden;
        }
        
        .start-learning-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,187,45,0.4);
            color: white;
            text-decoration: none;
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
        
        .progress-indicator {
            background: var(--dashboard-info);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .alert-success-custom {
            background: var(--dashboard-success);
            color: white;
        }
        
        .student-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--dashboard-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 30px rgba(0,200,81,0.3);
        }
        
        @media (max-width: 768px) {
            .welcome-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .status-card {
                padding: 1.5rem;
            }
            
            .floating-icons {
                display: none;
            }
            
            .start-learning-btn {
                padding: 1.2rem 2rem;
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--dashboard-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
            <!-- Welcome Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="welcome-header">
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
                                        Selamat Datang, <?php echo $_SESSION['name']; ?>!
                                    </h1>
                                    <p class="lead mb-3">Mulai perjalanan pembelajaran biologi adaptif yang disesuaikan dengan kemampuan Anda</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-brain me-1"></i>Adaptive Learning
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-chart-line me-1"></i>Progress Tracking
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-trophy me-1"></i>Achievement System
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="student-avatar">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Alert -->
            <?php if (isset($_SESSION['quiz_finish']) && $_SESSION['quiz_finish'] == true) { ?>
                <div class="alert alert-success-custom alert-custom" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Selamat!</strong> Anda berhasil menyelesaikan quiz dengan baik.
                    <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="alert"></button>
                </div>
            <?php }
            $_SESSION['quiz_finish'] = false; ?>

            <!-- Content Section -->
            <div class="row">
                <div class="col-12">
                    <?php if ($_SESSION['level_user'] == 3) { ?>
                        <!-- Level Assessment Results -->
                        <div class="status-card success">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-clipboard-check fa-3x text-success me-3"></i>
                                <div>
                                    <h4 class="mb-1 text-success">Hasil Penilaian Level Pembelajaran</h4>
                                    <p class="mb-0 text-muted">Status kemajuan pembelajaran Anda</p>
                                </div>
                            </div>
                            
                            <?php if ($_SESSION['test_processed']) { ?>
                                <div class="text-center py-3">
                                    <h5 class="mb-3">Level pembelajaran Anda adalah:</h5>
                                    <div class="level-badge">
                                        <i class="fas fa-graduation-cap me-2"></i>Level <?php echo $level_student ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <!-- Pre-test Status -->
                                <?php if ($_SESSION['pre_test_taken']) { ?>
                                    <div class="progress-indicator">
                                        <i class="fas fa-hourglass-half fa-2x mb-3"></i>
                                        <h5 class="mb-2">Pre-Test Sedang Diproses</h5>
                                        <p class="mb-0">Hasil pre-test Anda sedang dianalisis oleh sistem</p>
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-warning" style="border-radius: 15px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clipboard-question fa-2x me-3"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-2">Pre-Test Diperlukan</h6>
                                                <p class="mb-3">Ambil pre-test untuk menentukan level pembelajaran yang sesuai</p>
                                                <a href="pre-test.php" class="action-button">
                                                    <i class="fas fa-play me-2"></i>Mulai Pre-Test
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                                <!-- Survey Status -->
                                <?php if ($_SESSION['survey_taken']) { ?>
                                    <div class="progress-indicator">
                                        <i class="fas fa-poll fa-2x mb-3"></i>
                                        <h5 class="mb-2">Survey Sedang Diproses</h5>
                                        <p class="mb-0">Hasil survey gaya belajar Anda sedang dianalisis</p>
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-info" style="border-radius: 15px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-poll-h fa-2x me-3"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-2">Survey Gaya Belajar</h6>
                                                <p class="mb-3">Ikuti survey untuk personalisasi pembelajaran</p>
                                                <a href="survey.php" class="action-button">
                                                    <i class="fas fa-clipboard-list me-2"></i>Ikuti Survey
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <!-- Start Learning Section -->
                    <div class="text-center py-5">
                        <h3 class="mb-4">Siap Memulai Pembelajaran?</h3>
                        <p class="lead text-muted mb-4">Jelajahi modul-modul biologi yang telah disesuaikan dengan kemampuan Anda</p>
                        <a href="modul.php" class="start-learning-btn">
                            <i class="fas fa-rocket me-3"></i>MULAI BELAJAR
                        </a>
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
        // Initialize dashboard interactions
        initializeDashboard();
        
        // Button hover effects
        $('.action-button, .start-learning-btn').hover(
            function() {
                $(this).find('i').addClass('fa-bounce');
            },
            function() {
                $(this).find('i').removeClass('fa-bounce');
            }
        );
        
        // Status card animations
        $('.status-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.2) + 's');
            $(this).addClass('animate-in');
        });
        
        // Welcome animation
        setTimeout(function() {
            $('.welcome-header h1').addClass('animate__animated animate__fadeInUp');
        }, 500);
        
        // Start learning button click
        $('.start-learning-btn').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            // Show loading animation
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
            
            toast.fire({
                icon: 'success',
                title: 'Memuat modul pembelajaran...',
                background: '#00C851',
                color: 'white'
            });
            
            setTimeout(() => {
                window.location.href = href;
            }, 1000);
        });
        
        // Action button confirmations
        $('.action-button[href*="pre-test"]').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            Swal.fire({
                title: 'Mulai Pre-Test?',
                text: 'Pre-test akan menentukan level pembelajaran yang sesuai untuk Anda.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF8800',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Mulai!',
                cancelButtonText: 'Nanti Saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
        
        $('.action-button[href*="survey"]').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            Swal.fire({
                title: 'Ikuti Survey?',
                text: 'Survey akan membantu sistem memahami gaya belajar Anda.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#33b5e5',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ikuti!',
                cancelButtonText: 'Nanti Saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
    
    function initializeDashboard() {
        // Animate level badge
        $('.level-badge').each(function() {
            $(this).on('mouseenter', function() {
                $(this).addClass('animate__animated animate__pulse');
            }).on('mouseleave', function() {
                $(this).removeClass('animate__animated animate__pulse');
            });
        });
        
        // Progress indicators animation
        $('.progress-indicator').each(function() {
            const $indicator = $(this);
            setInterval(() => {
                $indicator.find('i').toggleClass('fa-spin');
            }, 2000);
        });
        
        // Add tooltip to badges
        $('[data-bs-toggle="tooltip"]').tooltip();
    }
    
    // Add CSS for animations
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
        
        .status-card {
            opacity: 0;
        }
        
        .level-badge {
            animation: levelPulse 3s ease-in-out infinite;
        }
        
        @keyframes levelPulse {
            0%, 100% { box-shadow: 0 5px 15px rgba(0,200,81,0.3); }
            50% { box-shadow: 0 8px 25px rgba(0,200,81,0.5); }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>

</html>