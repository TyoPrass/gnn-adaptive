<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// mengambil data modul dari database sesuai id di url
$modul = mysqli_real_escape_string($conn, $_GET['module']);
$sql = "SELECT * from materi where module_id = '{$modul}'";
$query = mysqli_query($conn, $sql);
$materi = mysqli_fetch_array($query, MYSQLI_ASSOC);

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Modul Pembelajaran Biologi - MyIRT Adaptive Learning</title>
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
            --module-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
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
        }
        
        .module-header {
            background: var(--module-primary);
            color: white;
            padding: 3rem 2rem;
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
            animation: moduleFloat 12s ease-in-out infinite;
        }
        
        @keyframes moduleFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-10deg); }
        }
        
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid #00C851;
        }
        
        .module-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #2d3436;
            text-align: justify;
        }
        
        .module-content h1, .module-content h2, .module-content h3 {
            color: #00C851;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
        }
        
        .module-content h1:first-child, .module-content h2:first-child, .module-content h3:first-child {
            margin-top: 0;
        }
        
        .module-content p {
            margin-bottom: 1.5rem;
        }
        
        .module-content ul, .module-content ol {
            padding-left: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .module-content li {
            margin-bottom: 0.5rem;
        }
        
        .module-content img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }
        
        .post-test-section {
            background: var(--module-warning);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .post-test-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: testPulse 6s ease-in-out infinite;
        }
        
        @keyframes testPulse {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.05); }
        }
        
        .btn-post-test {
            background: white;
            color: #FF8800;
            border: none;
            padding: 1.2rem 3rem;
            font-size: 1.3rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
        }
        
        .btn-post-test:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
            color: #FF8800;
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
        
        .navigation-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .nav-button {
            background: var(--module-info);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .nav-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            color: white;
            text-decoration: none;
        }
        
        @media (max-width: 768px) {
            .module-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .content-card {
                padding: 2rem 1.5rem;
            }
            
            .post-test-section {
                padding: 2rem 1rem;
            }
            
            .btn-post-test {
                padding: 1rem 2rem;
                font-size: 1.1rem;
            }
            
            .floating-icons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--module-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
                    <div class="module-card">
                        <div class="module-header">
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
                                        <i class="fas fa-book-open me-3"></i>Modul Pembelajaran Biologi
                                    </h1>
                                    <p class="lead mb-3">Pelajari materi biologi secara mendalam dengan panduan yang terstruktur</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-brain me-1"></i>Materi Lengkap
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-clipboard-question me-1"></i>Post Test
                                        </span>
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-trophy me-1"></i>Evaluasi
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-atom" style="font-size: 6rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="navigation-card">
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <a href="modul.php" class="nav-button">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Modul
                            </a>
                            <div class="text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Dashboard > Modul > Materi Pembelajaran
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="row">
                <div class="col-12">
                    <div class="content-card">
                        <div class="module-content">
                            <?php echo $materi['materi_desc'] ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post Test Section -->
            <div class="row">
                <div class="col-12">
                    <div class="post-test-section">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h2 class="mb-3">
                                    <i class="fas fa-clipboard-question me-3"></i>Post Test
                                </h2>
                                <p class="lead mb-3">
                                    Uji pemahaman Anda setelah mempelajari materi ini
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Selesaikan post test untuk melanjutkan ke modul berikutnya
                                </p>
                            </div>
                            <div class="col-lg-4 text-center">
                                <a href="post_test.php?module=<?php echo $_GET['module'] ?>" class="btn-post-test">
                                    <i class="fas fa-play me-2"></i>
                                    MULAI POST TEST
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        // Initialize module interactions
        initializeModuleInteractions();
        
        // Post test button interactions
        $('.btn-post-test').hover(
            function() {
                $(this).find('i').addClass('fa-bounce');
            },
            function() {
                $(this).find('i').removeClass('fa-bounce');
            }
        );
        
        // Navigation button interactions
        $('.nav-button').hover(
            function() {
                $(this).find('i').addClass('fa-bounce');
            },
            function() {
                $(this).find('i').removeClass('fa-bounce');
            }
        );
        
        // Content animations
        animateContent();
        
        // Post test confirmation
        $('.btn-post-test').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            Swal.fire({
                title: 'Mulai Post Test?',
                text: 'Pastikan Anda telah memahami materi dengan baik sebelum mengerjakan post test.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF8800',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Mulai Test!',
                cancelButtonText: 'Belajar Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    
                    toast.fire({
                        icon: 'info',
                        title: 'Mempersiapkan Post Test...',
                        background: '#FF8800',
                        color: 'white'
                    });
                    
                    setTimeout(() => {
                        window.location.href = href;
                    }, 1000);
                }
            });
        });
    });
    
    function initializeModuleInteractions() {
        // Content reading progress simulation
        let readingProgress = 0;
        const contentHeight = $('.module-content')[0].scrollHeight;
        const windowHeight = $(window).height();
        
        $(window).scroll(function() {
            const scrollTop = $(window).scrollTop();
            const contentTop = $('.module-content').offset().top;
            
            if (scrollTop > contentTop - windowHeight) {
                readingProgress = Math.min(100, ((scrollTop - contentTop + windowHeight) / contentHeight) * 100);
            }
        });
    }
    
    function animateContent() {
        // Animate content sections on scroll
        $(window).scroll(function() {
            $('.content-card, .post-test-section').each(function() {
                const elementTop = $(this).offset().top;
                const elementBottom = elementTop + $(this).outerHeight();
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();
                
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('animate-in');
                }
            });
        });
        
        // Highlight important content
        $('.module-content h1, .module-content h2, .module-content h3').each(function() {
            $(this).prepend('<i class="fas fa-leaf me-2" style="color: #00C851;"></i>');
        });
        
        // Add reading time estimation
        const wordCount = $('.module-content').text().split(' ').length;
        const readingTime = Math.ceil(wordCount / 200); // 200 words per minute
        
        $('.content-card').prepend(`
            <div class="alert alert-info mb-4" style="background: var(--module-info); color: white; border: none;">
                <i class="fas fa-clock me-2"></i>
                <strong>Waktu Baca Estimasi:</strong> ${readingTime} menit
            </div>
        `);
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
        
        .module-content {
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