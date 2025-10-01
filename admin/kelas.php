<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Kelola Kelas - MyIRT Adaptive Learning</title>
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
            --class-gradient: linear-gradient(135deg, #667eea 0%, #a8edea 100%);
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
            background: var(--class-gradient);
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--ocean-gradient);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .table {
            margin: 0;
        }
        
        .table thead th {
            background: var(--ocean-gradient);
            color: white;
            border: none;
            padding: 1rem 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(168, 237, 234, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 0.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-edit {
            background: var(--nature-gradient);
            color: white;
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(132, 250, 176, 0.4);
            color: white;
        }
        
        .btn-delete {
            background: var(--secondary-gradient);
            color: white;
        }
        
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
            color: white;
        }
        
        .btn-add {
            background: var(--ocean-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(168, 237, 234, 0.4);
            color: white;
        }
        
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: var(--ocean-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
            padding: 1.5rem;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #a8edea;
            box-shadow: 0 0 0 0.2rem rgba(168, 237, 234, 0.25);
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-primary {
            background: var(--ocean-gradient);
            color: white;
        }
        
        .badge-success {
            background: var(--biology-gradient);
            color: white;
        }
        
        .badge-warning {
            background: var(--warm-gradient);
            color: white;
        }
        
        .class-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-item {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .stat-item.total {
            border-left-color: #a8edea;
        }
        
        .stat-item.students {
            border-left-color: #43e97b;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .navbar {
            margin-bottom: 0;
        }
        
        .navbar-nav .nav-link.active {
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            font-weight: 600;
        }
        
        .navbar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        
        .dropdown-item:hover {
            background: var(--ocean-gradient);
            color: white;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .content-card,
        .table-container,
        .stat-item {
            animation: fadeInUp 0.6s ease;
        }
        
        .stat-item:nth-child(2) {
            animation-delay: 0.1s;
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .main-content {
                margin: 10px;
            }
            
            .btn-action {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            
            .class-stats {
                grid-template-columns: 1fr;
            }
        }
        
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin: 0.5rem 0;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 5px;
            margin: 0 2px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--ocean-gradient) !important;
            border-color: transparent !important;
            color: white !important;
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
    
    // Get class statistics
    $total_classes = 0;
    $total_students = 0;
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM class");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_classes = $row['count'];
    }
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM student");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_students = $row['count'];
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
                            <a class="nav-link text-white" href="topik.php">
                                <i class="fas fa-book-open me-1"></i>Materi
                            </a>
                        </li>
                    <?php } ?>
                    
                    <?php if ($level_user == 1) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="kelas.php">
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
            <h1>
                <i class="fas fa-school"></i>
                Kelola Kelas
            </h1>
            <div class="subtitle">
                <i class="fas fa-users-cog me-2"></i>
                Manajemen kelas dan distribusi siswa dalam sistem pembelajaran
                <span class="badge bg-light text-dark ms-3">
                    <i class="fas fa-calendar me-1"></i>
                    <?php echo date('d F Y'); ?>
                </span>
            </div>
        </div>

        <!-- Class Statistics -->
        <div class="content-card">
            <h3 class="mb-3">
                <i class="fas fa-chart-bar text-primary me-2"></i>
                Statistik Kelas
            </h3>
            <div class="class-stats">
                <div class="stat-item total">
                    <div class="stat-number"><?php echo $total_classes; ?></div>
                    <div class="stat-label">
                        <i class="fas fa-school me-1"></i>
                        Total Kelas
                    </div>
                </div>
                <div class="stat-item students">
                    <div class="stat-number"><?php echo $total_students; ?></div>
                    <div class="stat-label">
                        <i class="fas fa-user-graduate me-1"></i>
                        Total Siswa
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">
                        <i class="fas fa-list text-primary me-2"></i>
                        Daftar Kelas
                    </h3>
                    <p class="text-muted mb-0">Kelola data kelas dan monitoring distribusi siswa</p>
                </div>
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                    <i class="fas fa-plus"></i>
                    Tambah Kelas
                </button>
            </div>
            
            <div class="table-container">
                <table id="kelasTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>No</th>
                            <th><i class="fas fa-school me-1"></i>Kelas</th>
                            <th><i class="fas fa-users me-1"></i>Jumlah Murid</th>
                            <th><i class="fas fa-cogs me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kelas -->
    <div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-labelledby="modalTambahKelasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../data/kelas.php?action=tambahKelas" method="POST" id="formtambahKelas">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahKelasLabel">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Kelas Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="kelas" class="col-sm-3 col-form-label fw-bold">
                                <i class="fas fa-school me-2"></i>Nama Kelas
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kelas" placeholder="Contoh: A, B, C, atau X-1, X-2" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Masukkan nama kelas yang akan digunakan dalam sistem
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn text-white" style="background: var(--ocean-gradient);">
                            <i class="fas fa-save me-1"></i>Simpan Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kelas -->
    <div class="modal fade" id="modalEditKelas" tabindex="-1" aria-labelledby="modalEditKelasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../data/kelas.php?action=editKelas" method="POST" id="formEditKelas">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditKelasLabel">
                            <i class="fas fa-edit me-2"></i>Edit Data Kelas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="idKelas" value="" />
                        <div class="mb-3 row">
                            <label for="Kelas" class="col-sm-3 col-form-label fw-bold">
                                <i class="fas fa-school me-2"></i>Nama Kelas
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Contoh: A, B, C, atau X-1, X-2" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Ubah nama kelas sesuai kebutuhan sistem
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn text-white" style="background: var(--nature-gradient);">
                            <i class="fas fa-sync-alt me-1"></i>Update Kelas
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
        // Initialize DataTable with modern styling
        table = $('#kelasTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../data/kelas.php?action=getKelas",
                "dataType": "json",
                "type": "POST",
            },
            "columnDefs": [
                {
                    "targets": [0, 2, 3],
                    "orderable": false,
                },
            ],
            "columns": [{
                    data: "no",
                    name: "no"
                },
                {
                    data: "kelas",
                    name: "kelas"
                },
                {
                    data: "jumlah_murid",
                    name: "jumlah_murid",
                }, {
                    data: "action",
                    name: "action"
                }
            ],
            "language": {
                "processing": "<div class='text-center'><i class='fas fa-spinner fa-spin me-2'></i>Memuat data...</div>",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "<div class='text-center py-4'><i class='fas fa-inbox fa-3x text-muted mb-3'></i><br>Tidak ada data kelas yang ditemukan</div>",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
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

        // Submit Tambah Kelas with enhanced feedback
        $('#formtambahKelas').submit(function() {
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...').prop('disabled', true);
            
            $.ajax({
                url: "../data/kelas.php?action=tambahKelas",
                method: "POST",
                data: $('#formtambahKelas').serialize(),
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kelas baru berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#fff',
                        color: '#333'
                    });
                    table.ajax.reload();
                    $('#modalTambahKelas').modal('hide');
                    $('#formtambahKelas')[0].reset();
                },
                error: function(e) {
                    console.log(e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menambah kelas',
                        confirmButtonColor: '#f5576c'
                    });
                },
                complete: function() {
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
            return false;
        });

        // Show Edit Modal with enhanced UI
        $('#kelasTable').on('click', '#btn-edit', function() {
            const kelasId = $(this).attr('data');
            
            $.ajax({
                url: "../data/kelas.php?action=getKelasById",
                method: "post",
                type: "ajax",
                data: {
                    id: kelasId,
                },
                dataType: "json",
                success: function(data) {
                    $('#kelas').val(data['kelas']);
                    $('#idKelas').val(data['id']);
                    $('#modalEditKelas').modal('show');
                },
                error: function(e) {
                    console.log(e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat memuat data kelas',
                        confirmButtonColor: '#f5576c'
                    });
                }
            });
        });

        // Submit Edit Kelas
        $('#formEditKelas').submit(function() {
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Memperbarui...').prop('disabled', true);
            
            $.ajax({
                url: '../data/kelas.php?action=editKelas',
                method: "post",
                type: "ajax",
                data: {
                    id: $('#idKelas').val(),
                    kelas: $('#kelas').val()
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data kelas berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload();
                    $('#modalEditKelas').modal('hide');
                },
                error: function(e) {
                    console.log(e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui kelas',
                        confirmButtonColor: '#f5576c'
                    });
                },
                complete: function() {
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
            return false;
        });

        // Delete Kelas with enhanced confirmation
        $('#kelasTable').on('click', '#btn-delete', function() {
            const kelasId = $(this).attr('data');
            
            Swal.fire({
                title: 'Konfirmasi Hapus Kelas',
                text: "Apakah Anda yakin ingin menghapus kelas ini? Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f5576c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i>Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times me-1"></i>Batal',
                reverseButtons: true,
                background: '#fff',
                customClass: {
                    popup: 'rounded-3 shadow-lg',
                    confirmButton: 'btn rounded-pill px-4',
                    cancelButton: 'btn rounded-pill px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang memproses penghapusan kelas',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.ajax({
                        url: '../data/kelas.php?action=hapusKelas',
                        method: 'post',
                        type: 'ajax',
                        data: {
                            id: kelasId
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Kelas berhasil dihapus dari sistem',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.ajax.reload();
                        },
                        error: function(e) {
                            console.log(e);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus kelas',
                                confirmButtonColor: '#f5576c'
                            });
                        }
                    });
                }
            });
        });
        
        // Add modern animations to table rows
        $('#kelasTable').on('draw.dt', function() {
            $(this).find('tbody tr').each(function(index) {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                }).animate({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                }, 300 + (index * 50));
            });
        });
        
        // Welcome notification
        setTimeout(() => {
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            
            toast.fire({
                icon: 'info',
                title: 'Selamat datang di Kelola Kelas!',
                background: '#a8edea',
                color: 'white'
            });
        }, 1000);
    });
    </script>
</body>

</html>