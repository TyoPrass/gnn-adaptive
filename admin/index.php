<?php

include('../config/db.php');
session_start();

// jika belum login redirect ke login
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

    <title>Admin Dashboard - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5.2.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.3.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --biology-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --warm-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --ocean-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            --royal-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --nature-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            --admin-gradient: linear-gradient(135deg, #667eea 0%, #43e97b 100%);
            --dashboard-gradient: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .main-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            margin: 20px;
            padding: 0;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }
        
        .page-header {
            background: var(--admin-gradient);
            color: white;
            padding: 2rem;
            margin: 0;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .page-header .subtitle {
            margin-top: 0.5rem;
            opacity: 0.9;
            font-size: 1.2rem;
        }
        
        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--biology-gradient);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }
        
        .stat-card.students::before {
            background: var(--biology-gradient);
        }
        
        .stat-card.teachers::before {
            background: var(--warm-gradient);
        }
        
        .stat-card.classes::before {
            background: var(--ocean-gradient);
        }
        
        .stat-card.materials::before {
            background: var(--nature-gradient);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin: 2rem;
        }
        
        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .action-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .action-btn.success {
            background: var(--biology-gradient);
        }
        
        .action-btn.success:hover {
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.4);
        }
        
        .action-btn.warning {
            background: var(--warm-gradient);
        }
        
        .action-btn.warning:hover {
            box-shadow: 0 8px 25px rgba(250, 112, 154, 0.4);
        }
        
        .action-btn.info {
            background: var(--ocean-gradient);
        }
        
        .action-btn.info:hover {
            box-shadow: 0 8px 25px rgba(168, 237, 234, 0.4);
        }
        
        /* Navbar styling */
        .navbar {
            margin-bottom: 0;
        }
        
        .navbar-nav .nav-link.active {
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            font-weight: 600;
        }
        
        .navbar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        
        .dropdown-item:hover {
            background: var(--biology-gradient);
            color: white;
        }
        
        .recent-activity {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            margin-right: 1rem;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: #666;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .welcome-card,
        .stat-card,
        .chart-card,
        .quick-actions,
        .recent-activity {
            animation: fadeInUp 0.6s ease;
        }
        
        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }
        
        .stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }
        
        .stat-card:nth-child(4) {
            animation-delay: 0.3s;
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .main-content {
                margin: 10px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .charts-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php
    // Level user handling untuk akses kontrol - Integrated from sidebar.php
    if (!isset($_SESSION['level_user'])) {
        header('location: ../sign-in.php');
        exit();
    }
    
    $level_user = $_SESSION['level_user'];
    $user_name = $_SESSION['name'] ?? 'Guest';
    $user_login = $_SESSION['login'] ?? 'User';
    
    // Get statistics from database
    $stats = [
        'students' => 0,
        'teachers' => 0,
        'classes' => 0,
        'materials' => 0
    ];
    
    // Count students
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM student");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $stats['students'] = $row['count'];
    }
    
    // Count teachers  
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM teachers");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $stats['teachers'] = $row['count'];
    }
    
    // Count classes
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM class");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $stats['classes'] = $row['count'];
    }
    
    // Count materials
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM topic");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $stats['materials'] = $row['count'];
    }
    ?>
    
    <!-- Navigation Bar - Integrated from header.php -->
    <nav class="navbar navbar-expand-lg" style="background: var(--biology-gradient); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
                        <a class="nav-link text-white active" href="index.php">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    
                    <?php if ($level_user == 1 || $level_user == 2) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="murid.php">
                                <i class="fas fa-user-graduate me-1"></i>Murid
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="topik.php">
                                <i class="fas fa-book-open me-1"></i>Materi
                            </a>
                        </li>
                    <?php } ?>
                    
                    <?php if ($level_user == 1) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="kelas.php">
                                <i class="fas fa-school me-1"></i>Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="guru.php">
                                <i class="fas fa-chalkboard-teacher me-1"></i>Guru
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cogs me-1"></i>Pengaturan
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="pre-test.php"><i class="fas fa-tasks me-2"></i>Pre-Test</a></li>
                                <li><a class="dropdown-item" href="modul.php"><i class="fas fa-book me-2"></i>Modul</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hasil-pre-test.php">
                            <i class="fas fa-chart-bar me-1"></i>Hasil Level
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user_name); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?php echo htmlspecialchars($user_login); ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../sign-out.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="main-content">
        <!-- Modern Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-tachometer-alt"></i>
                Admin Dashboard
            </h1>
            <div class="subtitle">
                <i class="fas fa-calendar me-2"></i>
                <?php echo date('l, d F Y'); ?>
                <span class="badge bg-light text-dark ms-3">
                    <i class="fas fa-shield-alt me-1"></i>
                    Level: <?php echo $level_user == 1 ? 'Administrator' : ($level_user == 2 ? 'Guru' : 'User'); ?>
                </span>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-3">
                        <span class="text-primary">Selamat Datang</span>, 
                        <span class="fw-bold"><?php echo htmlspecialchars($user_name); ?>!</span>
                    </h2>
                    <p class="text-muted mb-0">
                        Anda login sebagai <strong><?php echo htmlspecialchars($user_login); ?></strong> dengan akses level 
                        <span class="badge bg-primary">
                            <?php echo $level_user == 1 ? 'Administrator' : ($level_user == 2 ? 'Guru' : 'User'); ?>
                        </span>
                    </p>
                    <p class="text-muted small mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Gunakan dashboard ini untuk mengelola sistem pembelajaran adaptif MyIRT.
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="../assets/images/study.svg" alt="Welcome" class="img-fluid" style="max-height: 150px;">
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card students">
                <div class="stat-icon" style="background: var(--biology-gradient);">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-number" id="studentsCount"><?php echo $stats['students']; ?></div>
                <div class="stat-label">Total Murid</div>
            </div>
            
            <?php if ($level_user == 1) { ?>
            <div class="stat-card teachers">
                <div class="stat-icon" style="background: var(--warm-gradient);">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-number" id="teachersCount"><?php echo $stats['teachers']; ?></div>
                <div class="stat-label">Total Guru</div>
            </div>
            
            <div class="stat-card classes">
                <div class="stat-icon" style="background: var(--ocean-gradient);">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-number" id="classesCount"><?php echo $stats['classes']; ?></div>
                <div class="stat-label">Total Kelas</div>
            </div>
            <?php } ?>
            
            <div class="stat-card materials">
                <div class="stat-icon" style="background: var(--nature-gradient);">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="stat-number" id="materialsCount"><?php echo $stats['materials']; ?></div>
                <div class="stat-label">Total Materi</div>
            </div>
        </div>

        <!-- Charts Section
        <div class="charts-container">
            <div class="chart-card">
                <h3 class="chart-title">
                    <i class="fas fa-chart-pie text-primary"></i>
                    Distribusi Level Pembelajaran
                </h3>
                <canvas id="levelChart" width="400" height="300"></canvas>
            </div> -->
            
            <!-- <div class="chart-card">
                <h3 class="chart-title">
                    <i class="fas fa-chart-line text-success"></i>
                    Progres Pembelajaran Mingguan
                </h3>
                <canvas id="progressChart" width="400" height="300"></canvas>
            </div> -->
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3 class="mb-3">
                <i class="fas fa-bolt text-warning me-2"></i>
                Aksi Cepat
            </h3>
            <div class="d-flex flex-wrap">
                <?php if ($level_user == 1 || $level_user == 2) { ?>
                    <a href="murid.php" class="action-btn success">
                        <i class="fas fa-user-graduate"></i>
                        Kelola Murid
                    </a>
                    <a href="topik.php" class="action-btn info">
                        <i class="fas fa-book-open"></i>
                        Kelola Materi
                    </a>
                <?php } ?>
                
                <?php if ($level_user == 1) { ?>
                    <a href="guru.php" class="action-btn warning">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Kelola Guru
                    </a>
                    <a href="kelas.php" class="action-btn">
                        <i class="fas fa-school"></i>
                        Kelola Kelas
                    </a>
                    <a href="pre-test.php" class="action-btn success">
                        <i class="fas fa-tasks"></i>
                        Hitung Level
                    </a>
                <?php } ?>
                
                <a href="hasil-pre-test.php" class="action-btn info">
                    <i class="fas fa-chart-bar"></i>
                    Lihat Hasil Level
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h3 class="mb-3">
                <i class="fas fa-clock text-info me-2"></i>
                Aktivitas Terbaru
            </h3>
            <div id="recentActivities">
                <div class="activity-item">
                    <div class="activity-icon" style="background: var(--biology-gradient);">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Sistem berhasil dimuat</div>
                        <div class="activity-time"><?php echo date('H:i'); ?> - Hari ini</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background: var(--warm-gradient);">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title"><?php echo htmlspecialchars($user_name); ?> berhasil login</div>
                        <div class="activity-time"><?php echo date('H:i'); ?> - Hari ini</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background: var(--ocean-gradient);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Dashboard berhasil dimuat</div>
                        <div class="activity-time"><?php echo date('H:i'); ?> - Hari ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    var levelChart, progressChart;
    var levelUser = <?php echo json_encode($level_user); ?>;
    
    $(document).ready(function() {
        // Initialize charts
        initializeLevelChart();
        initializeProgressChart();
        
        // Update statistics every 30 seconds
        setInterval(updateStatistics, 30000);
        
        // Add hover effects to stat cards
        $('.stat-card').hover(
            function() {
                $(this).find('.stat-number').css('transform', 'scale(1.1)');
            },
            function() {
                $(this).find('.stat-number').css('transform', 'scale(1)');
            }
        );
        
        // Add click animation to action buttons
        $('.action-btn').on('click', function(e) {
            const ripple = $('<span class="ripple"></span>');
            $(this).append(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
        
        // Show welcome notification
        setTimeout(() => {
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            
            toast.fire({
                icon: 'success',
                title: 'Selamat datang di Dashboard Admin!',
                background: '#43e97b',
                color: 'white'
            });
        }, 1000);
    });
    
    function initializeLevelChart() {
        const ctx = document.getElementById('levelChart').getContext('2d');
        
        // Sample data - replace with actual data from database
        levelChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Level Tinggi', 'Level Sedang', 'Level Rendah'],
                datasets: [{
                    data: [30, 45, 25],
                    backgroundColor: [
                        '#43e97b',
                        '#fee140', 
                        '#f5576c'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
    
    function initializeProgressChart() {
        const ctx = document.getElementById('progressChart').getContext('2d');
        
        // Sample data - replace with actual data from database
        progressChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Murid Aktif',
                    data: [12, 19, 15, 25, 22, 18, 24],
                    borderColor: '#43e97b',
                    backgroundColor: 'rgba(67, 233, 123, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Materi Diselesaikan',
                    data: [8, 12, 10, 15, 14, 11, 16],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    function updateStatistics() {
        $.ajax({
            url: '../data/dashboard-stats.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Animate number changes
                animateNumber($('#studentsCount'), data.students || <?php echo $stats['students']; ?>);
                
                if (levelUser == 1) {
                    animateNumber($('#teachersCount'), data.teachers || <?php echo $stats['teachers']; ?>);
                    animateNumber($('#classesCount'), data.classes || <?php echo $stats['classes']; ?>);
                }
                
                animateNumber($('#materialsCount'), data.materials || <?php echo $stats['materials']; ?>);
                
                // Update charts if new data available
                if (data.levelData) {
                    updateLevelChart(data.levelData);
                }
                
                if (data.progressData) {
                    updateProgressChart(data.progressData);
                }
            },
            error: function() {
                console.log('Failed to update statistics');
            }
        });
    }
    
    function animateNumber(element, targetNumber) {
        const currentNumber = parseInt(element.text()) || 0;
        const increment = targetNumber > currentNumber ? 1 : -1;
        const stepTime = Math.abs(Math.floor(300 / (targetNumber - currentNumber)));
        
        if (currentNumber !== targetNumber) {
            let current = currentNumber;
            const timer = setInterval(() => {
                current += increment;
                element.text(current);
                
                if (current === targetNumber) {
                    clearInterval(timer);
                }
            }, stepTime);
        }
    }
    
    function updateLevelChart(data) {
        if (levelChart && data) {
            levelChart.data.datasets[0].data = data;
            levelChart.update('animate');
        }
    }
    
    function updateProgressChart(data) {
        if (progressChart && data) {
            progressChart.data.datasets[0].data = data.active;
            progressChart.data.datasets[1].data = data.completed;
            progressChart.update('animate');
        }
    }
    
    // Add CSS for ripple effect
    const style = document.createElement('style');
    style.textContent = `
        .action-btn {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .stat-number {
            transition: transform 0.3s ease;
        }
    `;
    document.head.appendChild(style);
    </script>
</body>

</html>