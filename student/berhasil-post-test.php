<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// Validasi module_id dari URL parameter
if (!isset($_GET['module_id']) || empty($_GET['module_id'])) {
    header('location: modul.php');
    exit();
}

$completed_module_id = mysqli_real_escape_string($conn, $_GET['module_id']);

// Ambil data module yang selesai
$sql_module = "SELECT * FROM module WHERE id = '{$completed_module_id}'";
$query_module = mysqli_query($conn, $sql_module);

if (mysqli_num_rows($query_module) == 0) {
    header('location: modul.php');
    exit();
}

$module_data = mysqli_fetch_array($query_module, MYSQLI_ASSOC);

if ($_SESSION['level_user'] == 3) {
    $survey = mysqli_query($conn, "SELECT * FROM survey_result where student_id = '{$_SESSION['student_id']}'");
    $survey_row = mysqli_num_rows($survey);
    if ($survey_row == 1) {
        $_SESSION['survey_taken'] = true;
        $level = mysqli_fetch_array($survey, MYSQLI_ASSOC);
        $_SESSION['levels'] = $survey;
    } else {
        $_SESSION['survey_taken'] = false;
    }
}

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Post Test Berhasil - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --success-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --success-secondary: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            --success-accent: linear-gradient(135deg, #81C784 0%, #66BB6A 100%);
            --success-info: linear-gradient(135deg, #4DD0E1 0%, #0097A7 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E9 0%, #F1F8E9 50%, #E0F2F1 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .success-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .success-header {
            background: var(--success-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .success-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: successFloat 12s ease-in-out infinite;
        }
        
        @keyframes successFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(10deg); }
        }
        
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid #00C851;
        }
        
        .success-alert {
            background: var(--success-primary);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .success-alert::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: alertPulse 4s ease-in-out infinite;
        }
        
        @keyframes alertPulse {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.02); }
        }
        
        .success-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            color: #00C851;
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #00C851;
            animation: confettiFall 3s linear infinite;
        }
        
        @keyframes confettiFall {
            0% { transform: translateY(-100px) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
        
        .action-button {
            border: none;
            padding: 1.2rem 3rem;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
            min-width: 250px;
            margin: 0.5rem;
        }
        
        .btn-success-custom {
            background: var(--success-primary);
            color: white;
        }
        
        .btn-success-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,200,81,0.4);
            color: white;
        }
        
        .btn-info-custom {
            background: var(--success-info);
            color: white;
        }
        
        .btn-info-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(77,208,225,0.4);
            color: white;
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }
        
        .action-button:hover::before {
            left: 100%;
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
        
        .info-box {
            background: var(--success-accent);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .achievement-badge {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .achievement-badge .badge-icon {
            font-size: 4rem;
            color: #FFD700;
            margin-bottom: 1rem;
            animation: rotate 3s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .stat-item {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-top: 3px solid #00C851;
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #00C851;
        }
        
        @media (max-width: 768px) {
            .success-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .content-card {
                padding: 2rem 1rem;
            }
            
            .floating-icons {
                display: none;
            }
            
            .action-button {
                padding: 1rem 2rem;
                min-width: 200px;
                margin: 0.5rem 0;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Confetti Elements -->
    <div class="confetti-container">
        <div class="confetti" style="left: 10%; animation-delay: 0s; background: #00C851;"></div>
        <div class="confetti" style="left: 20%; animation-delay: 0.5s; background: #FFD700;"></div>
        <div class="confetti" style="left: 30%; animation-delay: 1s; background: #4CAF50;"></div>
        <div class="confetti" style="left: 40%; animation-delay: 1.5s; background: #81C784;"></div>
        <div class="confetti" style="left: 50%; animation-delay: 2s; background: #00C851;"></div>
        <div class="confetti" style="left: 60%; animation-delay: 2.5s; background: #FFD700;"></div>
        <div class="confetti" style="left: 70%; animation-delay: 0.8s; background: #4CAF50;"></div>
        <div class="confetti" style="left: 80%; animation-delay: 1.3s; background: #81C784;"></div>
        <div class="confetti" style="left: 90%; animation-delay: 1.8s; background: #00C851;"></div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--success-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="success-card">
                        <div class="success-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="floating-icons icon3">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-check-circle me-3"></i>Selamat! Post Test Berhasil
                                    </h1>
                                    <p class="lead mb-3">Anda telah menyelesaikan modul ini dengan sangat baik!</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-trophy me-1"></i>Lulus Post Test
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-arrow-right me-1"></i>Modul Selanjutnya Terbuka
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-graduation-cap me-1"></i>Prestasi Meningkat
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="success-icon">
                                        <i class="fas fa-smile-beam"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="row">
                <div class="col-12">
                    <div class="content-card">
                        <!-- Success Alert -->
                        <div class="success-alert">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h3 class="mb-3">Post Test Berhasil Diselesaikan!</h3>
                            <p class="lead mb-0">Anda telah mencapai passing grade dan modul ini telah selesai</p>
                        </div>
                        
                        <!-- Achievement Badge -->
                        <div class="achievement-badge">
                            <div class="badge-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h4 class="mb-2">Pencapaian Baru!</h4>
                            <p class="text-muted mb-0">Modul berhasil diselesaikan dengan nilai memuaskan</p>
                        </div>
                        
                        <!-- Info Box -->
                        <div class="info-box">
                            <i class="fas fa-unlock fa-2x mb-3"></i>
                            <h5 class="mb-2">Modul Selanjutnya Telah Terbuka!</h5>
                            <p class="mb-0">Anda sekarang dapat melanjutkan pembelajaran ke modul berikutnya</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <a href="modul.php" class="action-button btn-success-custom">
                                <i class="fas fa-book-open me-2"></i>
                                LANJUT KE MODUL SELANJUTNYA
                            </a>
                            <a href="index.php" class="action-button btn-info-custom">
                                <i class="fas fa-home me-2"></i>
                                KEMBALI KE DASHBOARD
                            </a>
                        </div>
                        
                        <!-- Stats Section -->
                        <div class="stats-container">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <h6>Modul Selesai</h6>
                                <small class="text-muted">Siap untuk tantangan berikutnya</small>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-unlock-alt"></i>
                                </div>
                                <h6>Akses Terbuka</h6>
                                <small class="text-muted">Modul selanjutnya sudah tersedia</small>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h6>Progress Meningkat</h6>
                                <small class="text-muted">Pembelajaran Anda semakin maju</small>
                            </div>
                        </div>
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
        // Show success message on page load
        Swal.fire({
            icon: 'success',
            title: 'Selamat!',
            text: 'Post Test berhasil diselesaikan. Modul selanjutnya telah terbuka!',
            confirmButtonColor: '#00C851',
            confirmButtonText: 'Lanjutkan',
            timer: 3000,
            timerProgressBar: true
        });
        
        // Button hover effects
        $('.action-button').hover(
            function() {
                $(this).find('i').addClass('fa-bounce');
            },
            function() {
                $(this).find('i').removeClass('fa-bounce');
            }
        );
        
        // Continue to next module confirmation
        $('a[href*="modul.php"]').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            Swal.fire({
                title: 'Lanjutkan Pembelajaran?',
                text: 'Anda akan melanjutkan ke modul berikutnya',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00C851',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Nanti Saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    
                    toast.fire({
                        icon: 'success',
                        title: 'Memuat modul...',
                        background: '#00C851',
                        color: 'white'
                    });
                    
                    setTimeout(() => {
                        window.location.href = href;
                    }, 1000);
                }
            });
        });
        
        // Animate content on load
        setTimeout(() => {
            $('.content-card').addClass('animate-in');
        }, 300);
        
        // Stats hover effects
        $('.stat-item').hover(
            function() {
                $(this).css('transform', 'translateY(-5px)');
                $(this).find('.stat-icon i').addClass('fa-bounce');
            },
            function() {
                $(this).css('transform', 'translateY(0)');
                $(this).find('.stat-icon i').removeClass('fa-bounce');
            }
        );
        
        // Trophy rotation on hover
        $('.badge-icon').hover(
            function() {
                $(this).css('animation', 'rotate 1s linear infinite');
            },
            function() {
                $(this).css('animation', 'rotate 3s linear infinite');
            }
        );
    });
    
    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        .animate-in {
            animation: slideInUp 0.6s ease-out;
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
        
        .success-alert {
            animation: fadeInDown 0.8s ease-out;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-item {
            transition: transform 0.3s ease;
        }
        
        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
        }
    `;
    document.head.appendChild(style);
    </script>
</body>

</html>
