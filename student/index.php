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

    <title>Dashboard Siswa - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- plugin css file  -->
    <link rel="stylesheet" href="../node_modules/owl.carousel2/dist/assets/owl.carousel.min.css" />
    <!-- project css file  -->
    <link rel="stylesheet" href="../assets/css/e-learn.style.min.css">
    <link href="../assets/css/style.css?v=<?php echo date("yymmdd") ?>" rel="stylesheet" />
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --info-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .welcome-card {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        
        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            transition: left 0.5s;
        }
        
        .feature-card:hover::before {
            left: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(102, 126, 234, 0.3);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .status-completed {
            background: var(--success-gradient);
            color: white;
        }
        
        .status-pending {
            background: var(--warning-gradient);
            color: white;
        }
        
        .status-not-taken {
            background: var(--secondary-gradient);
            color: white;
        }
        
        .progress-container {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            overflow: hidden;
            height: 8px;
            margin: 1rem 0;
        }
        
        .progress-bar-custom {
            height: 100%;
            background: var(--success-gradient);
            border-radius: 10px;
            transition: width 0.8s ease;
        }
        
        .stats-card {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .quick-action-btn {
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-adaptive {
            background: var(--primary-gradient);
            color: white;
        }
        
        .btn-elearning {
            background: var(--info-gradient);
            color: white;
        }
        
        .quick-action-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        @media (max-width: 768px) {
            .feature-card {
                margin-bottom: 1rem;
            }
            .stats-number {
                font-size: 1.5rem;
            }
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--primary-gradient); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#">
                <i class="fas fa-graduation-cap me-2"></i>MyIRT Learning
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-card dashboard-card p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-5 fw-bold mb-3">
                                    <i class="fas fa-hand-wave me-3"></i>Selamat Datang, <?php echo $_SESSION['name']; ?>!
                                </h1>
                                <p class="lead mb-3">Mulai perjalanan belajar adaptif Anda hari ini</p>
                                <div class="d-flex align-items-center">
                                    <span class="me-3">Progress Keseluruhan:</span>
                                    <div class="progress-container flex-grow-1">
                                        <?php
                                        $progress = 0;
                                        if ($_SESSION['survey_taken']) $progress += 25;
                                        if ($_SESSION['pre_test_taken']) $progress += 25;
                                        if ($_SESSION['test_processed']) $progress += 50;
                                        ?>
                                        <div class="progress-bar-custom" style="width: <?php echo $progress; ?>%"></div>
                                    </div>
                                    <span class="ms-3 fw-bold"><?php echo $progress; ?>%</span>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <img src="../assets/images/study.png" alt="Study" class="img-fluid" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card dashboard-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stats-number"><?php echo $_SESSION['test_processed'] ? $_SESSION['level'] : '?'; ?></div>
                                <small class="text-muted">Level Saat Ini</small>
                            </div>
                            <i class="fas fa-layer-group fa-2x" style="color: #667eea;"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card dashboard-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stats-number"><?php echo $_SESSION['survey_taken'] ? '✓' : '✗'; ?></div>
                                <small class="text-muted">Survey</small>
                            </div>
                            <i class="fas fa-poll-h fa-2x" style="color: #f093fb;"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card dashboard-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stats-number"><?php echo $_SESSION['pre_test_taken'] ? '✓' : '✗'; ?></div>
                                <small class="text-muted">Pre-test</small>
                            </div>
                            <i class="fas fa-clipboard-check fa-2x" style="color: #4facfe;"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card dashboard-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stats-number"><?php echo $_SESSION['test_processed'] ? '✓' : '✗'; ?></div>
                                <small class="text-muted">Tes Diproses</small>
                            </div>
                            <i class="fas fa-cogs fa-2x" style="color: #43e97b;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Learning Options -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="mb-4 fw-bold">
                        <i class="fas fa-rocket me-2" style="color: #667eea;"></i>Pilih Mode Pembelajaran
                    </h3>
                </div>
                
                <div class="col-lg-6 mb-3">
                    <div class="feature-card dashboard-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Adaptive Learning</h4>
                        <p class="text-muted mb-4">
                            Pembelajaran yang disesuaikan dengan kemampuan dan kecepatan belajar Anda. 
                            Sistem akan menyesuaikan tingkat kesulitan berdasarkan performa Anda.
                        </p>
                        
                        <div class="mb-3">
                            <?php if (!$_SESSION['survey_taken']) { ?>
                                <span class="status-badge status-pending">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Survey Belum Diambil
                                </span>
                            <?php } elseif (!$_SESSION['pre_test_taken']) { ?>
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock me-1"></i>Pre-test Belum Diambil
                                </span>
                            <?php } elseif (!$_SESSION['test_processed']) { ?>
                                <span class="status-badge status-pending">
                                    <i class="fas fa-hourglass-half me-1"></i>Tes Sedang Diproses
                                </span>
                            <?php } else { ?>
                                <span class="status-badge status-completed">
                                    <i class="fas fa-check-circle me-1"></i>Siap Digunakan
                                </span>
                            <?php } ?>
                        </div>
                        
                        <div class="d-grid">
                            <?php if ($_SESSION['survey_taken'] && $_SESSION['pre_test_taken'] && $_SESSION['test_processed']) { ?>
                                <a href="index-adaptive-learning.php" class="quick-action-btn btn-adaptive">
                                    <i class="fas fa-play me-2"></i>Mulai Adaptive Learning
                                </a>
                            <?php } elseif (!$_SESSION['survey_taken']) { ?>
                                <a href="survey.php" class="quick-action-btn btn-adaptive">
                                    <i class="fas fa-poll me-2"></i>Ambil Survey Dulu
                                </a>
                            <?php } elseif (!$_SESSION['pre_test_taken']) { ?>
                                <a href="pre-test.php" class="quick-action-btn btn-adaptive">
                                    <i class="fas fa-edit me-2"></i>Ambil Pre-test Dulu
                                </a>
                            <?php } else { ?>
                                <button class="quick-action-btn btn-adaptive" disabled>
                                    <i class="fas fa-hourglass-half me-2"></i>Menunggu Proses
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-3">
                    <div class="feature-card dashboard-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h4 class="fw-bold mb-3">E-Learning</h4>
                        <p class="text-muted mb-4">
                            Pembelajaran konvensional dengan materi yang telah disusun secara sistematis. 
                            Anda dapat belajar sesuai urutan yang telah ditentukan.
                        </p>
                        
                        <div class="mb-3">
                            <span class="status-badge status-completed">
                                <i class="fas fa-check-circle me-1"></i>Selalu Tersedia
                            </span>
                        </div>
                        
                        <div class="d-grid">
                            <a href="index-e-learning.php" class="quick-action-btn btn-elearning">
                                <i class="fas fa-play me-2"></i>Mulai E-Learning
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-bolt me-2" style="color: #667eea;"></i>Aksi Cepat
                        </h5>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <a href="topik.php" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-list me-2"></i>Lihat Topik
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <a href="modul.php" class="btn btn-outline-info w-100">
                                    <i class="fas fa-book me-2"></i>Modul Belajar
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <a href="quiz.php" class="btn btn-outline-success w-100">
                                    <i class="fas fa-question-circle me-2"></i>Quiz
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <a href="../sign-out.php" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Jquery Core Js -->
    <script src="../assets/bundles/libscripts.bundle.js"></script>
    <!-- Plugin Js-->
    <script src="../node_modules/owl.carousel2/dist/owl.carousel.min.js"></script>
    <script src="../assets/bundles/apexcharts.bundle.js"></script>
    <!-- Jquery Page Js -->
    <script src="../js/template.js"></script>
    
    <script>
        // Progress bar animation
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar-custom');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });

            // Add hover effects to cards
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add click effect to buttons
            const buttons = document.querySelectorAll('.quick-action-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.disabled) {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = 'scale(1.05)';
                        }, 100);
                    }
                });
            });

            // Add notification for status changes
            <?php if (!$_SESSION['survey_taken']) { ?>
                showNotification('Jangan lupa ambil survey untuk mengaktifkan Adaptive Learning!', 'info');
            <?php } elseif (!$_SESSION['pre_test_taken']) { ?>
                showNotification('Lanjutkan dengan Pre-test untuk melengkapi profil belajar Anda!', 'warning');
            <?php } elseif ($_SESSION['test_processed']) { ?>
                showNotification('Selamat! Semua fitur pembelajaran sudah tersedia untuk Anda!', 'success');
            <?php } ?>
        });

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible position-fixed`;
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                animation: slideIn 0.5s ease;
            `;
            
            notification.innerHTML = `
                <i class="fas fa-info-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Add some CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>