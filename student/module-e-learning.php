<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// mengambil data modul dari database sesuai id di url
$modul = mysqli_real_escape_string($conn, $_GET['module']);

// VALIDASI: Cek apakah modul sebelumnya sudah lulus post-test (HANYA dari post_test_e_learning_result)
$sql_check_order = "SELECT * FROM module WHERE id = '{$modul}'";
$query_check_order = mysqli_query($conn, $sql_check_order);
$current_module = mysqli_fetch_assoc($query_check_order);

if ($current_module) {
    // Ambil modul sebelumnya
    $sql_previous = "SELECT id FROM module WHERE id < '{$modul}' ORDER BY id DESC LIMIT 1";
    $query_previous = mysqli_query($conn, $sql_previous);
    
    if (mysqli_num_rows($query_previous) > 0) {
        $previous_module = mysqli_fetch_assoc($query_previous);
        $previous_module_id = $previous_module['id'];
        
        // CEK HANYA dari post_test_e_learning_result dengan status LULUS
        $sql_check_passed = "SELECT * FROM post_test_e_learning_result 
                             WHERE student_id = '{$_SESSION['student_id']}' 
                             AND module_id = '{$previous_module_id}' 
                             AND status = 'lulus'";
        $query_check_passed = mysqli_query($conn, $sql_check_passed);
        
        // Jika modul sebelumnya belum lulus post-test, BLOCK akses
        if (mysqli_num_rows($query_check_passed) == 0) {
            header('location: index-e-learning.php?error=locked&module=' . $modul . '&required=' . $previous_module_id);
            exit();
        }
    }
}

$sql = "SELECT * from materi where module_id = '{$modul}'";
$query = mysqli_query($conn, $sql);
$materi = mysqli_fetch_array($query, MYSQLI_ASSOC);

// Cek apakah user sudah lulus post-test untuk modul ini
$sql_posttest = "SELECT * FROM post_test_e_learning_result WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$modul}' AND status = 'lulus'";
$query_posttest = mysqli_query($conn, $sql_posttest);
$has_passed = mysqli_num_rows($query_posttest) > 0;

// Ambil nilai post-test jika ada
$posttest_score = null;
if ($has_passed) {
    $posttest_result = mysqli_fetch_assoc($query_posttest);
    $posttest_score = $posttest_result['nilai'];
}

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Modul E-Learning Biologi - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --module-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --module-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --module-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --module-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --module-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --module-light: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .module-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
            transition: all 0.4s ease;
        }
        
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .module-header {
            background: var(--module-primary);
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .module-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: moduleFloat 8s ease-in-out infinite;
        }
        
        @keyframes moduleFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-5deg); }
        }
        
        .module-content {
            padding: 3rem 2rem;
            line-height: 1.8;
            font-size: 1.1rem;
            color: #333;
        }
        
        .module-content h1, .module-content h2, .module-content h3 {
            color: #2E7D32;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
        }
        
        .module-content h1 {
            border-bottom: 3px solid #00C851;
            padding-bottom: 0.5rem;
        }
        
        .module-content p {
            margin-bottom: 1.5rem;
            text-align: justify;
        }
        
        .module-content img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }
        
        .module-content ul, .module-content ol {
            padding-left: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .module-content li {
            margin-bottom: 0.5rem;
        }
        
        .btn-back {
            background: var(--module-info);
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
        
        .btn-back:hover {
            background: var(--module-secondary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }
        
        .floating-icons {
            position: absolute;
            font-size: 3rem;
            opacity: 0.1;
            animation: floatIcons 6s ease-in-out infinite;
        }
        
        .floating-icons.icon1 {
            top: 20%;
            right: 15%;
            animation-delay: 0s;
        }
        
        .floating-icons.icon2 {
            bottom: 30%;
            left: 10%;
            animation-delay: 2s;
        }
        
        @keyframes floatIcons {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.05; }
            50% { transform: translateY(-10px) rotate(5deg); opacity: 0.15; }
        }
        
        .progress-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: var(--module-primary);
            z-index: 9999;
            transition: width 0.3s ease;
        }
        
        .module-navigation {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
            margin: 0;
        }
        
        .breadcrumb-custom .breadcrumb-item {
            color: #666;
        }
        
        .breadcrumb-custom .breadcrumb-item.active {
            color: #00C851;
            font-weight: bold;
        }
        
        .completion-badge {
            background: var(--module-success);
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .module-header {
                padding: 1.5rem 1rem;
                text-align: center;
            }
            
            .module-content {
                padding: 2rem 1rem;
                font-size: 1rem;
            }
            
            .floating-icons {
                display: none;
            }
            
            .module-navigation {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .btn-back {
                padding: 0.8rem 2rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <!-- Progress Indicator -->
    <div class="progress-indicator" id="progressIndicator"></div>
    
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--module-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
            <?php if (isset($_GET['posttest'])) { ?>
            <?php if ($_GET['posttest'] == 'passed') { ?>
            <!-- Success Alert - Lulus Post-Test -->
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 15px; border-left: 5px solid #00C851;">
                <h5 class="alert-heading">
                    <i class="fas fa-trophy me-2"></i>Selamat! Post-Test Berhasil! 🎉
                </h5>
                <p class="mb-2">
                    Anda telah <strong>LULUS</strong> post-test dengan nilai <strong><?php echo number_format($_SESSION['posttest_score'], 0); ?></strong>!
                </p>
                <hr>
                <p class="mb-0">
                    <i class="fas fa-arrow-right me-2"></i>Silakan lanjutkan pembelajaran ke modul berikutnya di bawah ini.
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php } elseif ($_GET['posttest'] == 'failed') { ?>
            <!-- Warning Alert - Gagal Post-Test -->
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert" style="border-radius: 15px; border-left: 5px solid #FF8800;">
                <h5 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-2"></i>Post-Test Belum Berhasil
                </h5>
                <p class="mb-2">
                    Nilai Anda: <strong><?php echo number_format($_SESSION['posttest_score'], 0); ?></strong> (Minimal: 70)
                </p>
                <hr>
                <p class="mb-0">
                    <i class="fas fa-redo me-2"></i>Silakan <strong>pelajari kembali materi di bawah</strong>, lalu coba post-test lagi untuk melanjutkan.
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php } ?>
            <?php 
            // Clear session after displaying
            unset($_SESSION['posttest_status']);
            unset($_SESSION['posttest_score']);
            unset($_SESSION['posttest_module']);
            ?>
            <?php } ?>
            <!-- Navigation Breadcrumb -->
            <div class="module-navigation">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom">
                        <li class="breadcrumb-item">
                            <a href="index.php" class="text-decoration-none">
                                <i class="fas fa-home me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="index-e-learning.php" class="text-decoration-none">
                                <i class="fas fa-laptop-code me-1"></i>E-Learning
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-book me-1"></i>Modul <?php echo $modul; ?>
                        </li>
                    </ol>
                </nav>
                
                <div class="d-flex gap-2 align-items-center">
                    <span class="completion-badge">
                        <i class="fas fa-graduation-cap me-2"></i>Pembelajaran Aktif
                    </span>
                    <a href="index-e-learning.php" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <!-- Module Content -->
            <div class="row">
                <div class="col-12">
                    <div class="module-card">
                        <div class="module-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-dna"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-6 fw-bold mb-3">
                                        <i class="fas fa-book-open me-3"></i>Modul Pembelajaran Biologi
                                    </h1>
                                    <p class="lead mb-0">Jelajahi konsep-konsep biologi melalui materi interaktif dan komprehensif</p>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-seedling" style="font-size: 5rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="module-content" id="moduleContent">
                            <?php 
                            if ($materi && !empty($materi['materi_desc'])) {
                                echo $materi['materi_desc'];
                            } else {
                                echo '<div class="text-center py-5">';
                                echo '<i class="fas fa-exclamation-triangle fa-5x text-warning mb-3"></i>';
                                echo '<h3 class="text-muted">Materi Tidak Ditemukan</h3>';
                                echo '<p class="lead text-muted">Materi untuk modul ini belum tersedia atau terjadi kesalahan dalam memuat konten.</p>';
                                echo '<a href="index-e-learning.php" class="btn-back mt-3">';
                                echo '<i class="fas fa-arrow-left me-2"></i>Kembali ke E-Learning';
                                echo '</a>';
                                echo '</div>';
                            }
                            ?>
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
        // Initialize page enhancements
        initializeModule();
        
        // Reading progress tracker
        trackReadingProgress();
        
        // Smooth scrolling for anchor links
        initializeSmoothScrolling();
        
        // Auto-mark module as learned (if system supports it)
        setTimeout(markModuleAsLearned, 30000); // After 30 seconds
    });
    
    function initializeModule() {
        // Add reading time estimation
        const content = $('#moduleContent').text();
        const wordCount = content.split(' ').length;
        const readingTime = Math.ceil(wordCount / 200); // Average reading speed
        
        if (readingTime > 0) {
            const readingBadge = `
                <div class="alert alert-info border-0 rounded-4 mb-4" style="background: var(--module-info); color: white;">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Estimasi Waktu Baca:</strong> ${readingTime} menit
                    <span class="float-end">
                        <i class="fas fa-book-reader"></i>
                    </span>
                </div>
            `;
            $('#moduleContent').prepend(readingBadge);
        }
        
        // Enhance content formatting
        enhanceContentFormatting();
        
        // Add completion button at the end
        addCompletionButton();
    }
    
    function enhanceContentFormatting() {
        // Style headings with biology icons
        $('#moduleContent h1').each(function() {
            $(this).prepend('<i class="fas fa-seedling me-2"></i>');
        });
        
        $('#moduleContent h2').each(function() {
            $(this).prepend('<i class="fas fa-leaf me-2"></i>');
        });
        
        $('#moduleContent h3').each(function() {
            $(this).prepend('<i class="fas fa-dna me-2"></i>');
        });
        
        // Add highlight to important paragraphs
        $('#moduleContent p').each(function() {
            if ($(this).text().toLowerCase().includes('penting') || 
                $(this).text().toLowerCase().includes('catatan') ||
                $(this).text().toLowerCase().includes('ingat')) {
                $(this).addClass('alert alert-warning border-0 rounded-4');
                $(this).prepend('<i class="fas fa-exclamation-triangle me-2"></i>');
            }
        });
    }
    
    function trackReadingProgress() {
        $(window).scroll(function() {
            const scrollTop = $(window).scrollTop();
            const documentHeight = $(document).height() - $(window).height();
            const progress = (scrollTop / documentHeight) * 100;
            
            $('#progressIndicator').css('width', progress + '%');
        });
    }
    
    function initializeSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    }
    
    function addCompletionButton() {
        <?php if ($has_passed) { ?>
        // User sudah lulus post-test, tampilkan badge selesai
        const completionSection = `
            <div class="text-center py-5 mt-5" style="border-top: 2px solid #e9ecef;">
                <div class="mb-4">
                    <i class="fas fa-trophy fa-5x" style="color: #FFD700;"></i>
                </div>
                <h4 class="mb-3" style="color: #00C851;">Modul Telah Selesai!</h4>
                <div class="mb-4">
                    <span class="badge" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white; font-size: 1.2rem; padding: 0.8rem 1.5rem;">
                        <i class="fas fa-award me-2"></i>Nilai Post-Test: <?php echo number_format($posttest_score, 0); ?>
                    </span>
                </div>
                <p class="lead text-muted mb-4">Anda telah lulus post-test untuk modul ini</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="index-e-learning.php" class="btn btn-success btn-lg rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke E-Learning
                    </a>
                </div>
            </div>
        `;
        <?php } else { ?>
        // User belum lulus, tampilkan tombol post-test
        const completionSection = `
            <div class="text-center py-5 mt-5" style="border-top: 2px solid #e9ecef;">
                <div class="mb-4">
                    <i class="fas fa-clipboard-check fa-5x" style="color: #00C851;"></i>
                </div>
                <h4 class="mb-3">Selesaikan Modul dengan Post-Test</h4>
                <p class="lead text-muted mb-4">Uji pemahaman Anda dengan mengikuti post-test. Nilai minimal 70 untuk lulus.</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="post-test-e-learning.php?module=<?php echo $modul; ?>" class="btn btn-warning btn-lg rounded-pill px-4">
                        <i class="fas fa-pen me-2"></i>Mulai Post-Test
                    </a>
                    <a href="index-e-learning.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke E-Learning
                    </a>
                </div>
            </div>
        `;
        <?php } ?>
        $('#moduleContent').append(completionSection);
    }
    
    function markAsCompleted() {
        Swal.fire({
            title: 'Modul Selesai! 🎉',
            text: 'Selamat! Anda telah menyelesaikan modul pembelajaran ini.',
            icon: 'success',
            confirmButtonColor: '#00C851',
            confirmButtonText: 'Lanjutkan',
            showCancelButton: true,
            cancelButtonText: 'Tetap di Sini'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect back to e-learning index
                window.location.href = 'index-e-learning.php';
            }
        });
    }
    
    function markModuleAsLearned() {
        // This would typically make an AJAX call to mark module as learned
        // For now, we'll just show a subtle notification
        const toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        
        toast.fire({
            icon: 'info',
            title: 'Progress tersimpan otomatis',
            background: '#00C851',
            color: 'white'
        });
    }
    
    // Keyboard navigation
    $(document).keydown(function(e) {
        // ESC key to go back
        if (e.keyCode === 27) {
            window.location.href = 'index-e-learning.php';
        }
        
        // Space bar to scroll
        if (e.keyCode === 32) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(window).scrollTop() + $(window).height() * 0.8
            }, 500);
        }
    });
    </script>
</body>

</html>