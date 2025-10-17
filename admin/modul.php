<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}
if (!isset($_GET['subtopik']) || empty($_GET['subtopik'])) {
    echo "<script>
        alert('Parameter subtopik tidak ditemukan!');
        window.location.href = 'topik.php';
    </script>";
    exit();
}
// Mengambil id sub topik dari URL
$id = $_GET['subtopik'];

// Mengambil data sub topik dari database
$sql = "SELECT st.*, t.topic_desc FROM sub_topic st JOIN topic t ON st.topic_id = t.id WHERE st.id = '{$id}'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_array($query, MYSQLI_ASSOC);
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Kelola Modul - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5.2.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.3.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- DataTables with Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
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
            --module-gradient: linear-gradient(135deg, #667eea 0%, #fa709a 100%);
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
            background: var(--module-gradient);
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
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
        }
        
        .btn-add {
            background: var(--success-gradient);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
        }
        
        .btn-back {
            background: var(--ocean-gradient);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-decoration: none;
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-edit {
            background: var(--warm-gradient);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-edit:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        .btn-delete {
            background: var(--secondary-gradient);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-delete:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .table thead th {
            background: var(--module-gradient);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9ff;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: var(--module-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-info {
            background: var(--success-gradient);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
        }
        
        .btn-danger {
            background: var(--secondary-gradient);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
        }
        
        .dataTables_wrapper {
            padding: 0;
        }
        
        .dataTables_length select,
        .dataTables_filter input {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 8px 12px;
        }
        
        .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: none;
            background: var(--module-gradient);
            color: white;
        }
        
        .page-link:hover {
            background: var(--primary-gradient);
            color: white;
        }
        
        .page-item.active .page-link {
            background: var(--secondary-gradient);
            border-color: transparent;
        }
        
        /* Navbar Styling */
        .navbar {
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 700;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 4px;
            padding: 8px 12px !important;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            font-weight: 600;
        }
        
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: var(--module-gradient);
            color: white;
            transform: translateX(5px);
        }
        
        .dropdown-header {
            font-weight: 600;
            color: #667eea;
            font-size: 0.9rem;
        }
        
        .badge {
            font-weight: 500;
            font-size: 0.8rem;
        }
        
        .context-info {
            background: var(--nature-gradient);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .breadcrumb-nav {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .breadcrumb-nav a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .breadcrumb-nav a:hover {
            opacity: 1;
            text-decoration: underline;
        }
        
        /* Loading Animation */
        .dataTables_processing {
            background: rgba(255,255,255,0.9) !important;
            border: none !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
            border-radius: 10px !important;
            color: #667eea !important;
            font-weight: 600 !important;
        }
        
        /* Action Button Hover Effects */
        .btn-edit:hover, .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Modern Alert Styling */
        .swal2-popup {
            border-radius: 15px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        
        .swal2-title {
            color: #333 !important;
            font-weight: 600 !important;
        }
        
        .swal2-confirm {
            border-radius: 10px !important;
            padding: 10px 20px !important;
            font-weight: 600 !important;
        }
        
        .swal2-cancel {
            border-radius: 10px !important;
            padding: 10px 20px !important;
            font-weight: 600 !important;
        }
    </style>
</head>

<body>
    <?php
    // Level user handling
    if (!isset($_SESSION['level_user'])) {
        header('location: ../sign-in.php');
        exit();
    }
    
    $level_user = $_SESSION['level_user'];
    $user_name = $_SESSION['name'] ?? 'Guest';
    $user_login = $_SESSION['login'] ?? 'User';
    
    // Get module statistics for current subtopic
    $total_modules = 0;
    
    $result_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM module WHERE sub_topic_id = '{$id}'");
    if ($result_count) {
        $row_count = mysqli_fetch_assoc($result_count);
        $total_modules = $row_count['count'];
    }
    ?>
    
    <!-- Navigation Bar -->
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
                        <a class="nav-link text-white" href="index.php">
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
                            <a class="nav-link text-white active" href="topik.php">
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
            <div class="breadcrumb-nav">
                <i class="fas fa-home me-2"></i>
                <a href="index.php">Dashboard</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="topik.php">Topik</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="subtopik.php?topik=<?php echo $result['topic_id']; ?>"><?php echo htmlspecialchars($result['topic_desc']); ?></a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span><?php echo htmlspecialchars($result['sub_topic_desc']); ?></span>
            </div>
            
            <h1>
                <i class="fas fa-puzzle-piece"></i>
                Kelola Modul
            </h1>
            <div class="subtitle">
                <i class="fas fa-cubes me-2"></i>
                Manajemen modul pembelajaran untuk subtopik: <strong><?php echo htmlspecialchars($result['sub_topic_desc']); ?></strong>
                <span class="badge bg-light text-dark ms-3">
                    <i class="fas fa-calendar me-1"></i>
                    <?php echo date('d F Y'); ?>
                </span>
            </div>
            <div class="mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                <i class="fas fa-puzzle-piece fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo $total_modules; ?></h4>
                                <small class="opacity-75">Total Modul</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                <i class="fas fa-layer-group fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo htmlspecialchars($result['sub_topic_desc']); ?></h4>
                                <small class="opacity-75">Subtopik</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                <i class="fas fa-bookmark fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo htmlspecialchars($result['topic_desc']); ?></h4>
                                <small class="opacity-75">Topik Induk</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <h3 class="mb-0">
                        <i class="fas fa-cubes text-primary me-2"></i>
                        Daftar Modul
                    </h3>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="subtopik.php?topik=<?php echo $result['topic_id'] ?>" class="btn btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Subtopik
                    </a>
                    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahModul">
                        <i class="fas fa-plus me-2"></i>Tambah Modul
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="moduleTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>No</th>
                            <th><i class="fas fa-puzzle-piece me-2"></i>Modul</th>
                            <th><i class="fas fa-cogs me-2"></i>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Modul -->
    <div class="modal fade" id="modalTambahModul" tabindex="-1" aria-labelledby="modalTambahModulLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../data/materi.php?action=tambahModul" method="POST" id="formTambahModul">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahModulLabel">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Modul Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="subtopik" value="<?php echo $id ?>">
                        
                        <div class="context-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-1">
                                        <i class="fas fa-bookmark me-2"></i>Topik
                                    </h6>
                                    <strong><?php echo htmlspecialchars($result['topic_desc']); ?></strong>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1">
                                        <i class="fas fa-layer-group me-2"></i>Subtopik
                                    </h6>
                                    <strong><?php echo htmlspecialchars($result['sub_topic_desc']); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="modul" class="form-label fw-bold">
                                <i class="fas fa-puzzle-piece text-primary me-2"></i>Nama Modul
                            </label>
                            <input type="text" class="form-control" name="modul" placeholder="Masukkan nama modul pembelajaran..." required>
                            <div class="form-text">Contoh: Pengenalan Sel, Struktur Membran Sel, Organel Sel, dll.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-save me-2"></i>Simpan Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Modul -->
    <div class="modal fade" id="modalEditModul" tabindex="-1" aria-labelledby="modalEditModulLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../data/materi.php?action=editModul" method="POST" id="formeditModul">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditModulLabel">
                            <i class="fas fa-edit me-2"></i>Edit Modul
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id" id="idModul" value="" />
                        
                        <div class="context-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-1">
                                        <i class="fas fa-bookmark me-2"></i>Topik
                                    </h6>
                                    <strong><?php echo htmlspecialchars($result['topic_desc']); ?></strong>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1">
                                        <i class="fas fa-layer-group me-2"></i>Subtopik
                                    </h6>
                                    <strong><?php echo htmlspecialchars($result['sub_topic_desc']); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="modul" class="form-label fw-bold">
                                <i class="fas fa-puzzle-piece text-primary me-2"></i>Nama Modul
                            </label>
                            <input type="text" class="form-control" id="modul" name="modul" placeholder="Masukkan nama modul pembelajaran..." required>
                            <div class="form-text">Ubah nama modul sesuai kebutuhan pembelajaran.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-save me-2"></i>Update Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modern JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    var table;
    $(document).ready(function() {
        table = $('#moduleTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../data/materi.php?action=getModul",
                "data": {
                    "subtopik": <?php echo $id ?>,
                },
                "dataType": "json",
                "type": "POST",
            },
            "columnDefs": [
                {
                    "targets": [0, 2],
                    "orderable": false,
                },
            ],
            "columns": [{
                    data: "no",
                    name: "no"
                },
                {
                    data: "module_desc",
                    name: "module_desc"
                },
                {
                    data: "action",
                    name: "action"
                }
            ],
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Submit Form Tambah Modul
        $('#formTambahModul').submit(function() {
            $.ajax({
                url: "../data/materi.php?action=tambahModul",
                method: "POST",
                data: $('#formTambahModul').serialize(),
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Modul baru berhasil ditambahkan',
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#fff',
                        color: '#333'
                    });
                    table.ajax.reload();
                    $('#modalTambahModul').modal('hide');
                    $('#formTambahModul')[0].reset();
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menambah modul'
                    });
                    console.log(e)
                }
            })
            return false;
        })

        // Menampilkan modal edit modul
        $('#moduleTable').on('click', '#btn-edit', function() {
            $.ajax({
                url: "../data/materi.php?action=getModulById",
                method: "post",
                type: "ajax",
                data: {
                    modul: $(this).attr('data'),
                },
                dataType: "json",
                success: function(data) {
                    $('#modul').val(data['modul']);
                    $('#idModul').val(data['id']);
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat mengambil data modul'
                    });
                    console.log(e);
                }
            })
            $('#modalEditModul').modal('show');
        })

        // Submit Form Edit Modul
        $('#formeditModul').submit(function() {
            $.ajax({
                url: '../data/materi.php?action=editModul',
                method: "post",
                type: "ajax",
                data: {
                    id: $('#idModul').val(),
                    modul: $('#modul').val()
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Modul berhasil diperbarui',
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#fff',
                        color: '#333'
                    });
                    table.ajax.reload();
                    $('#modalEditModul').modal('hide');
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui modul'
                    });
                    console.log(e);
                }
            });
            return false;
        })

        // Konfirmasi hapus modul
        $('#moduleTable').on('click', '#btn-delete', function() {
            const modulId = $(this).attr('data');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus modul ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                background: '#fff',
                color: '#333'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../data/materi.php?action=hapusModul',
                        method: 'post',
                        type: 'ajax',
                        data: {
                            id: modulId
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Modul berhasil dihapus',
                                timer: 2000,
                                showConfirmButton: false,
                                background: '#fff',
                                color: '#333'
                            });
                            table.ajax.reload();
                        },
                        error: function(e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus modul'
                            });
                            console.log(e);
                        }
                    })
                }
            })
        })
    });
    </script>
</body>

</html>