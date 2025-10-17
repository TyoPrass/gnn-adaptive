<?php
include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// ✅ PERBAIKAN: Mengambil topic_id dari URL
if (!isset($_GET['topic_id']) || empty($_GET['topic_id'])) {
    echo "<script>
        alert('Parameter topic_id tidak ditemukan!');
        window.location.href = 'index.php';
    </script>";
    exit();
}

$topic_id = mysqli_real_escape_string($conn, $_GET['topic_id']);

// ✅ Mengambil data topik dari database
$sql = "SELECT * FROM topic WHERE id = '{$topic_id}'";
$query = mysqli_query($conn, $sql);

if (!$query || mysqli_num_rows($query) == 0) {
    echo "<script>
        alert('Data topik tidak ditemukan!');
        window.location.href = 'index.php';
    </script>";
    exit();
}

$topic = mysqli_fetch_array($query, MYSQLI_ASSOC);

// ✅ Mengambil data subtopik berdasarkan topic_id
$sql_subtopik = "SELECT * FROM sub_topic WHERE topic_id = '{$topic_id}' ORDER BY id ASC";
$query_subtopik = mysqli_query($conn, $sql_subtopik);
$subtopics = mysqli_fetch_all($query_subtopik, MYSQLI_ASSOC);

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Subtopik <?php echo htmlspecialchars($topic['topic_desc']); ?> - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --info-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --hover-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .page-header {
            background: var(--primary-gradient);
            color: white;
            padding: 4rem 0 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: headerFloat 15s ease-in-out infinite;
        }
        
        @keyframes headerFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }
        
        .breadcrumb-nav {
            font-size: 0.95rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .breadcrumb-nav a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb-nav a:hover {
            color: white;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 10;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 10;
        }
        
        .header-actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .stats-cards {
            margin-top: 2rem;
            position: relative;
            z-index: 10;
        }

        .stat-card {
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            padding: 1.5rem;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
            height: 100%;
            min-height: 100px;
        }

        .stat-card:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.3);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .stat-icon.topic-icon {
            background: var(--success-gradient);
        }

        .stat-icon.progress-icon {
            background: var(--secondary-gradient);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-content {
            flex: 1;
            text-align: left;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
            color: white;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.9);
            font-weight: 500;
        }

        .progress-card .stat-number {
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .back-button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 0.8rem 1.8rem;
            border-radius: 30px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .back-button i {
            transition: transform 0.3s ease;
        }

        .back-button:hover i {
            transform: translateX(-3px);
        }

        /* ✅ RESPONSIVE IMPROVEMENTS */
        @media (max-width: 768px) {
            .page-header {
                padding: 3rem 0 1.5rem;
                text-align: left;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .subtopik-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .subtopik-card {
                padding: 1.5rem;
            }
            
            .stats-cards {
                margin-top: 1.5rem;
            }
            
            .header-actions {
                justify-content: center;
                margin-bottom: 1.5rem;
            }
            
            .stat-card {
                padding: 1.2rem;
                min-height: 80px;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .stat-label {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .back-button {
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
        }
        
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .subtopik-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .subtopik-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .subtopik-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-gradient);
        }
        
        .subtopik-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--hover-shadow);
        }
        
        .subtopik-icon {
            width: 60px;
            height: 60px;
            background: var(--info-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 1.5rem;
            position: relative;
        }
        
        .subtopik-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--info-gradient);
            opacity: 0.3;
            transform: scale(0);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(0); opacity: 0.3; }
            50% { transform: scale(1.2); opacity: 0.1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        
        .subtopik-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .subtopik-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .subtopik-meta .badge {
            background: var(--success-gradient);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .subtopik-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
        
        .btn-view {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .back-button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            font-weight: 500;
        }
        
        .back-button:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            color: white;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 3rem 0 1.5rem;
                text-align: center;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .subtopik-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .subtopik-card {
                padding: 1.5rem;
            }
            
            .stats-cards {
                margin-top: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-brain me-2"></i>MyIRT Learning
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
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb-nav">
                <i class="fas fa-home me-2"></i>
                <a href="index.php">Dashboard</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span>Subtopik</span>
            </div>
            
            <!-- ✅ TOMBOL KEMBALI DI KIRI ATAS -->
            <div class="header-actions mb-3">
                <a href="index.php" class="back-button">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
            
            <h1 class="page-title">
                <i class="fas fa-layer-group me-3"></i>
                <?php echo htmlspecialchars($topic['topic_desc']); ?>
            </h1>
            <p class="page-subtitle">
                Jelajahi subtopik pembelajaran untuk memperdalam pemahaman Anda
            </p>
            
            <!-- ✅ STATS CARDS YANG DIPERBAIKI -->
            <div class="stats-cards">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number"><?php echo count($subtopics); ?></div>
                                <div class="stat-label">Total Subtopik</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="stat-card">
                            <div class="stat-icon topic-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number"><?php echo htmlspecialchars($topic['topic_desc']); ?></div>
                                <div class="stat-label">Topik Utama</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="stat-card progress-card">
                            <div class="stat-icon progress-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">100%</div>
                                <div class="stat-label">Tersedia untuk Dipelajari</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="content-container">
        <?php if (count($subtopics) > 0): ?>
            <div class="subtopik-grid">
                <?php foreach ($subtopics as $index => $subtopik): ?>
                    <div class="subtopik-card">
                        <div class="subtopik-icon">
                            <i class="fas fa-bookmark"></i>
                        </div>
                        
                        <h3 class="subtopik-title">
                            <?php echo htmlspecialchars($subtopik['sub_topic_desc']); ?>
                        </h3>
                        
                        <div class="subtopik-meta">
                            <span class="badge">
                                <i class="fas fa-list me-1"></i>
                                Subtopik #<?php echo $index + 1; ?>
                            </span>
                            <span>
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('d M Y'); ?>
                            </span>
                        </div>
                        
                        <div class="subtopik-actions">
                            <a href="modul.php?subtopik=<?php echo $subtopik['id']; ?>" class="btn-view">
                                <i class="fas fa-eye me-2"></i>
                                Lihat Modul
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>Belum Ada Subtopik</h3>
                <p>Subtopik untuk topik "<?php echo htmlspecialchars($topic['topic_desc']); ?>" belum tersedia.</p>
                <a href="index.php" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.subtopik-card');
            
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>