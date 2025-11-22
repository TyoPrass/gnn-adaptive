<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
    exit();
}

// mengambil data module dari URL
$module_id = mysqli_real_escape_string($conn, $_GET['module']);
$student_id = $_SESSION['student_id'];

// Ambil data module
$sql = "SELECT * FROM module WHERE id = '{$module_id}'";
$query = mysqli_query($conn, $sql);
$modul = mysqli_fetch_array($query, MYSQLI_ASSOC);

// Cek apakah ada hasil post-test
$sql_check = "SELECT * FROM post_test_adaptive_result 
              WHERE student_id = '{$student_id}' 
              AND module_id = '{$module_id}' 
              ORDER BY id DESC LIMIT 1";
$query_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($query_check) == 0) {
    // Jika belum ada hasil, redirect ke post-test
    header('location: post_test.php?module=' . $module_id);
    exit();
}

$result = mysqli_fetch_assoc($query_check);
$is_passed = ($result['status'] == 'lulus');

// Ambil nilai pre-test untuk perbandingan
$sql_pretest = "SELECT score, correct_answers, total_questions FROM result_hasil_pretest 
                WHERE student_id = '{$student_id}' AND module_id = '{$module_id}' LIMIT 1";
$query_pretest = mysqli_query($conn, $sql_pretest);
$pretest = mysqli_fetch_assoc($query_pretest);

// Tentukan nilai terbaik
$best_score = max($result['score'], $pretest['score']);
$is_posttest_better = ($result['score'] >= $pretest['score']);

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Hasil Post Test - <?php echo $modul['module_desc']; ?> - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --warning-gradient: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .result-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .result-header {
            background: <?php echo $is_passed ? 'var(--success-gradient)' : 'var(--warning-gradient)'; ?>;
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .result-header::before {
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
        
        .score-display {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .score-number {
            font-size: 5rem;
            font-weight: bold;
            background: <?php echo $is_passed ? 'var(--success-gradient)' : 'var(--warning-gradient)'; ?>;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 1rem 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 1rem 3rem;
            border-radius: 50px;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 1rem 0;
            <?php if ($is_passed) { ?>
                background: var(--success-gradient);
            <?php } else { ?>
                background: var(--warning-gradient);
            <?php } ?>
            color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .detail-box {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 5px solid;
        }
        
        .detail-box.correct {
            border-left-color: #00C851;
        }
        
        .detail-box.incorrect {
            border-left-color: #ff4444;
        }
        
        .detail-box.total {
            border-left-color: #667eea;
        }
        
        .detail-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .detail-label {
            font-size: 1.2rem;
            color: #666;
        }
        
        .action-button {
            padding: 1rem 2.5rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-primary-custom {
            background: var(--primary-gradient);
            color: white;
        }
        
        .btn-success-custom {
            background: var(--success-gradient);
            color: white;
        }
        
        .action-button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .icon-large {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: bounceIn 0.8s ease;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .percentage-bar {
            height: 30px;
            border-radius: 15px;
            background: #e0e0e0;
            overflow: hidden;
            margin: 1rem 0;
        }
        
        .percentage-fill {
            height: 100%;
            background: <?php echo $is_passed ? 'var(--success-gradient)' : 'var(--warning-gradient)'; ?>;
            transition: width 1s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .score-number {
                font-size: 3rem;
            }
            .status-badge {
                font-size: 1.2rem;
                padding: 0.75rem 2rem;
            }
            .detail-number {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--primary-gradient); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>MyIRT Learning
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
                    <div class="result-card">
                        <div class="result-header">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-chart-line me-3"></i>Hasil Post Test
                                    </h1>
                                    <h3 class="mb-2"><?php echo $modul['module_desc']; ?></h3>
                                    <p class="lead mb-0">Berikut adalah hasil post-test Anda</p>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas <?php echo $is_passed ? 'fa-trophy' : 'fa-redo'; ?> icon-large"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Score Display -->
            <div class="row mb-4">
                <div class="col-lg-6 offset-lg-3">
                    <div class="score-display">
                        <i class="fas <?php echo $is_passed ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-warning'; ?>" style="font-size: 3rem;"></i>
                        <div class="score-number"><?php echo number_format($result['score'], 0); ?></div>
                        <div class="percentage-bar">
                            <div class="percentage-fill" style="width: <?php echo $result['score']; ?>%">
                                <?php echo number_format($result['score'], 1); ?>%
                            </div>
                        </div>
                        <div class="status-badge">
                            <?php echo $is_passed ? '✓ LULUS' : '✗ BELUM LULUS'; ?>
                        </div>
                        <p class="text-muted mt-3">
                            <?php if ($is_passed) { ?>
                                <i class="fas fa-star me-2"></i>Selamat! Anda telah menguasai materi ini
                            <?php } else { ?>
                                <i class="fas fa-info-circle me-2"></i>Passing Grade: 70 | Anda perlu <?php echo 70 - $result['score']; ?> poin lagi
                            <?php } ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="detail-box correct">
                        <div class="text-center">
                            <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                            <div class="detail-number text-success"><?php echo $result['correct_answers']; ?></div>
                            <div class="detail-label">Jawaban Benar</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="detail-box incorrect">
                        <div class="text-center">
                            <i class="fas fa-times-circle text-danger" style="font-size: 2rem;"></i>
                            <div class="detail-number text-danger">
                                <?php echo $result['total_questions'] - $result['correct_answers']; ?>
                            </div>
                            <div class="detail-label">Jawaban Salah</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="detail-box total">
                        <div class="text-center">
                            <i class="fas fa-list text-primary" style="font-size: 2rem;"></i>
                            <div class="detail-number text-primary"><?php echo $result['total_questions']; ?></div>
                            <div class="detail-label">Total Soal</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Perbandingan Nilai -->
            <?php if ($pretest): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="info-card">
                        <h5 class="mb-3">
                            <i class="fas fa-balance-scale me-2"></i>Perbandingan Nilai
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded <?php echo !$is_posttest_better ? 'bg-light border-warning' : ''; ?>">
                                    <h6>Pre-Test</h6>
                                    <div class="fs-2 fw-bold <?php echo !$is_posttest_better ? 'text-warning' : 'text-muted'; ?>">
                                        <?php echo number_format($pretest['score'], 0); ?>
                                    </div>
                                    <small><?php echo $pretest['correct_answers']; ?>/<?php echo $pretest['total_questions']; ?> benar</small>
                                    <?php if (!$is_posttest_better): ?>
                                    <br><span class="badge bg-warning mt-2">Nilai Terbaik</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <i class="fas fa-arrow-right fa-3x text-primary"></i>
                                    <br>
                                    <small class="text-muted">vs</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded <?php echo $is_posttest_better ? 'bg-light border-success' : ''; ?>">
                                    <h6>Post-Test</h6>
                                    <div class="fs-2 fw-bold <?php echo $is_posttest_better ? 'text-success' : 'text-muted'; ?>">
                                        <?php echo number_format($result['score'], 0); ?>
                                    </div>
                                    <small><?php echo $result['correct_answers']; ?>/<?php echo $result['total_questions']; ?> benar</small>
                                    <?php if ($is_posttest_better): ?>
                                    <br><span class="badge bg-success mt-2">Nilai Terbaik</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-<?php echo $is_posttest_better ? 'success' : 'warning'; ?> mt-3 mb-0">
                            <i class="fas <?php echo $is_posttest_better ? 'fa-arrow-up' : 'fa-arrow-down'; ?> me-2"></i>
                            <?php if ($is_posttest_better): ?>
                                <strong>Bagus!</strong> Nilai post-test Anda lebih tinggi. Sistem akan menggunakan nilai <strong><?php echo number_format($result['score'], 0); ?></strong>.
                            <?php else: ?>
                                <strong>Info:</strong> Nilai pre-test Anda masih lebih tinggi. Sistem akan menggunakan nilai <strong><?php echo number_format($pretest['score'], 0); ?></strong>.
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Info Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="info-card">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informasi
                        </h5>
                        <?php if ($is_passed) { ?>
                        <div class="alert alert-success mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Anda sudah lulus post-test ini!</strong> Nilai terbaik Anda adalah <strong><?php echo number_format($best_score, 0); ?></strong>.
                        </div>
                        <ul class="mb-0">
                            <li>Nilai Post-Test: <?php echo number_format($result['score'], 0); ?> (<?php echo $result['correct_answers']; ?>/<?php echo $result['total_questions']; ?> benar)</li>
                            <li>Nilai Pre-Test: <?php echo number_format($pretest['score'], 0); ?> (<?php echo $pretest['correct_answers']; ?>/<?php echo $pretest['total_questions']; ?> benar)</li>
                            <li>Nilai Terbaik: <strong><?php echo number_format($best_score, 0); ?></strong> dari <?php echo $is_posttest_better ? 'Post-Test' : 'Pre-Test'; ?></li>
                            <li>Status: <span class="badge bg-success">LULUS</span></li>
                            <li>Anda dapat melanjutkan ke modul berikutnya atau mencoba perbaiki nilai</li>
                        </ul>
                        <?php } else { ?>
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Anda belum lulus post-test ini.</strong> Silakan coba lagi untuk meningkatkan pemahaman Anda.
                        </div>
                        <ul class="mb-0">
                            <li>Nilai Anda: <?php echo number_format($result['score'], 0); ?> (Passing Grade: 70)</li>
                            <li>Jawaban benar: <?php echo $result['correct_answers']; ?>/<?php echo $result['total_questions']; ?></li>
                            <li>Anda perlu <?php echo 70 - $result['score']; ?> poin lagi untuk lulus</li>
                            <li>Silakan pelajari kembali materi dan coba lagi</li>
                        </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-12 text-center">
                    <?php if ($is_passed) { ?>
                    <a href="post_test.php?module=<?php echo $module_id; ?>&retry=1" class="action-button btn-warning text-white">
                        <i class="fas fa-redo me-2"></i>Perbaiki Nilai
                    </a>
                    <?php } else { ?>
                    <a href="post_test.php?module=<?php echo $module_id; ?>" class="action-button btn-success-custom">
                        <i class="fas fa-redo me-2"></i>Coba Lagi
                    </a>
                    <?php } ?>
                    <a href="module.php?module=<?php echo $module_id; ?>" class="action-button btn-primary-custom">
                        <i class="fas fa-book me-2"></i>Kembali ke Modul
                    </a>
                    <a href="modul-rekomendasi.php" class="action-button btn btn-secondary">
                        <i class="fas fa-route me-2"></i>Lihat Rekomendasi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animation on load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate percentage bar
            const percentageFill = document.querySelector('.percentage-fill');
            const targetWidth = percentageFill.style.width;
            percentageFill.style.width = '0%';
            setTimeout(() => {
                percentageFill.style.width = targetWidth;
            }, 500);

            // Animate numbers
            const detailNumbers = document.querySelectorAll('.detail-number');
            detailNumbers.forEach(number => {
                const target = parseInt(number.textContent);
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    number.textContent = Math.floor(current);
                }, 20);
            });

            // Animate score number
            const scoreNumber = document.querySelector('.score-number');
            const scoreTarget = parseInt(scoreNumber.textContent);
            let scoreCurrent = 0;
            const scoreIncrement = scoreTarget / 50;
            const scoreTimer = setInterval(() => {
                scoreCurrent += scoreIncrement;
                if (scoreCurrent >= scoreTarget) {
                    scoreCurrent = scoreTarget;
                    clearInterval(scoreTimer);
                }
                scoreNumber.textContent = Math.floor(scoreCurrent);
            }, 20);
        });
    </script>
</body>
</html>
