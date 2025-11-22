<?php
/**
 * Halaman Rekomendasi Modul Adaptive
 * Menampilkan urutan modul yang disesuaikan dengan:
 * 1. Hasil Pre-test GNN (kemampuan per modul)
 * 2. Survey Result (minat/preferensi level)
 * 3. Sequence adaptif berdasarkan kemampuan user
 */

session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
    exit;
}

include('../config/db.php');

$student_id = $_SESSION['student_id'];

// Cek apakah sudah ada hasil pretest
$check_query = "SELECT COUNT(*) as count FROM result_hasil_pretest WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$check_result = mysqli_stmt_get_result($stmt);
$check_data = mysqli_fetch_assoc($check_result);

if ($check_data['count'] == 0) {
    header('location: pre-test.php');
    exit;
}

// Ambil hasil pretest per modul dengan GNN prediction
$query = "SELECT 
            rhp.*,
            m.module_desc,
            m.module_level,
            m.number as module_number,
            m.id as module_id
          FROM result_hasil_pretest rhp
          JOIN module m ON m.id = rhp.module_id
          WHERE rhp.student_id = ?
          ORDER BY m.number ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);

$modules_data = [];
while ($row = mysqli_fetch_assoc($results)) {
    $modules_data[] = $row;
}

// Ambil survey result (minat user)
$survey_query = "SELECT level_result FROM survey_result WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $survey_query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$survey_result = mysqli_stmt_get_result($stmt);
$survey_data = mysqli_fetch_assoc($survey_result);
$user_interest_level = $survey_data ? $survey_data['level_result'] : 2;

// Ambil level student dari level_student
$level_query = "SELECT level FROM level_student WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $level_query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$level_result = mysqli_stmt_get_result($stmt);
$level_data = mysqli_fetch_assoc($level_result);
$student_level = $level_data ? $level_data['level'] : 2;

// ========================================
// ALGORITMA REKOMENDASI SEQUENCE MODUL
// ========================================

/**
 * Strategi Rekomendasi:
 * 1. Modul dengan score rendah (0-50) → Prioritas tinggi (harus dikuasai dulu)
 * 2. Modul dengan score sedang (50-85) → Prioritas menengah (perlu penguatan)
 * 3. Modul dengan score tinggi (85-100) → Prioritas rendah (sudah dikuasai)
 * 4. Urutkan berdasarkan: score rendah → level rendah → minat user
 */

function generateModuleSequence($modules, $user_interest, $student_level) {
    $weak_modules = [];      // Score 0-50
    $medium_modules = [];    // Score 50-85
    $strong_modules = [];    // Score 85-100
    
    foreach ($modules as $module) {
        $module['priority_score'] = calculatePriority($module, $user_interest, $student_level);
        
        if ($module['score'] < 50) {
            $weak_modules[] = $module;
        } elseif ($module['score'] < 85) {
            $medium_modules[] = $module;
        } else {
            $strong_modules[] = $module;
        }
    }
    
    // Sort setiap kategori berdasarkan priority score
    usort($weak_modules, function($a, $b) {
        return $b['priority_score'] <=> $a['priority_score'];
    });
    
    usort($medium_modules, function($a, $b) {
        return $b['priority_score'] <=> $a['priority_score'];
    });
    
    usort($strong_modules, function($a, $b) {
        return $a['module_level'] <=> $b['module_level'];
    });
    
    // Gabungkan: weak → medium → strong
    return array_merge($weak_modules, $medium_modules, $strong_modules);
}

/**
 * Hitung priority score untuk modul
 * Semakin tinggi score, semakin prioritas
 */
function calculatePriority($module, $user_interest, $student_level) {
    $score = 0;
    
    // 1. Score rendah = prioritas tinggi (inverse)
    $score += (100 - $module['score']) * 2;
    
    // 2. Level modul sesuai dengan kemampuan user
    $level_match = 3 - abs($module['module_level'] - $student_level);
    $score += $level_match * 15;
    
    // 3. Sesuai dengan minat dari survey
    $interest_match = 3 - abs($module['module_level'] - $user_interest);
    $score += $interest_match * 10;
    
    // 4. GNN confidence (jika ada)
    if (isset($module['gnn_confidence']) && $module['gnn_confidence'] > 0) {
        $score += $module['gnn_confidence'] * 20;
    }
    
    // 5. Recommended level dari GNN
    if (isset($module['recommended_level'])) {
        if ($module['recommended_level'] <= $student_level) {
            $score += 25; // Modul yang direkomendasikan GNN untuk level user
        }
    }
    
    return $score;
}

// Generate sequence
$recommended_sequence = generateModuleSequence($modules_data, $user_interest_level, $student_level);

// Hitung statistik
$total_modules = count($recommended_sequence);
$weak_count = count(array_filter($recommended_sequence, function($m) { return $m['score'] < 50; }));
$medium_count = count(array_filter($recommended_sequence, function($m) { return $m['score'] >= 50 && $m['score'] < 85; }));
$strong_count = count(array_filter($recommended_sequence, function($m) { return $m['score'] >= 85; }));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Modul Adaptive - MyIRT Learning</title>
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
        
        .hero-section {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
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
        
        .stats-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-box {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .sequence-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .module-item {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 5px solid;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .module-item:hover {
            transform: translateX(10px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .module-item.priority-high {
            border-left-color: #ff4444;
            background: linear-gradient(135deg, rgba(255, 68, 68, 0.05) 0%, white 100%);
        }
        
        .module-item.priority-medium {
            border-left-color: #ffbb33;
            background: linear-gradient(135deg, rgba(255, 187, 51, 0.05) 0%, white 100%);
        }
        
        .module-item.priority-low {
            border-left-color: #00C851;
            background: linear-gradient(135deg, rgba(0, 200, 81, 0.05) 0%, white 100%);
        }
        
        .sequence-number {
            position: absolute;
            top: 1rem;
            left: 1rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .module-content {
            padding-left: 50px;
        }
        
        .priority-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
        .priority-high-badge {
            background: #ff4444;
            color: white;
        }
        
        .priority-medium-badge {
            background: #ffbb33;
            color: white;
        }
        
        .priority-low-badge {
            background: #00C851;
            color: white;
        }
        
        .recommendation-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .action-btn {
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-start-learning {
            background: var(--success-gradient);
            border: none;
            color: white;
        }
        
        .btn-start-learning:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .score-progress {
            height: 8px;
            border-radius: 10px;
            background: #e0e0e0;
            overflow: hidden;
            margin: 0.5rem 0;
        }
        
        .score-progress-bar {
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        
        .info-icon {
            cursor: help;
            color: #667eea;
        }
        
        @media (max-width: 768px) {
            .stats-container {
                flex-direction: column;
            }
            
            .module-content {
                padding-left: 0;
                padding-top: 50px;
            }
            
            .sequence-number {
                left: 50%;
                transform: translateX(-50%);
            }
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
                        <a class="nav-link" href="hasil-pretest.php">
                            <i class="fas fa-chart-line me-1"></i>Hasil Pre-test
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

    <div class="container py-4">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="fas fa-route me-3"></i>Jalur Pembelajaran Adaptive Anda
                    </h1>
                    <p class="lead mb-0">
                        Urutan modul yang dipersonalisasi berdasarkan kemampuan dan minat Anda
                    </p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-brain fa-5x" style="opacity: 0.8;"></i>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-container">
            <div class="stat-box">
                <i class="fas fa-layer-group stat-icon" style="color: #667eea;"></i>
                <div class="stat-value"><?php echo $student_level; ?></div>
                <div class="stat-label">Level Anda</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-heart stat-icon" style="color: #f093fb;"></i>
                <div class="stat-value"><?php echo $user_interest_level; ?></div>
                <div class="stat-label">Minat Level</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-exclamation-circle stat-icon" style="color: #ff4444;"></i>
                <div class="stat-value"><?php echo $weak_count; ?></div>
                <div class="stat-label">Perlu Penguatan</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-check-circle stat-icon" style="color: #00C851;"></i>
                <div class="stat-value"><?php echo $strong_count; ?></div>
                <div class="stat-label">Sudah Dikuasai</div>
            </div>
        </div>

        <!-- Recommendation Info -->
        <div class="recommendation-box">
            <h5 class="mb-3">
                <i class="fas fa-lightbulb me-2"></i>Bagaimana Rekomendasi Ini Dibuat?
            </h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-start mb-2">
                        <i class="fas fa-brain me-2 mt-1"></i>
                        <div>
                            <strong>GNN Prediction</strong><br>
                            <small>Analisis kemampuan Anda per modul menggunakan Graph Neural Network</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start mb-2">
                        <i class="fas fa-poll me-2 mt-1"></i>
                        <div>
                            <strong>Survey Minat</strong><br>
                            <small>Preferensi tingkat kesulitan yang Anda pilih</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start mb-2">
                        <i class="fas fa-sort-amount-up me-2 mt-1"></i>
                        <div>
                            <strong>Adaptive Sequence</strong><br>
                            <small>Urutan optimal dari materi yang perlu dipelajari</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Sequence -->
        <div class="sequence-card">
            <h3 class="mb-4">
                <i class="fas fa-list-ol me-2"></i>Urutan Modul yang Direkomendasikan
            </h3>

            <?php foreach ($recommended_sequence as $index => $module): 
                $priority_class = '';
                $priority_badge_class = '';
                $priority_text = '';
                $priority_icon = '';
                
                if ($module['score'] < 50) {
                    $priority_class = 'priority-high';
                    $priority_badge_class = 'priority-high-badge';
                    $priority_text = 'Prioritas Tinggi';
                    $priority_icon = 'fa-exclamation-circle';
                } elseif ($module['score'] < 85) {
                    $priority_class = 'priority-medium';
                    $priority_badge_class = 'priority-medium-badge';
                    $priority_text = 'Prioritas Sedang';
                    $priority_icon = 'fa-info-circle';
                } else {
                    $priority_class = 'priority-low';
                    $priority_badge_class = 'priority-low-badge';
                    $priority_text = 'Sudah Bagus';
                    $priority_icon = 'fa-check-circle';
                }
                
                $score_color = '';
                if ($module['score'] >= 85) {
                    $score_color = '#00C851';
                } elseif ($module['score'] >= 50) {
                    $score_color = '#ffbb33';
                } else {
                    $score_color = '#ff4444';
                }
            ?>
            
            <div class="module-item <?php echo $priority_class; ?>">
                <div class="sequence-number"><?php echo $index + 1; ?></div>
                <div class="module-content">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="mb-2"><?php echo $module['module_desc']; ?></h5>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="priority-badge <?php echo $priority_badge_class; ?>">
                                    <i class="fas <?php echo $priority_icon; ?> me-1"></i><?php echo $priority_text; ?>
                                </span>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-layer-group me-1"></i>Level <?php echo $module['module_level']; ?>
                                </span>
                                <?php if (isset($module['recommended_level'])): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-robot me-1"></i>GNN: Level <?php echo $module['recommended_level']; ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-bold" style="color: <?php echo $score_color; ?>">
                                <?php echo $module['score']; ?>
                            </div>
                            <small class="text-muted">Score</small>
                        </div>
                    </div>
                    
                    <div class="score-progress">
                        <div class="score-progress-bar" 
                             style="width: <?php echo $module['score']; ?>%; background: <?php echo $score_color; ?>">
                        </div>
                    </div>
                    
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-check-circle me-1"></i>
                            Benar: <?php echo $module['correct_answers']; ?>/<?php echo $module['total_questions']; ?>
                            <?php if (isset($module['gnn_confidence']) && $module['gnn_confidence'] > 0): ?>
                                | <i class="fas fa-chart-line me-1"></i>
                                Confidence: <?php echo number_format($module['gnn_confidence'] * 100, 1); ?>%
                            <?php endif; ?>
                        </small>
                    </div>
                    
                    <div class="mt-3">
                        <a href="module.php?module=<?php echo $module['module_id']; ?>" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-book-open me-1"></i>Mulai Belajar Modul Ini
                        </a>
                    </div>
                </div>
            </div>
            
            <?php endforeach; ?>
        </div>

        <!-- Action Buttons -->
        <div class="text-center">
          
            <a href="hasil-pretest.php" class="btn btn-outline-secondary action-btn ms-2">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Hasil
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.module-item');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    item.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });
        });
    </script>
</body>
</html>
