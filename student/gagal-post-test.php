<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

$student_id = $_SESSION['student_id'];

// Ambil module_id dari URL atau session
$module_id = isset($_GET['module_id']) ? mysqli_real_escape_string($conn, $_GET['module_id']) : null;

// Ambil hasil post-test dari session atau database
$score = isset($_SESSION['posttest_score']) ? $_SESSION['posttest_score'] : null;
$correct = isset($_SESSION['posttest_correct']) ? $_SESSION['posttest_correct'] : null;
$total = isset($_SESSION['posttest_total']) ? $_SESSION['posttest_total'] : null;

// Jika tidak ada di session dan ada module_id, ambil dari database
if ($score === null && $module_id !== null) {
    $sql_result = "SELECT * FROM post_test_adaptive_result 
                   WHERE student_id = '{$student_id}' 
                   AND module_id = '{$module_id}' 
                   AND status = 'gagal'
                   ORDER BY id DESC LIMIT 1";
    $query_result = mysqli_query($conn, $sql_result);
    
    if (mysqli_num_rows($query_result) > 0) {
        $result_data = mysqli_fetch_assoc($query_result);
        $score = $result_data['score'];
        $correct = $result_data['correct_answers'];
        $total = $result_data['total_questions'];
    }
}

// Clear session setelah digunakan
$saved_score = $score;
$saved_correct = $correct;
$saved_total = $total;
unset($_SESSION['posttest_score']);
unset($_SESSION['posttest_correct']);
unset($_SESSION['posttest_total']);
unset($_SESSION['posttest_status']);

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

    <title>Post Test Gagal - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --failure-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --failure-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --failure-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --failure-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --failure-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --failure-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
        }
        
        body {
            background: linear-gradient(135deg, #FFE8E8 0%, #FFF0F0 50%, #FFE0E0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .failure-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .failure-header {
            background: var(--failure-danger);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .failure-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: failureFloat 12s ease-in-out infinite;
        }
        
        @keyframes failureFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-10deg); }
        }
        
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid #ff4444;
        }
        
        .failure-alert {
            background: var(--failure-danger);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .failure-alert::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: alertPulse 4s ease-in-out infinite;
        }
        
        @keyframes alertPulse {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.02); }
        }
        
        .failure-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ff4444;
            animation: shake 2s ease-in-out infinite;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }
        
        .choice-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .choice-title {
            color: #2d3436;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 2rem;
            text-align: center;
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
            min-width: 200px;
            margin: 0.5rem;
        }
        
        .btn-danger-custom {
            background: var(--failure-danger);
            color: white;
        }
        
        .btn-danger-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255,68,68,0.4);
            color: white;
        }
        
        .btn-warning-custom {
            background: var(--failure-warning);
            color: white;
        }
        
        .btn-warning-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255,187,51,0.4);
            color: white;
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
            background: var(--failure-info);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
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
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .failure-header {
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
                min-width: 180px;
                margin: 0.5rem 0;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--failure-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
                    <div class="failure-card">
                        <div class="failure-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="floating-icons icon3">
                                <i class="fas fa-redo"></i>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-times-circle me-3"></i>Post Test Gagal
                                    </h1>
                                    <p class="lead mb-3">Jangan berkecil hati! Kegagalan adalah bagian dari proses pembelajaran</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-redo me-1"></i>Coba Lagi
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-level-down-alt me-1"></i>Opsi Level
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-book-open me-1"></i>Pelajari Ulang
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="failure-icon">
                                        <i class="fas fa-sad-cry"></i>
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
                    <?php if ($_SESSION['level_user'] == 3) { ?>
                        <div class="content-card">
                            <!-- Failure Alert -->
                            <div class="failure-alert">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <h3 class="mb-3">Post Test Tidak Lulus</h3>
                                <?php if ($saved_score !== null) { ?>
                                <div class="mt-4 mb-3">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8">
                                            <div class="score-display p-4 bg-white rounded-3 shadow-sm border border-danger">
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="score-item">
                                                            <i class="fas fa-chart-line text-danger fa-2x mb-2"></i>
                                                            <h2 class="mb-0 fw-bold text-danger"><?php echo number_format($saved_score, 0); ?></h2>
                                                            <small class="text-muted">Nilai Anda</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="score-item">
                                                            <i class="fas fa-tasks text-warning fa-2x mb-2"></i>
                                                            <h2 class="mb-0 fw-bold text-warning"><?php echo $saved_correct; ?>/<?php echo $saved_total; ?></h2>
                                                            <small class="text-muted">Jawaban Benar</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="score-item">
                                                            <i class="fas fa-bullseye text-info fa-2x mb-2"></i>
                                                            <h2 class="mb-0 fw-bold text-info">70</h2>
                                                            <small class="text-muted">Nilai Minimum</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 text-center">
                                                    <p class="mb-0 text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Anda perlu <?php echo (70 - $saved_score); ?> poin lagi untuk lulus
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <p class="lead mb-0">Anda belum berhasil mencapai passing grade yang diperlukan</p>
                            </div>
                            
                            <!-- Info Box -->
                            <div class="info-box">
                                <i class="fas fa-lightbulb fa-2x mb-3"></i>
                                <h5 class="mb-2">Tips untuk Sukses</h5>
                                <p class="mb-0">Pelajari kembali materi dengan lebih teliti atau pilih level yang lebih sesuai dengan kemampuan Anda</p>
                            </div>

                            <!-- Choice Section -->
                            <div class="choice-section">
                                <?php if ($_SESSION['level_user'] > 1) : ?>
                                    <div class="choice-title">
                                        <i class="fas fa-question-circle me-2"></i>
                                        Pilih langkah selanjutnya untuk melanjutkan pembelajaran
                                    </div>
                                <?php endif ?>
                                
                                <div class="text-center">
                                    <form action="../data/level-down.php" method="POST" class="d-inline">
                                        <?php if ($_SESSION['cek_level']==false) : ?>
                                            <!-- <button type="submit" class="action-button btn-danger-custom">
                                                <i class="fas fa-level-down-alt me-2"></i>
                                                TURUNKAN LEVEL BELAJAR
                                            </button> -->
                                        <?php endif ?>
                                    </form>
                                    
                                    <?php if ($_SESSION['cek_level']==false) { ?>
                                        <a href="modul.php" class="action-button btn-warning-custom">
                                            <i class="fas fa-redo me-2"></i>
                                            ULANGI PEMBELAJARAN
                                        </a>
                                    <?php } else { ?>
                                        <a href="modul.php" class="action-button btn-warning-custom">
                                            <i class="fas fa-arrow-down me-2"></i>
                                            TURUN LEVEL
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <!-- Stats Section -->
                            <div class="stats-container">
                                <div class="stat-item">
                                    <div class="stat-icon text-info">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <h6>Belajar Lagi</h6>
                                    <small class="text-muted">Pelajari materi dengan lebih mendalam</small>
                                </div>
                                <!-- <div class="stat-item">
                                    <div class="stat-icon text-warning">
                                        <i class="fas fa-level-down-alt"></i>
                                    </div>
                                    <h6>Turun Level</h6>
                                    <small class="text-muted">Mulai dari level yang lebih mudah</small>
                                </div> -->
                                <div class="stat-item">
                                    <div class="stat-icon text-success">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h6>Konsultasi</h6>
                                    <small class="text-muted">Tanyakan pada guru atau teman</small>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
        // Initialize failure page interactions
        initializeFailurePage();
        
        // Button hover effects
        $('.action-button').hover(
            function() {
                $(this).find('i').addClass('fa-bounce');
            },
            function() {
                $(this).find('i').removeClass('fa-bounce');
            }
        );
        
        // Level down confirmation
        $('form[action*="level-down"] button').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Turunkan Level?',
                text: 'Anda akan dipindahkan ke level yang lebih mudah. Apakah Anda yakin?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ff4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Turunkan!',
                cancelButtonText: 'Batal',
                background: '#fff',
                customClass: {
                    confirmButton: 'btn-danger-custom',
                    cancelButton: 'btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang menurunkan level pembelajaran',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    form.submit();
                }
            });
        });
        
        // Retry confirmation
        $('a[href*="modul.php"]').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const buttonText = $(this).text().trim();
            
            let title, text;
            if (buttonText.includes('ULANGI')) {
                title = 'Ulangi Pembelajaran?';
                text = 'Anda akan kembali ke modul pembelajaran untuk belajar lagi.';
            } else {
                title = 'Turun Level?';
                text = 'Level pembelajaran Anda akan diturunkan secara otomatis.';
            }
            
            Swal.fire({
                title: title,
                text: text,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#ffbb33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show encouragement message
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    
                    toast.fire({
                        icon: 'success',
                        title: 'Semangat! Jangan menyerah!',
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
        }, 500);
        
        // Stats hover effects
        $('.stat-item').hover(
            function() {
                $(this).find('.stat-icon i').addClass('fa-bounce');
            },
            function() {
                $(this).find('.stat-icon i').removeClass('fa-bounce');
            }
        );
    });
    
    function initializeFailurePage() {
        // Add motivational quotes rotation
        const quotes = [
            'Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas.',
            'Setiap ahli pernah menjadi pemula.',
            'Jangan takut gagal, takutlah tidak mencoba.',
            'Belajar dari kesalahan adalah kunci sukses.',
            'Persistence beats resistance always.'
        ];
        
        let currentQuote = 0;
        setInterval(() => {
            // Rotate motivational message if exists
            const motivationEl = $('.info-box p');
            if (motivationEl.length > 0) {
                motivationEl.fadeOut(300, function() {
                    $(this).text(quotes[currentQuote]).fadeIn(300);
                    currentQuote = (currentQuote + 1) % quotes.length;
                });
            }
        }, 5000);
        
        // Add pulse effect to failure icon
        setInterval(() => {
            $('.failure-icon').addClass('animate__heartBeat');
            setTimeout(() => {
                $('.failure-icon').removeClass('animate__heartBeat');
            }, 1000);
        }, 3000);
    }
    
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
        
        .failure-alert {
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
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
    `;
    document.head.appendChild(style);
    </script>
</body>

</html>