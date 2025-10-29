<?php
include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// ✅ VALIDASI PRE-TEST - Cek apakah siswa sudah mengerjakan pre-test dan hasilnya sudah keluar
$student_id = $_SESSION['student_id'];

// Cek apakah siswa sudah mengerjakan pre-test
$check_pretest_answer = mysqli_query($conn, "SELECT * FROM pre_test_answer WHERE student_id = '{$student_id}'");
$has_done_pretest = mysqli_num_rows($check_pretest_answer) > 0;

// Cek apakah hasil pre-test sudah diproses (ada di tabel level_student)
$check_pretest_result = mysqli_query($conn, "SELECT * FROM level_student WHERE student_id = '{$student_id}'");
$pretest_result_ready = mysqli_num_rows($check_pretest_result) > 0;

// Jika belum mengerjakan pre-test ATAU hasil belum keluar, redirect ke halaman info
if (!$has_done_pretest || !$pretest_result_ready) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Akses Ditolak - Pre-Test Diperlukan</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .error-container {
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                padding: 3rem;
                max-width: 650px;
                width: 100%;
                text-align: center;
                animation: slideUp 0.5s ease-out;
            }
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .error-icon {
                font-size: 80px;
                margin-bottom: 20px;
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }
            .error-title {
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 15px;
                color: #333;
            }
            .error-message {
                font-size: 1.1rem;
                color: #666;
                margin-bottom: 25px;
                line-height: 1.6;
            }
            .info-box {
                background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
                border-left: 4px solid #667eea;
                padding: 20px;
                border-radius: 10px;
                margin: 25px 0;
                text-align: left;
            }
            .info-box h5 {
                color: #667eea;
                font-weight: 600;
                margin-bottom: 12px;
            }
            .info-box ul {
                margin: 0;
                padding-left: 20px;
                color: #555;
            }
            .info-box li {
                margin-bottom: 8px;
            }
            .btn-action {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                color: white;
                padding: 12px 30px;
                border-radius: 10px;
                font-weight: 600;
                text-decoration: none;
                display: inline-block;
                margin: 5px;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            }
            .btn-action:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
                color: white;
            }
            .status-badge {
                background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
                color: #2d3436;
                padding: 8px 20px;
                border-radius: 20px;
                font-size: 0.9rem;
                font-weight: 600;
                display: inline-block;
                margin: 15px 0;
            }
            .user-info {
                background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="user-info">
                <i class="fas fa-user-circle me-2"></i>
                Hai, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>
            </div>
            
            <div class="error-icon">
                <?php if (!$has_done_pretest) { ?>
                    <i class="fas fa-exclamation-triangle" style="color: #e74c3c;"></i>
                <?php } else { ?>
                    <i class="fas fa-hourglass-half" style="color: #f39c12;"></i>
                <?php } ?>
            </div>
            
            <?php if (!$has_done_pretest) { ?>
                <!-- Siswa belum mengerjakan pre-test -->
                <h2 class="error-title">
                    <i class="fas fa-clipboard-check me-2" style="color: #e74c3c;"></i>
                    Pre-Test Belum Dikerjakan
                </h2>
                
                <p class="error-message">
                    Anda <strong>belum mengerjakan pre-test</strong> yang diperlukan untuk mengakses modul pembelajaran.
                </p>
                
                <div class="status-badge" style="background: linear-gradient(135deg, #ff7675 0%, #d63031 100%); color: white;">
                    <i class="fas fa-times-circle me-2"></i>Pre-Test: Belum Dikerjakan
                </div>
                
                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2"></i>Mengapa Pre-Test Penting?</h5>
                    <ul>
                        <li><strong>Menentukan level pembelajaran</strong> yang sesuai dengan kemampuan Anda</li>
                        <li>Membantu sistem memberikan <strong>materi yang tepat</strong></li>
                        <li>Pre-test hanya dilakukan <strong>satu kali</strong> di awal</li>
                        <li>Setelah selesai, Anda bisa langsung mengakses semua modul</li>
                    </ul>
                </div>
                
                <div class="mt-4">
                    <a href="pre-test.php" class="btn-action">
                        <i class="fas fa-clipboard-check me-2"></i>Kerjakan Pre-Test Sekarang
                    </a>
                    <a href="index.php" class="btn-action" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
                
            <?php } else { ?>
                <!-- Siswa sudah mengerjakan pre-test tapi hasilnya belum keluar -->
                <h2 class="error-title">
                    <i class="fas fa-clock me-2" style="color: #f39c12;"></i>
                    Hasil Pre-Test Sedang Diproses
                </h2>
                
                <p class="error-message">
                    Anda sudah mengerjakan pre-test, namun <strong>hasilnya sedang dalam proses perhitungan</strong> oleh sistem.
                </p>
                
                <div class="status-badge">
                    <i class="fas fa-spinner fa-spin me-2"></i>Status: Menunggu Hasil
                </div>
                
                <div class="info-box">
                    <h5><i class="fas fa-lightbulb me-2"></i>Informasi Penting:</h5>
                    <ul>
                        <li>Pre-test Anda <strong>sudah diterima</strong> oleh sistem</li>
                        <li>Sistem sedang <strong>menghitung level pembelajaran</strong> Anda</li>
                        <li>Proses ini biasanya memakan waktu <strong>beberapa saat</strong></li>
                        <li>Anda <strong>belum dapat mengakses modul</strong> sampai hasil pre-test keluar</li>
                        <li>Hasil akan otomatis tersedia di dashboard Anda</li>
                    </ul>
                </div>
                
                <p style="color: #888; margin-top: 20px;">
                    <i class="fas fa-info-circle me-2" style="color: #17a2b8;"></i>
                    <strong>Catatan:</strong> Jika hasil tidak keluar dalam waktu lama, silakan hubungi administrator
                </p>
                
                <div class="mt-4">
                    <a href="index.php" class="btn-action">
                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                    </a>
                    <a href="../sign-out.php" class="btn-action" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            <?php } ?>
            
            <hr style="margin: 30px 0; opacity: 0.3;">
            
            <p style="color: #999; font-size: 0.9rem; margin-top: 20px;">
                <i class="fas fa-phone-alt me-2"></i>
                Butuh bantuan? Hubungi administrator atau guru Anda
            </p>
        </div>
    </body>
    </html>
    <?php
    exit(); // Stop eksekusi script
}

// ✅ PENANGANAN PARAMETER SUBTOPIK
$subtopik_id = null;
$subtopik_data = null;

if (isset($_GET['subtopik'])) {
    $use_subtopik = true;
    $use_topik = false;

    $subtopik_id = mysqli_real_escape_string($conn, $_GET['subtopik']);
    
    // ✅ Ambil data subtopik untuk display
    $sql_subtopik = "SELECT st.*, t.topic_desc FROM sub_topic st 
                     JOIN topic t ON st.topic_id = t.id 
                     WHERE st.id = '{$subtopik_id}'";
    $query_subtopik = mysqli_query($conn, $sql_subtopik);
    
    if (!$query_subtopik || mysqli_num_rows($query_subtopik) == 0) {
        echo "<script>
            alert('Data subtopik tidak ditemukan!');
            window.location.href = 'index.php';
        </script>";
        exit();
    }
    
    $subtopik_data = mysqli_fetch_array($query_subtopik, MYSQLI_ASSOC);
} elseif (isset($_GET['topic_id'])) {
    $use_topik = true;
    $use_subtopik = false;
    
    $topik_id = mysqli_real_escape_string($conn, $_GET['topic_id']);
    
    // ✅ Ambil data topik untuk display
    $sql_topik = "SELECT * FROM topic WHERE id = '{$topik_id}'";
    $query_topik = mysqli_query($conn, $sql_topik);
    
    if (!$query_topik || mysqli_num_rows($query_topik) == 0) {
        echo "<script>
            alert('Data topik tidak ditemukan!');
            window.location.href = 'index.php';
        </script>";
        exit();
    }
    
    $topik_data = mysqli_fetch_array($query_topik, MYSQLI_ASSOC);
    
    // ✅ Ambil semua subtopik dalam topik ini
    $sql_subtopik_list = "SELECT * FROM sub_topic WHERE topic_id = '{$topik_id}' ORDER BY id ASC";
    $query_subtopik_list = mysqli_query($conn, $sql_subtopik_list);
    $subtopik_list = mysqli_fetch_all($query_subtopik_list, MYSQLI_ASSOC);
    
} else {
    $use_subtopik = false;
    $use_topik = false;
}

// Logic untuk pre-test dan survey (tetap sama seperti sebelumnya)
if (!$use_subtopik && !$use_topik) {
    // mengambil data jawaban pre_test dari murid
    $pre_test = mysqli_query($conn, "SELECT * FROM pre_test_answer where student_id = '{$_SESSION['student_id']}'");
    $pre_test_row = mysqli_num_rows($pre_test);

    // mengambil data hasil survey dari murid
    $survey = mysqli_query($conn, "SELECT * FROM survey_result where student_id = '{$_SESSION['student_id']}'");
    $survey_row = mysqli_num_rows($survey);

    // mengecek jika murid sudah mengambil pre test dan survey
    if ($pre_test_row > 0 || $survey_row > 0) {
        // mengambil data level murid dari database
        $query = mysqli_query($conn, "SELECT * FROM level_student where student_id = '{$_SESSION['student_id']}'");
        $_SESSION['survey_taken'] = true;
        // jika data ada maka tes sudah di proses
        if (mysqli_num_rows($query) > 0) {
            $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
            $level_user = $result['level'];
            // setting level modul sesuai level user
            if ($level_user == 1) {
                $level_modul = [1, 2, 3];
            } else if ($level_user == 2) {
                $level_modul = [2, 3, 1];
            } else {
                $level_modul = [3, 1, 2];
            }
            $_SESSION['test_processed'] = true;
        } else {
            // pre test belum diproses
            $_SESSION['test_processed'] = false;
        }
    } else {
        $_SESSION['survey_taken'] = false;
    }
} else {
    // Jika menggunakan subtopik atau topik, set default values
    $_SESSION['survey_taken'] = true;
    $_SESSION['test_processed'] = true;
    $level_modul = [1, 2, 3]; // Tampilkan semua level untuk subtopik/topik
}

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>
        <?php 
        if ($use_subtopik && $subtopik_data) {
            echo "Modul " . htmlspecialchars($subtopik_data['sub_topic_desc']) . " - MyIRT Adaptive Learning";
        } elseif ($use_topik && $topik_data) {
            echo "Modul " . htmlspecialchars($topik_data['topic_desc']) . " - MyIRT Adaptive Learning";
        } else {
            echo "Modul Pembelajaran Biologi - MyIRT Adaptive Learning";
        }
        ?>
    </title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --modul-primary: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --modul-secondary: linear-gradient(135deg, #FF8800 0%, #FF6600 100%);
            --modul-success: linear-gradient(135deg, #00bb2d 0%, #009688 100%);
            --modul-info: linear-gradient(135deg, #33b5e5 0%, #0099cc 100%);
            --modul-warning: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --modul-danger: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
            --modul-locked: linear-gradient(135deg, #666666 0%, #999999 100%);
        }
        
        body {
            background: linear-gradient(135deg, #E8F5E8 0%, #F0FFF0 50%, #E0FFE0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .modul-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .modul-header {
            background: var(--modul-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .modul-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: modulFloat 12s ease-in-out infinite;
        }
        
        @keyframes modulFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-10deg); }
        }
        
        .breadcrumb-nav {
            background: rgba(255,255,255,0.1);
            border-radius: 25px;
            padding: 0.8rem 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            display: inline-flex;
            align-items: center;
        }
        
        .breadcrumb-nav a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb-nav a:hover {
            color: white;
        }
        
        .level-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 3rem;
            border-left: 5px solid #00C851;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        
        .level-title {
            color: #2E7D32;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
        }
        
        .level-badge {
            background: var(--modul-primary);
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }
        
        .module-item {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid transparent;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .module-item:hover {
            border-color: rgba(0, 200, 81, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            text-decoration: none;
        }
        
        .module-item.available {
            border-color: #00C851;
            background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);
        }
        
        .module-item.completed {
            background: var(--modul-success);
            color: white;
            border-color: #009688;
        }
        
        .module-item.locked {
            background: var(--modul-locked);
            color: white;
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .module-item.locked:hover {
            transform: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .module-number {
            background: var(--modul-primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        
        .module-item.completed .module-number {
            background: rgba(255,255,255,0.3);
        }
        
        .module-item.locked .module-number {
            background: rgba(255,255,255,0.2);
        }
        
        .module-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #2E7D32;
        }
        
        .module-item.completed .module-title,
        .module-item.locked .module-title {
            color: white;
        }
        
        .module-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .module-item.completed .module-description,
        .module-item.locked .module-description {
            color: rgba(255,255,255,0.9);
        }
        
        .module-status {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
        }
        
        .subtopik-info {
            background: var(--modul-info);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .subtopik-info::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: infoFloat 8s ease-in-out infinite;
        }
        
        @keyframes infoFloat {
            0%, 100% { opacity: 0.1; transform: scale(1) rotate(0deg); }
            50% { opacity: 0.3; transform: scale(1.05) rotate(5deg); }
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
        
        @media (max-width: 768px) {
            .modul-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            
            .level-section {
                padding: 1.5rem;
            }
            
            .module-item {
                padding: 1rem;
            }
            
            .floating-icons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--modul-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
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
                    <div class="modul-card">
                        <div class="modul-header">
                            <div class="floating-icons icon1">
                                <i class="fas fa-dna"></i>
                            </div>
                            <div class="floating-icons icon2">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <div class="floating-icons icon3">
                                <i class="fas fa-seedling"></i>
                            </div>
                            
                            <!-- ✅ BREADCRUMB NAVIGATION -->
                            <div class="breadcrumb-nav">
                                <i class="fas fa-home me-2"></i>
                                <a href="index.php">Dashboard</a>
                                <i class="fas fa-chevron-right mx-2"></i>
                                <?php if ($use_subtopik && $subtopik_data): ?>
                                    <a href="subtopik.php?topic_id=<?php echo $subtopik_data['topic_id']; ?>">Subtopik</a>
                                    <i class="fas fa-chevron-right mx-2"></i>
                                    <span><?php echo htmlspecialchars($subtopik_data['sub_topic_desc']); ?></span>
                                <?php elseif ($use_topik && $topik_data): ?>
                                    <span><?php echo htmlspecialchars($topik_data['topic_desc']); ?></span>
                                <?php else: ?>
                                    <span>Modul Adaptif</span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- ✅ BACK BUTTON -->
                            <div class="mb-3">
                                <?php if ($use_subtopik && $subtopik_data): ?>
                                    <a href="subtopik.php?topic_id=<?php echo $subtopik_data['topic_id']; ?>" class="back-button">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Subtopik
                                    </a>
                                <?php elseif ($use_topik && $topik_data): ?>
                                    <a href="index.php" class="back-button">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                                    </a>
                                <?php else: ?>
                                    <a href="index.php" class="back-button">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="display-5 fw-bold mb-3">
                                        <i class="fas fa-books me-3"></i>
                                        <?php 
                                        if ($use_subtopik && $subtopik_data) {
                                            echo "Modul " . htmlspecialchars($subtopik_data['sub_topic_desc']);
                                        } elseif ($use_topik && $topik_data) {
                                            echo "Modul " . htmlspecialchars($topik_data['topic_desc']);
                                        } else {
                                            echo "Rekomendasi Modul Biologi";
                                        }
                                        ?>
                                    </h1>
                                    <p class="lead mb-3">
                                        <?php 
                                        if ($use_subtopik && $subtopik_data) {
                                            echo "Pelajari modul-modul dalam subtopik " . htmlspecialchars($subtopik_data['sub_topic_desc']);
                                        } elseif ($use_topik && $topik_data) {
                                            echo "Pelajari semua modul dalam topik " . htmlspecialchars($topik_data['topic_desc']);
                                        } else {
                                            echo "Pembelajaran adaptif disesuaikan dengan tingkat kemampuan dan gaya belajar Anda";
                                        }
                                        ?>
                                    </p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <?php if ($use_subtopik && $subtopik_data): ?>
                                            <span class="badge bg-light text-dark p-2">
                                                <i class="fas fa-bookmark me-1"></i><?php echo htmlspecialchars($subtopik_data['topic_desc']); ?>
                                            </span>
                                            <span class="badge bg-light text-dark p-2">
                                                <i class="fas fa-layer-group me-1"></i>Subtopik Focused
                                            </span>
                                        <?php elseif ($use_topik && $topik_data): ?>
                                            <span class="badge bg-light text-dark p-2">
                                                <i class="fas fa-book me-1"></i><?php echo htmlspecialchars($topik_data['topic_desc']); ?>
                                            </span>
                                            <span class="badge bg-light text-dark p-2">
                                                <i class="fas fa-sitemap me-1"></i>Topik Focused
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark p-2">
                                                <i class="fas fa-brain me-1"></i>Pembelajaran Adaptif
                                            </span>
                                            <span class="badge bg-light text-dark p-2">
                                                <i class="fas fa-chart-line me-1"></i>Progress Tracking
                                            </span>
                                            <span class="badge bg-light text-dark p-2">
                                                <i class="fas fa-award me-1"></i>Level Based
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-graduation-cap" style="font-size: 6rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="row">
                <div class="col-12">
                    <?php if ($use_subtopik && $subtopik_data){ ?>
                        <!-- ✅ SUBTOPIK INFO SECTION -->
                        <div class="subtopik-info">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h3 class="mb-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Tentang Subtopik Ini
                                    </h3>
                                    <p class="mb-2">
                                        <strong>Topik:</strong> <?php echo htmlspecialchars($subtopik_data['topic_desc']); ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Subtopik:</strong> <?php echo htmlspecialchars($subtopik_data['sub_topic_desc']); ?>
                                    </p>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-book-reader fa-4x" style="opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ✅ MODUL SECTION UNTUK SUBTOPIK -->
                        <div class="level-section">
                            <div class="level-title">
                                <div class="level-badge">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <span>Modul dalam Subtopik Ini</span>
                            </div>
                            
                            <div class="row">
                                <?php
                                // ✅ QUERY MODUL BERDASARKAN SUBTOPIK
                                $sql_modul = "SELECT * FROM module WHERE sub_topic_id = '{$subtopik_id}' ORDER BY number ASC";
                                $query_modul = mysqli_query($conn, $sql_modul);
                                
                                if (mysqli_num_rows($query_modul) > 0) {
                                    $modules = mysqli_fetch_all($query_modul, MYSQLI_ASSOC);
                                    
                                    foreach ($modules as $key => $module) {
                                        // Cek apakah modul sudah dipelajari (jika user sudah login)
                                        $module_learned = false;
                                        if (isset($_SESSION['student_id'])) {
                                            $sql_learned = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$module['id']}'";
                                            $query_learned = mysqli_query($conn, $sql_learned);
                                            $module_learned = mysqli_num_rows($query_learned) > 0;
                                        }
                                        
                                        $status_class = $module_learned ? 'completed' : 'available';
                                        $status_icon = $module_learned ? 'fas fa-check-circle' : 'fas fa-play-circle';
                                ?>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <a href="module.php?module=<?php echo $module['id']; ?>" 
                                       class="module-item <?php echo $status_class; ?>">
                                       
                                        <div class="module-status">
                                            <i class="<?php echo $status_icon; ?>"></i>
                                        </div>
                                        
                                        <div class="module-number">
                                            <?php echo $module['number']; ?>
                                        </div>
                                        
                                        <div class="module-title">
                                            Modul <?php echo $module['number']; ?>
                                        </div>
                                        
                                        <div class="module-description">
                                            <?php echo htmlspecialchars($module['module_desc']); ?>
                                        </div>
                                    </a>
                                </div>
                                <?php 
                                    }
                                } else {
                                ?>
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-folder-open fa-5x mb-3" style="opacity: 0.3;"></i>
                                        <h4>Belum Ada Modul</h4>
                                        <p class="text-muted">Modul untuk subtopik ini belum tersedia.</p>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                    <?php } elseif ($use_topik && $topik_data) { ?>
                        <!-- ✅ TOPIK INFO SECTION -->
                        <div class="subtopik-info">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h3 class="mb-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Tentang Topik Ini
                                    </h3>
                                    <p class="mb-2">
                                        <strong>Topik:</strong> <?php echo htmlspecialchars($topik_data['topic_desc']); ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Total Subtopik:</strong> <?php echo count($subtopik_list); ?> subtopik
                                    </p>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-sitemap fa-4x" style="opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ✅ MODUL SECTION UNTUK TOPIK (GROUPED BY SUBTOPIK) -->
                        <?php if (count($subtopik_list) > 0): ?>
                            <?php foreach ($subtopik_list as $subtopik): ?>
                                <div class="level-section">
                                    <div class="level-title">
                                        <div class="level-badge">
                                            <i class="fas fa-bookmark"></i>
                                        </div>
                                        <span><?php echo htmlspecialchars($subtopik['sub_topic_desc']); ?></span>
                                    </div>
                                    
                                    <div class="row">
                                        <?php
                                        // ✅ QUERY MODUL BERDASARKAN SUBTOPIK
                                        $sql_modul = "SELECT * FROM module WHERE sub_topic_id = '{$subtopik['id']}' ORDER BY number ASC";
                                        $query_modul = mysqli_query($conn, $sql_modul);
                                        
                                        if (mysqli_num_rows($query_modul) > 0) {
                                            $modules = mysqli_fetch_all($query_modul, MYSQLI_ASSOC);
                                            
                                            foreach ($modules as $key => $module) {
                                                // Cek apakah modul sudah dipelajari
                                                $module_learned = false;
                                                if (isset($_SESSION['student_id'])) {
                                                    $sql_learned = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$module['id']}'";
                                                    $query_learned = mysqli_query($conn, $sql_learned);
                                                    $module_learned = mysqli_num_rows($query_learned) > 0;
                                                }
                                                
                                                $status_class = $module_learned ? 'completed' : 'available';
                                                $status_icon = $module_learned ? 'fas fa-check-circle' : 'fas fa-play-circle';
                                        ?>
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <a href="module.php?module=<?php echo $module['id']; ?>" 
                                            class="module-item <?php echo $status_class; ?>">
                                            
                                                <div class="module-status">
                                                    <i class="<?php echo $status_icon; ?>"></i>
                                                </div>
                                                
                                                <div class="module-number">
                                                    <?php echo $module['number']; ?>
                                                </div>
                                                
                                                <div class="module-title">
                                                    Modul <?php echo $module['number']; ?>
                                                </div>
                                                
                                                <div class="module-description">
                                                    <?php echo htmlspecialchars($module['module_desc']); ?>
                                                </div>
                                            </a>
                                        </div>
                                        <?php 
                                            }
                                        } else {
                                        ?>
                                        <div class="col-12">
                                            <div class="text-center py-3">
                                                <i class="fas fa-folder-open fa-3x mb-3" style="opacity: 0.3;"></i>
                                                <h6>Belum Ada Modul</h6>
                                                <p class="text-muted">Modul untuk subtopik ini belum tersedia.</p>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-folder-open fa-5x mb-3" style="opacity: 0.3;"></i>
                                    <h4>Belum Ada Subtopik</h4>
                                    <p class="text-muted">Subtopik untuk topik ini belum tersedia.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    <?php } else { ?>
                        <!-- ✅ LOGIC LAMA UNTUK PEMBELAJARAN ADAPTIF -->
                        <?php
                        // Error Messages (tetap sama seperti sebelumnya)
                        if (isset($_SESSION['gagal_post_test'])) { ?>
                            <div class="alert alert-danger-custom alert-custom" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Gagal Lulus Post Test!</strong> Silakan ulangi kembali untuk melanjutkan pembelajaran.
                                <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php }
                        unset($_SESSION['gagal_post_test']);
                        
                        if (isset($_SESSION['turun_level'])) { ?>
                            <div class="alert alert-danger-custom alert-custom" role="alert">
                                <i class="fas fa-level-down-alt me-2"></i>
                                <strong>Level Turun!</strong> Anda gagal mengerjakan post test sebanyak 3x. Level Anda otomatis turun 1 tingkat.
                                <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php }
                        unset($_SESSION['turun_level']);
                        
                        if ($_SESSION['survey_taken']) {
                            if (isset($level_modul)) {
                                // Progress Info (sama seperti sebelumnya)
                                $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}'";
                                $query = mysqli_query($conn, $sql);
                                $learned_module = mysqli_num_rows($query);
                                $total_modules = 7;
                                ?>
                                
                                <div class="progress-info">
                                <h5 class="mb-2">
                                    <i class="fas fa-chart-bar me-2"></i>Progress Pembelajaran Anda
                                </h5>
                                <div class="progress-stats">
                                    <div class="progress-stat">
                                        <span class="progress-number"><?php echo $learned_module; ?></span>
                                        <small>Modul Selesai</small>
                                    </div>
                                    <div class="progress-stat">
                                        <span class="progress-number"><?php echo $total_modules; ?></span>
                                        <small>Total Modul</small>
                                    </div>
                                    <div class="progress-stat">
                                        <span class="progress-number"><?php echo round(($learned_module/$total_modules)*100); ?>%</span>
                                        <small>Kemajuan</small>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            foreach ($level_modul as $l) { ?>
                                <div class="level-section">
                                    <div class="level-title">
                                        <div class="level-badge"><?php echo $l; ?></div>
                                        <span>Level <?php echo $l; ?> - 
                                        <?php 
                                        if ($l == 1) echo "Dasar";
                                        else if ($l == 2) echo "Menengah";
                                        else echo "Lanjutan";
                                        ?>
                                        </span>
                                    </div>
                                    
                                    <div class="row">
                                        <?php
                                        $sql = "SELECT * FROM module WHERE module_level = '{$l}'";
                                        $query = mysqli_query($conn, $sql);
                                        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                        $last_key = 0;
                                        $last_learned = 0;
                                        
                                        foreach ($result as $key => $r) {
                                            $disabled = false;
                                            $module_learned = false;
                                            
                                            // Logic untuk menentukan status module (sama seperti original)
                                            if ($l == $level_user) {
                                                if ($key == array_key_first($result)) {
                                                    $disabled = false;
                                                } else {
                                                    $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' order by id DESC limit 1";
                                                    $query = mysqli_query($conn, $sql);
                                                    
                                                    if (mysqli_num_rows($query) > 0) {
                                                        $modul = mysqli_fetch_array($query, MYSQLI_ASSOC);
                                                        if ($modul['module_id'] == $last_key) {
                                                            $disabled = false;
                                                        } else {
                                                            $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$last_key}'";
                                                            $query = mysqli_query($conn, $sql);
                                                            if (mysqli_num_rows($query) > 0) {
                                                                $disabled = false;
                                                            } else {
                                                                $disabled = true;
                                                            }
                                                        }
                                                    } else {
                                                        $disabled = true;
                                                    }
                                                }
                                            } else if ($l < $level_user) {
                                                $disabled = false;
                                            } else {
                                                if (isset($level_done)) {
                                                    if ($level_done == ($l - 1)) {
                                                        if ($key == array_key_first($result)) {
                                                            $disabled = false;
                                                        } else {
                                                            $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' order by id DESC limit 1";
                                                            $query = mysqli_query($conn, $sql);
                                                            $modul = mysqli_fetch_array($query, MYSQLI_ASSOC);
                                                            if ($modul['module_id'] == $last_key) {
                                                                $disabled = false;
                                                            } else {
                                                                $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$last_key}'";
                                                                $query = mysqli_query($conn, $sql);
                                                                if (mysqli_num_rows($query) > 0) {
                                                                    $disabled = false;
                                                                } else {
                                                                    $disabled = true;
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $disabled = true;
                                                    }
                                                } else {
                                                    $disabled = true;
                                                }
                                            }
                                            
                                            $last_key = $r['id'];
                                            $sql = "SELECT * FROM module_learned WHERE student_id = '{$_SESSION['student_id']}' AND module_id = '{$r['id']}'";
                                            $query = mysqli_query($conn, $sql);
                                            if (mysqli_num_rows($query) > 0) {
                                                $module_learned = true;
                                            }
                                            
                                            if ($key == array_key_last($result) && $module_learned == true) {
                                                $level_done = $l;
                                            }
                                            
                                            $status_class = $disabled ? 'locked' : ($module_learned ? 'completed' : 'available');
                                            $status_icon = $disabled ? 'fas fa-lock' : ($module_learned ? 'fas fa-check-circle' : 'fas fa-play-circle');
                                        ?>
                                        
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <a href="<?php echo $disabled ? '#' : 'module.php?module=' . $r['id']; ?>" 
                                               class="module-item <?php echo $status_class; ?>" 
                                               <?php if ($disabled) echo 'onclick="return false;"'; ?>>
                                               
                                                <div class="module-status">
                                                    <i class="<?php echo $status_icon; ?>"></i>
                                                </div>
                                                
                                                <div class="module-number">
                                                    <?php echo $r['number']; ?>
                                                </div>
                                                
                                                <div class="module-title">
                                                    Modul <?php echo $r['number']; ?>
                                                </div>
                                                
                                                <div class="module-description">
                                                    <?php echo $r['module_desc']; ?>
                                                </div>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <!-- Quiz Section -->
                            <div class="quiz-section">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <h2 class="mb-3">
                                            <i class="fas fa-clipboard-question me-3"></i>Quiz Akhir
                                        </h2>
                                        <p class="lead mb-3">
                                            Uji pemahaman Anda dengan quiz biologi yang menarik
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-brain me-2"></i>
                                            Quiz tersedia kapan saja untuk mengukur kemampuan Anda
                                        </p>
                                    </div>
                                    <div class="col-lg-4 text-center">
                                        <a href="quiz.php" class="btn-quiz">
                                            <i class="fas fa-play me-2"></i>
                                            MULAI QUIZ
                                        </a>
                                    </div>
                                </div>
                            </div>
                                
                            <?php } else { ?>
                                <!-- Waiting Section (sama seperti sebelumnya) -->
                                <div class="waiting-section">
                                    <i class="fas fa-hourglass-half fa-5x mb-4"></i>
                                    <h2 class="mb-3">Proses Penghitungan Pre-Test</h2>
                                    <p class="lead mb-4">
                                        Silahkan tunggu hasil pre-test yang masih diproses oleh sistem
                                    </p>
                                    <a href="index.php" class="btn btn-light btn-lg">
                                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                                    </a>
                                </div>
                            <?php }
                        } else { ?>
                            <!-- No Survey Section (sama seperti sebelumnya) -->
                            <div class="waiting-section">
                                <i class="fas fa-clipboard-question fa-5x mb-4"></i>
                                <h2 class="mb-3">Pre-Test Diperlukan</h2>
                                <p class="lead mb-4">
                                    Anda belum melakukan pre-test. Silakan lakukan pre-test terlebih dahulu untuk mendapatkan rekomendasi modul pembelajaran.
                                </p>
                                <a href="index.php" class="btn btn-light btn-lg">
                                    <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        // Module interactions
        $('.module-item').hover(
            function() {
                $(this).find('.module-number').addClass('animate__pulse');
            },
            function() {
                $(this).find('.module-number').removeClass('animate__pulse');
            }
        );
        
        // Module click tracking
        $('.module-item').on('click', function() {
            const moduleNumber = $(this).find('.module-number').text();
            
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
            
            toast.fire({
                icon: 'info',
                title: `Memuat Modul ${moduleNumber}...`,
                background: '#00C851',
                color: 'white'
            });
        });
    });
    </script>
</body>

</html>