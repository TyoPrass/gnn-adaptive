<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// Mengambil id topik dari URL
$id = $_GET['topik'];

// Mengambil data topik dari database
$sql = "SELECT * FROM topic WHERE id = '{$id}'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_array($query, MYSQLI_ASSOC);

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Kelola Subtopik - MyIRT Adaptive Learning</title>
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
            --subtopic-gradient: linear-gradient(135deg, #667eea 0%, #38f9d7 100%);
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
            background: var(--subtopic-gradient);
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
            background: var(--subtopic-gradient);
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
            background: var(--subtopic-gradient);
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
            background: var(--subtopic-gradient);
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
            background: var(--subtopic-gradient);
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
        
        .topic-info {
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
    
    // Get subtopic statistics for current topic
    $total_subtopics = 0;
    
    $result_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM sub_topic WHERE topic_id = '{$id}'");
    if ($result_count) {
        $row_count = mysqli_fetch_assoc($result_count);
        $total_subtopics = $row_count['count'];
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
                <span><?php echo htmlspecialchars($result['topic_desc']); ?></span>
            </div>
            
            <h1>
                <i class="fas fa-list"></i>
                Kelola Subtopik
            </h1>
            <div class="subtitle">
                <i class="fas fa-layer-group me-2"></i>
                Manajemen subtopik untuk topik: <strong><?php echo htmlspecialchars($result['topic_desc']); ?></strong>
                <span class="badge bg-light text-dark ms-3">
                    <i class="fas fa-calendar me-1"></i>
                    <?php echo date('d F Y'); ?>
                </span>
            </div>
            <div class="mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                <i class="fas fa-list fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo $total_subtopics; ?></h4>
                                <small class="opacity-75">Total Subtopik</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                        <i class="fas fa-layer-group text-primary me-2"></i>
                        Daftar Subtopik
                    </h3>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="topik.php" class="btn btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Topik
                    </a>
                    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahSubTopik">
                        <i class="fas fa-plus me-2"></i>Tambah Subtopik
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="subTopikTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>No</th>
                            <th><i class="fas fa-layer-group me-2"></i>Subtopik</th>
                            <th><i class="fas fa-cogs me-2"></i>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Subtopik -->
    <div class="modal fade" id="modalTambahSubTopik" tabindex="-1" aria-labelledby="modalTambahSubTopikLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../data/materi.php?action=tambahSubTopik" method="POST" id="formTambahSubTopik">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahSubTopikLabel">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Subtopik Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id" id="idTopik" value="" />
                        <input type="hidden" name="topik" value="<?php echo $id ?>">
                        
                        <div class="topic-info">
                            <h6 class="mb-1">
                                <i class="fas fa-bookmark me-2"></i>Topik Induk
                            </h6>
                            <strong><?php echo htmlspecialchars($result['topic_desc']); ?></strong>
                        </div>
                        
                        <div class="mb-4">
                            <label for="subtopik" class="form-label fw-bold">
                                <i class="fas fa-layer-group text-primary me-2"></i>Nama Subtopik
                            </label>
                            <input type="text" class="form-control" name="subtopik" placeholder="Masukkan nama subtopik pembelajaran..." required>
                            <div class="form-text">Contoh: Struktur Sel, Fungsi Organel, Siklus Sel, dll.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-save me-2"></i>Simpan Subtopik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Subtopik -->
    <div class="modal fade" id="modalEditSubTopik" tabindex="-1" aria-labelledby="modalEditSubTopikLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../data/materi.php?action=editSubTopik" method="POST" id="formEditSubTopik">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditSubTopikLabel">
                            <i class="fas fa-edit me-2"></i>Edit Subtopik
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id" id="idSubTopik" value="" />
                        
                        <div class="topic-info">
                            <h6 class="mb-1">
                                <i class="fas fa-bookmark me-2"></i>Topik Induk
                            </h6>
                            <strong><?php echo htmlspecialchars($result['topic_desc']); ?></strong>
                        </div>
                        
                        <div class="mb-4">
                            <label for="subTopik" class="form-label fw-bold">
                                <i class="fas fa-layer-group text-primary me-2"></i>Nama Subtopik
                            </label>
                            <input type="text" class="form-control" id="subTopik" name="subTopik" placeholder="Masukkan nama subtopik pembelajaran..." required>
                            <div class="form-text">Ubah nama subtopik sesuai kebutuhan pembelajaran.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-save me-2"></i>Update Subtopik
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
        table = $('#subTopikTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../data/materi.php?action=getSubTopik",
                "data": {
                    topik: <?php echo $id ?>,
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
                    data: "sub_topic_desc",
                    name: "sub_topic_desc"
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

        // Submit tambah subtopik
        $('#formTambahSubTopik').submit(function() {
            $.ajax({
                url: "../data/materi.php?action=tambahSubTopik",
                method: "POST",
                data: $('#formTambahSubTopik').serialize(),
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Subtopik baru berhasil ditambahkan',
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#fff',
                        color: '#333'
                    });
                    table.ajax.reload();
                    $('#modalTambahSubTopik').modal('hide');
                    $('#formTambahSubTopik')[0].reset();
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menambah subtopik'
                    });
                    console.log(e)
                }
            })
            return false;
        })

        // Tampilkan modal edit subtopik
        $('#subTopikTable').on('click', '#btn-edit', function() {
            $.ajax({
                url: "../data/materi.php?action=getSubTopikById",
                method: "post",
                type: "ajax",
                data: {
                    topik: $(this).attr('data'),
                },
                dataType: "json",
                success: function(data) {
                    $('#subTopik').val(data['sub_topik']);
                    $('#idSubTopik').val(data['id']);
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat mengambil data subtopik'
                    });
                    console.log(e);
                }
            })
            $('#modalEditSubTopik').modal('show');
        })

        // Submit edit subtopik
        $('#formEditSubTopik').submit(function() {
            $.ajax({
                url: '../data/materi.php?action=editSubTopik',
                method: "post",
                type: "ajax",
                data: {
                    id: $('#idSubTopik').val(),
                    subtopik: $('#subTopik').val()
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Subtopik berhasil diperbarui',
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#fff',
                        color: '#333'
                    });
                    table.ajax.reload();
                    $('#modalEditSubTopik').modal('hide');
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui subtopik'
                    });
                    console.log(e);
                }
            });
            return false;
        })

        // Konfirmasi hapus subtopik
        $('#subTopikTable').on('click', '#btn-delete', function() {
            const subtopikId = $(this).attr('data');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus subtopik ini?',
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
                        url: '../data/materi.php?action=hapusSubTopik',
                        method: 'post',
                        type: 'ajax',
                        data: {
                            id: subtopikId
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Subtopik berhasil dihapus',
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
                                text: 'Terjadi kesalahan saat menghapus subtopik'
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