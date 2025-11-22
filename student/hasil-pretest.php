<?php
/**
 * Halaman Hasil Pre-Test
 * Menampilkan hasil pretest student dengan detail per modul
 */

session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
    exit;
}

include('../config/db.php');
include('../data/gnn_helper.php');

$student_id = $_SESSION['student_id'];

// Cek apakah sudah ada hasil pretest
$check_query = "SELECT COUNT(*) as count FROM result_hasil_pretest WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$check_result = mysqli_stmt_get_result($stmt);
$check_data = mysqli_fetch_assoc($check_result);

if ($check_data['count'] == 0) {
    // Belum ada hasil, redirect ke pre-test
    header('location: pre-test.php');
    exit;
}

// Ambil hasil pretest per modul dengan hasil post-test (best score)
$query = "SELECT 
            rhp.*,
            m.module_desc,
            m.module_level,
            m.number as module_number,
            ptr.score as post_test_score,
            ptr.correct_answers as post_test_correct,
            ptr.total_questions as post_test_total,
            ptr.status as post_test_status
          FROM result_hasil_pretest rhp
          JOIN module m ON m.id = rhp.module_id
          LEFT JOIN post_test_adaptive_result ptr ON ptr.module_id = m.id 
                                                   AND ptr.student_id = rhp.student_id 
                                                   AND ptr.id = (
                                                       SELECT id FROM post_test_adaptive_result 
                                                       WHERE module_id = m.id 
                                                       AND student_id = rhp.student_id 
                                                       ORDER BY score DESC, id DESC LIMIT 1
                                                   )
          WHERE rhp.student_id = ?
          ORDER BY m.number ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);

// Hitung statistik
$total_correct = 0;
$total_questions = 0;
$total_score = 0;
$module_count = 0;

$modules_data = [];
while ($row = mysqli_fetch_assoc($results)) {
    // Simpan score asli pre-test
    $row['pretest_score'] = $row['score'];
    $row['pretest_correct'] = $row['correct_answers'];
    $row['pretest_total'] = $row['total_questions'];
    
    // Gunakan nilai TERBAIK antara pre-test dan post-test untuk perhitungan level
    if (!empty($row['post_test_score'])) {
        if ($row['post_test_score'] > $row['score']) {
            // Post-test lebih baik, gunakan nilai post-test
            $row['score'] = $row['post_test_score'];
            $row['correct_answers'] = $row['post_test_correct'];
            $row['total_questions'] = $row['post_test_total'];
            $row['best_source'] = 'post-test';
        } else {
            // Pre-test lebih baik, tetap gunakan nilai pre-test
            $row['best_source'] = 'pre-test';
        }
    } else {
        $row['best_source'] = 'pre-test';
    }
    
    $total_correct += $row['correct_answers'];
    $total_questions += $row['total_questions'];
    $total_score += $row['score'];
    $module_count++;
    $modules_data[] = $row;
}

$average_score = $module_count > 0 ? round($total_score / $module_count, 2) : 0;
$overall_percentage = $total_questions > 0 ? round(($total_correct / $total_questions) * 100, 2) : 0;

// Ambil level dari level_student (sama dengan modul rekomendasi)
$level_query = "SELECT level FROM level_student WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $level_query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$level_result = mysqli_stmt_get_result($stmt);
$level_data = mysqli_fetch_assoc($level_result);

// Gunakan level dari tabel level_student (hasil GNN)
// Jika tidak ada, hitung berdasarkan average score
if ($level_data) {
    $final_level = (int)$level_data['level'];
} else {
    // Fallback: tentukan level berdasarkan average score
    if ($average_score >= 85) {
        $final_level = 3;
    } elseif ($average_score >= 50) {
        $final_level = 2;
    } else {
        $final_level = 1;
    }
}

// Tentukan label dan warna berdasarkan final level
if ($final_level == 3) {
    $level_label = 'Tinggi';
    $level_color = 'success';
} elseif ($final_level == 2) {
    $level_label = 'Sedang';
    $level_color = 'warning';
} else {
    $level_label = 'Dasar';
    $level_color = 'info';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pre-Test - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .result-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .header-section {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .header-section::before {
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
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .module-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 5px solid;
            transition: all 0.3s ease;
        }
        
        .module-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .module-card.level-high {
            border-left-color: #38ef7d;
            background: linear-gradient(135deg, rgba(56, 239, 125, 0.05) 0%, white 100%);
        }
        
        .module-card.level-medium {
            border-left-color: #f5576c;
            background: linear-gradient(135deg, rgba(245, 87, 108, 0.05) 0%, white 100%);
        }
        
        .module-card.level-low {
            border-left-color: #00f2fe;
            background: linear-gradient(135deg, rgba(0, 242, 254, 0.05) 0%, white 100%);
        }
        
        .score-badge {
            font-size: 1.5rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: bold;
        }
        
        .progress-custom {
            height: 30px;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .level-badge {
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .recommendation-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .method-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .method-gnn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .method-irt {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .method-hybrid {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-gradient);">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>MyIRT Adaptive Learning
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user me-1"></i><?php echo $_SESSION['name']; ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../sign-out.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Alert Message if redirected from pre-test -->
        <?php if (isset($_SESSION['pretest_message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <?php 
            echo $_SESSION['pretest_message']; 
            unset($_SESSION['pretest_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <!-- Header Section -->
        <div class="header-section">
            <i class="fas fa-trophy fa-4x mb-3" style="opacity: 0.9;"></i>
            <h1 class="display-4 fw-bold mb-3">Hasil Pre-Test</h1>
            <p class="lead mb-0">Berikut adalah hasil pre-test Anda</p>
        </div>

        <!-- Overall Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <i class="fas fa-graduation-cap stat-icon"></i>
                    <div class="stat-value"><?php echo $final_level; ?></div>
                    <div class="stat-label">Level Akhir</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <i class="fas fa-chart-line stat-icon"></i>
                    <div class="stat-value"><?php echo number_format($average_score, 0); ?></div>
                    <div class="stat-label">Rata-rata Score</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <i class="fas fa-check-circle stat-icon"></i>
                    <div class="stat-value"><?php echo $total_correct; ?>/<?php echo $total_questions; ?></div>
                    <div class="stat-label">Jawaban Benar</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <i class="fas fa-percentage stat-icon"></i>
                    <div class="stat-value"><?php echo number_format($overall_percentage, 1); ?>%</div>
                    <div class="stat-label">Persentase</div>
                </div>
            </div>
        </div>

        <!-- Level Badge -->
        <div class="text-center mb-4">
            <span class="level-badge bg-<?php echo $level_color; ?>">
                <i class="fas fa-medal me-2"></i>Level <?php echo $final_level; ?> - <?php echo $level_label; ?>
            </span>
        </div>

        <!-- Hasil Per Modul -->
        <div class="result-card">
            <h3 class="mb-4">
                <i class="fas fa-list-check me-2"></i>Hasil Per Modul
            </h3>
            
            <?php foreach ($modules_data as $module): 
                $score_class = '';
                $level_class = '';
                if ($module['score'] >= 85) {
                    $score_class = 'success';
                    $level_class = 'level-high';
                } elseif ($module['score'] >= 50) {
                    $score_class = 'warning';
                    $level_class = 'level-medium';
                } else {
                    $score_class = 'info';
                    $level_class = 'level-low';
                }
                
                $percentage = $module['total_questions'] > 0 ? 
                    ($module['correct_answers'] / $module['total_questions']) * 100 : 0;
            ?>
            
            <div class="module-card <?php echo $level_class; ?>">
                <div class="row align-items-center">
                    <div class="col-md-1 text-center">
                        <div class="fs-3 fw-bold text-muted">#<?php echo $module['module_number']; ?></div>
                    </div>
                    <div class="col-md-5">
                        <h5 class="mb-2"><?php echo $module['module_desc']; ?></h5>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="badge bg-secondary">
                                <i class="fas fa-layer-group me-1"></i>Level <?php echo $module['module_level']; ?>
                            </span>
                            <?php if ($module['method']): ?>
                                <span class="method-badge method-<?php echo strtolower($module['method']); ?>">
                                    <i class="fas fa-brain me-1"></i><?php echo $module['method']; ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($module['gnn_predicted_level']): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-robot me-1"></i>GNN Level: <?php echo $module['gnn_predicted_level']; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="progress-custom">
                            <div class="progress-bar bg-<?php echo $score_class; ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo $percentage; ?>%"
                                 aria-valuenow="<?php echo $percentage; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo number_format($percentage, 0); ?>%
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-check-circle me-1"></i>
                            <?php echo $module['correct_answers']; ?> dari <?php echo $module['total_questions']; ?> soal benar
                        </small>
                    </div>
                    <div class="col-md-3 text-end">
                        <span class="score-badge badge bg-<?php echo $score_class; ?>">
                            <i class="fas fa-star me-1"></i><?php echo $module['score']; ?>
                        </span>
                        <div class="mt-2">
                            <?php if ($module['gnn_confidence']): ?>
                                <small class="text-muted">
                                    <i class="fas fa-chart-line me-1"></i>
                                    Confidence: <?php echo number_format($module['gnn_confidence'] * 100, 1); ?>%
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php endforeach; ?>
        </div>

        <!-- Recommendation -->
        <div class="recommendation-box">
            <h4 class="mb-3">
                <i class="fas fa-lightbulb me-2"></i>Rekomendasi Belajar
            </h4>
            <div class="row">
                <div class="col-md-8">
                    <p class="mb-2">
                        <i class="fas fa-arrow-right me-2"></i>
                        Anda berada di <strong>Level <?php echo $final_level; ?></strong> dengan rata-rata score <strong><?php echo number_format($average_score, 0); ?></strong>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-arrow-right me-2"></i>
                        <?php if ($average_score >= 85): ?>
                            Excellent! Anda siap untuk materi tingkat lanjut.
                        <?php elseif ($average_score >= 50): ?>
                            Bagus! Anda dapat melanjutkan dengan materi tingkat menengah.
                        <?php else: ?>
                            Fokus pada penguatan materi dasar terlebih dahulu.
                        <?php endif; ?>
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-arrow-right me-2"></i>
                        Sistem adaptive learning akan menyesuaikan materi sesuai kemampuan Anda.
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <a href="modul-rekomendasi.php" class="btn btn-light btn-lg">
                        <i class="fas fa-route me-2"></i>Lihat Rekomendasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4">
            <a href="modul-rekomendasi.php" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-route me-2"></i>Lihat Rekomendasi Modul
            </a>
     
            <a href="index.php" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.module-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });
        });
    </script>
</body>
</html>
