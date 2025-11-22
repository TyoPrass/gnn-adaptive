<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard Guru - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --guru-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --guru-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --guru-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --guru-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --guru-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --guru-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .guru-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .guru-header {
            background: var(--guru-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .guru-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: guruFloat 12s ease-in-out infinite;
        }
        
        @keyframes guruFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-10deg); }
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-left: 5px solid #00C851;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }
        
        .stat-icon.students {
            background: var(--guru-info);
        }
        
        .stat-icon.classes {
            background: var(--guru-warning);
        }
        
        .stat-icon.materials {
            background: var(--guru-success);
        }
        
        .stat-icon.tests {
            background: var(--guru-secondary);
        }
        
        .quick-action-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }
        
        .quick-action-card:hover {
            border-color: rgba(0, 200, 81, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            text-decoration: none;
        }
        
        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            margin-bottom: 1rem;
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
        
        .guru-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--guru-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 30px rgba(0,200,81,0.3);
        }
        
        .level-badge {
            background: var(--guru-warning);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9rem;
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .guru-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .stats-card {
                padding: 1.5rem;
            }
            
            .floating-icons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--guru-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-leaf me-2"></i>MyIRT Adaptive Learning
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    
                    <?php if ($_SESSION['level_user'] == 1 || $_SESSION['level_user'] == 2) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="murid.php">
                                <i class="fas fa-user-graduate me-1"></i>Murid
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="topik.php">
                                <i class="fas fa-book me-1"></i>Materi
                            </a>
                        </li>
                    <?php } ?>
                    
                    <?php if ($_SESSION['level_user'] == 1) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="kelas.php">
                                <i class="fas fa-chalkboard me-1"></i>Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="guru.php">
                                <i class="fas fa-users me-1"></i>Guru
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-chart-line me-1"></i>Level Murid
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="pre-test.php">Hitung Level</a></li>
                                <li><a class="dropdown-item" href="hasil-pre-test.php">Hasil Level</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    
                    <?php if ($_SESSION['level_user'] == 2) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="hasil-pre-test.php">
                                <i class="fas fa-chart-bar me-1"></i>Hasil Level
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo $_SESSION['name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?php echo $_SESSION['login']; ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../sign-out.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
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
                    <div class="guru-card">
                        <div class="guru-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-chalkboard-teacher"></i>
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
                                        Selamat Datang, <?php echo $_SESSION['name']; ?>!
                                    </h1>
                                    <p class="lead mb-3">Dashboard Guru - Kelola pembelajaran biologi adaptif dengan mudah</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="level-badge">
                                            <i class="fas fa-user-tag me-1"></i>
                                            <?php 
                                            if ($_SESSION['level_user'] == 1) echo 'Administrator';
                                            elseif ($_SESSION['level_user'] == 2) echo 'Guru';
                                            else echo 'User';
                                            ?>
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-calendar me-1"></i><?php echo date('d F Y'); ?>
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-clock me-1"></i><?php echo date('H:i'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="guru-avatar">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="row mb-4">
                <?php if ($_SESSION['level_user'] == 1 || $_SESSION['level_user'] == 2) { ?>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stat-icon students">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h4 class="mb-2">
                                <?php
                                $students_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM student");
                                $students_count = mysqli_fetch_array($students_query)['total'];
                                echo $students_count;
                                ?>
                            </h4>
                            <p class="text-muted mb-0">Total Murid</p>
                        </div>
                    </div>
                    
                    <?php if ($_SESSION['level_user'] == 2) { ?>
                    <!-- ✅ CARD BARU: Murid yang Diampu Guru -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%);">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="mb-2">
                                <?php
                                // Ambil class_id yang diampu guru
                                $teacher_id = $_SESSION['teacher_id'];
                                $class_query = mysqli_query($conn, "SELECT DISTINCT c.id, c.class_name 
                                                                    FROM class_attendance ca 
                                                                    JOIN class c ON ca.class_id = c.id 
                                                                    WHERE ca.teacher_id = '{$teacher_id}' 
                                                                    ORDER BY c.class_name ASC");
                                
                                $class_names = array();
                                $class_ids = array();
                                
                                if (mysqli_num_rows($class_query) > 0) {
                                    while ($class_row = mysqli_fetch_assoc($class_query)) {
                                        $class_ids[] = $class_row['id'];
                                        $class_names[] = $class_row['class_name'];
                                    }
                                    
                                    if (!empty($class_ids)) {
                                        $class_id_list = implode(',', $class_ids);
                                        $my_students_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM student WHERE class_id IN ({$class_id_list})");
                                        $my_students_count = mysqli_fetch_array($my_students_query)['total'];
                                        echo $my_students_count;
                                    } else {
                                        echo "0";
                                    }
                                } else {
                                    echo "0";
                                }
                                ?>
                            </h4>
                            <p class="text-muted mb-0">Murid yang Anda Ampu</p>
                            <?php if (!empty($class_names)) { ?>
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">
                                    <i class="fas fa-chalkboard me-1"></i>Kelas:
                                </small>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($class_names as $class_name) { ?>
                                    <span class="badge bg-primary" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($class_name); ?>
                                    </span>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="mt-2">
                                <span class="badge bg-secondary" style="font-size: 0.75rem;">
                                    <i class="fas fa-info-circle me-1"></i>Belum mengampu kelas
                                </span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <!-- ✅ CARD BARU: Kelas yang Diampu -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stat-icon classes">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <h4 class="mb-2">
                                <?php
                                $teacher_id = $_SESSION['teacher_id'];
                                $my_classes_query = mysqli_query($conn, "SELECT COUNT(DISTINCT class_id) as total FROM class_attendance WHERE teacher_id = '{$teacher_id}'");
                                $my_classes_count = mysqli_fetch_array($my_classes_query)['total'];
                                echo $my_classes_count;
                                ?>
                            </h4>
                            <p class="text-muted mb-0">Kelas yang Anda Ampu</p>
                            <?php if (!empty($class_names) && !empty($class_ids)) { ?>
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">
                                    <i class="fas fa-info-circle me-1"></i>Detail:
                                </small>
                                <div class="d-flex flex-column gap-1">
                                    <?php 
                                    foreach ($class_ids as $index => $class_id) { 
                                        // Hitung jumlah murid per kelas
                                        $students_per_class = mysqli_query($conn, "SELECT COUNT(*) as total FROM student WHERE class_id = '{$class_id}'");
                                        $students_count = mysqli_fetch_array($students_per_class)['total'];
                                    ?>
                                    <small class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success" style="font-size: 0.7rem; min-width: 60px;">
                                            <?php echo htmlspecialchars($class_names[$index]); ?>
                                        </span>
                                        <span class="text-muted" style="font-size: 0.7rem;">
                                            <i class="fas fa-user-friends me-1"></i><?php echo $students_count; ?> murid
                                        </span>
                                    </small>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="mt-2">
                                <span class="badge bg-secondary" style="font-size: 0.75rem;">
                                    <i class="fas fa-info-circle me-1"></i>Belum mengampu kelas
                                </span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stat-icon materials">
                                <i class="fas fa-book"></i>
                            </div>
                            <h4 class="mb-2">
                                <?php
                                $materials_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM module");
                                $materials_count = mysqli_fetch_array($materials_query)['total'];
                                echo $materials_count;
                                ?>
                            </h4>
                            <p class="text-muted mb-0">Materi Tersedia</p>
                        </div>
                    </div>
                <?php } ?>
                
                <?php if ($_SESSION['level_user'] == 1) { ?>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stat-icon classes">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <h4 class="mb-2">
                                <?php
                                $classes_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM class");
                                $classes_count = mysqli_fetch_array($classes_query)['total'];
                                echo $classes_count;
                                ?>
                            </h4>
                            <p class="text-muted mb-0">Total Kelas</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stat-icon tests">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h4 class="mb-2">
                                <?php
                                $tests_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM pre_test_answer");
                                $tests_count = mysqli_fetch_array($tests_query)['total'];
                                echo $tests_count;
                                ?>
                            </h4>
                            <p class="text-muted mb-0">Pre-Test Selesai</p>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-4">Aksi Cepat</h3>
                </div>
                
                <?php if ($_SESSION['level_user'] == 1 || $_SESSION['level_user'] == 2) { ?>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="murid.php" class="quick-action-card">
                            <div class="action-icon" style="background: var(--guru-info);">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h5 class="mb-2">Kelola Murid</h5>
                            <p class="text-muted mb-0">Lihat dan kelola data siswa</p>
                        </a>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="topik.php" class="quick-action-card">
                            <div class="action-icon" style="background: var(--guru-success);">
                                <i class="fas fa-book"></i>
                            </div>
                            <h5 class="mb-2">Kelola Materi</h5>
                            <p class="text-muted mb-0">Tambah dan edit materi pembelajaran</p>
                        </a>
                    </div>
                <?php } ?>
                
                <?php if ($_SESSION['level_user'] == 1) { ?>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="kelas.php" class="quick-action-card">
                            <div class="action-icon" style="background: var(--guru-warning);">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <h5 class="mb-2">Kelola Kelas</h5>
                            <p class="text-muted mb-0">Atur kelas dan pembagian siswa</p>
                        </a>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="guru.php" class="quick-action-card">
                            <div class="action-icon" style="background: var(--guru-primary);">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="mb-2">Kelola Guru</h5>
                            <p class="text-muted mb-0">Manajemen data guru dan akses</p>
                        </a>
                    </div>
                <?php } ?>
                
                <!-- Card untuk Kelola Survey -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <a href="survey.php" class="quick-action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-poll"></i>
                        </div>
                        <h5 class="mb-2">Kelola Survey</h5>
                        <p class="text-muted mb-0">Kelola pertanyaan survey motivasi siswa</p>
                    </a>
                </div>
                
                <!-- Card untuk Kelola Soal -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <a href="soal-management.php" class="quick-action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h5 class="mb-2">Kelola Soal</h5>
                        <p class="text-muted mb-0">Manajemen soal dan pilihan jawaban</p>
                    </a>
                </div>
                
                <!-- Card untuk Hitung Pre-test -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <a href="pre-test.php" class="quick-action-card">
                        <div class="action-icon" style="background: var(--guru-secondary);">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h5 class="mb-2">Hitung Pre-Test</h5>
                        <p class="text-muted mb-0">Proses perhitungan level siswa per kelas</p>
                    </a>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-3">
                    <a href="hasil-pre-test.php" class="quick-action-card">
                        <div class="action-icon" style="background: var(--guru-info);">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5 class="mb-2">Hasil Level Siswa</h5>
                        <p class="text-muted mb-0">Lihat hasil dan analisis level</p>
                    </a>
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
        // Initialize guru dashboard
        initializeGuruDashboard();
        
        // Stats card animations
        $('.stats-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
            $(this).addClass('animate-in');
        });
        
        // Quick action card hover effects
        $('.quick-action-card').hover(
            function() {
                $(this).find('.action-icon i').addClass('fa-bounce');
            },
            function() {
                $(this).find('.action-icon i').removeClass('fa-bounce');
            }
        );
        
        // Level badge animation
        $('.level-badge').hover(
            function() {
                $(this).addClass('animate__pulse');
            },
            function() {
                $(this).removeClass('animate__pulse');
            }
        );
        
        // Welcome animation
        setTimeout(function() {
            $('.guru-header h1').addClass('animate__animated animate__fadeInUp');
        }, 500);
        
        // Quick action confirmations
        $('.quick-action-card').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const title = $(this).find('h5').text();
            
            // Show loading animation
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500
            });
            
            toast.fire({
                icon: 'info',
                title: `Membuka ${title}...`,
                background: '#00C851',
                color: 'white'
            });
            
            setTimeout(() => {
                window.location.href = href;
            }, 800);
        });
        
        // Real-time clock update
        updateClock();
        setInterval(updateClock, 1000);
    });
    
    function initializeGuruDashboard() {
        // Animate stats numbers
        $('.stats-card h4').each(function() {
            const $this = $(this);
            const target = parseInt($this.text());
            let current = 0;
            
            if (target > 0) {
                const increment = target / 30;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    $this.text(Math.floor(current));
                }, 50);
            }
        });
        
        // Add tooltips to action cards
        $('.quick-action-card').each(function() {
            const description = $(this).find('p').text();
            $(this).attr('title', description);
        });
        
        // Initialize popovers for level info
        $('[data-bs-toggle="popover"]').popover();
    }
    
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        
        $('.badge:contains("<?php echo date('H:i'); ?>")').
            html('<i class="fas fa-clock me-1"></i>' + timeString);
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
        
        .stats-card {
            opacity: 0;
        }
        
        .guru-avatar {
            animation: avatarPulse 3s ease-in-out infinite;
        }
        
        @keyframes avatarPulse {
            0%, 100% { box-shadow: 0 10px 30px rgba(0,200,81,0.3); }
            50% { box-shadow: 0 15px 40px rgba(0,200,81,0.5); }
        }
        
        .quick-action-card {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>

</html>