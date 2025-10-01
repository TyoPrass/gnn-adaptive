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

    <title>Kelola Guru - MyIRT Adaptive Learning</title>
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
            --teacher-gradient: linear-gradient(135deg, #fa709a 0%, #43e97b 100%);
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
            background: var(--teacher-gradient);
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
            background: var(--warm-gradient);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .table thead th {
            background: var(--warm-gradient);
            color: white;
            border: none;
            padding: 1rem 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .btn-add {
            background: var(--warm-gradient);
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
            box-shadow: 0 8px 25px rgba(250, 112, 154, 0.4);
            color: white;
        }
        
        .modal-header {
            background: var(--warm-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
            padding: 1.5rem;
        }
        
        .teacher-type-selector {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px solid #e9ecef;
        }
        
        .teacher-type-selector.selected {
            border-color: #fa709a;
            background: rgba(250, 112, 154, 0.1);
        }
        
        .form-check-input:checked {
            background-color: #fa709a;
            border-color: #fa709a;
        }
        
        .class-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .class-checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .class-checkbox-item:hover {
            background: rgba(250, 112, 154, 0.1);
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .main-content {
                margin: 10px;
            }
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
    
    // Get teacher statistics
    $total_teachers = 0;
    $total_classes = 0;
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM teachers");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_teachers = $row['count'];
    }
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM class");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_classes = $row['count'];
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
                            <a class="nav-link text-white" href="kelas.php">
                                <i class="fas fa-school me-1"></i>Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="guru.php">
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
                <i class="fas fa-chalkboard-teacher"></i>
                Kelola Data Guru
            </h1>
            <div class="subtitle">
                <i class="fas fa-users-cog me-2"></i>
                Manajemen guru dan distribusi kelas dalam sistem pembelajaran
                <span class="badge bg-light text-dark ms-3">
                    <i class="fas fa-calendar me-1"></i>
                    <?php echo date('d F Y'); ?>
                </span>
            </div>
        </div>

        <!-- Content Card -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">
                        <i class="fas fa-list text-primary me-2"></i>
                        Daftar Guru
                    </h3>
                    <p class="text-muted mb-0">Kelola data guru, distribusi kelas, dan informasi akademik</p>
                </div>
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahGuru">
                    <i class="fas fa-plus"></i>
                    Tambah Guru
                </button>
            </div>
            
            <div class="table-container">
                <table id="guruTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>No</th>
                            <th><i class="fas fa-chalkboard-teacher me-1"></i>Guru</th>
                            <th><i class="fas fa-id-card me-1"></i>NIP/Email</th>
                            <th><i class="fas fa-map-marker-alt me-1"></i>Alamat</th>
                            <th><i class="fas fa-phone me-1"></i>No HP</th>
                            <th><i class="fas fa-school me-1"></i>Kelas</th>
                            <th><i class="fas fa-cogs me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modern Modal Tambah Guru -->
    <div class="modal fade" id="modalTambahGuru" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus text-primary me-2"></i>
                        Tambah Data Guru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="../data/guru.php?action=tambahGuru" method="POST" id="formtambahGuru">
                    <div class="modal-body">
                        <div class="teacher-type-section mb-4">
                            <label class="form-label fw-bold">Tipe Guru *</label>
                            <div class="teacher-type-grid">
                                <input type="radio" class="btn-check" name="tipeguru" id="tipeguru1" value="1" required>
                                <label class="btn btn-outline-primary" for="tipeguru1">
                                    <i class="fas fa-user-tie me-1"></i>PNS
                                </label>
                                <input type="radio" class="btn-check" name="tipeguru" id="tipeguru2" value="2" required>
                                <label class="btn btn-outline-primary" for="tipeguru2">
                                    <i class="fas fa-user me-1"></i>Honorer
                                </label>
                            </div>
                        </div>
                        
                        <div id="selectedGuru" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-6" id="nipGroup">
                                    <label class="form-label">NIP</label>
                                    <input type="text" class="form-control" name="nip" placeholder="Nomor Induk Pegawai">
                                </div>
                                <div class="col-md-6" id="emailGroup">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email aktif">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password *</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No HP</label>
                                <input type="text" class="form-control" name="no_hp">
                            </div>
                        </div>
                        
                        <div class="teacher-class-section">
                            <label class="form-label fw-bold">Distribusi Kelas *</label>
                            <p class="text-muted mb-3">Pilih kelas yang akan diampu oleh guru ini</p>
                            <div class="teacher-class-grid">
                                <input type="checkbox" class="btn-check" name="kelas1" id="kelas1" value="1" required>
                                <label class="btn btn-outline-primary" for="kelas1">
                                    <i class="fas fa-school me-1"></i>Kelas A
                                </label>
                                <input type="checkbox" class="btn-check" name="kelas2" id="kelas2" value="2">
                                <label class="btn btn-outline-primary" for="kelas2">
                                    <i class="fas fa-school me-1"></i>Kelas B
                                </label>
                                <input type="checkbox" class="btn-check" name="kelas3" id="kelas3" value="3">
                                <label class="btn btn-outline-primary" for="kelas3">
                                    <i class="fas fa-school me-1"></i>Kelas C
                                </label>
                                <input type="checkbox" class="btn-check" name="kelas4" id="kelas4" value="4">
                                <label class="btn btn-outline-primary" for="kelas4">
                                    <i class="fas fa-school me-1"></i>Kelas D
                                </label>
                                <input type="checkbox" class="btn-check" name="kelas5" id="kelas5" value="5">
                                <label class="btn btn-outline-primary" for="kelas5">
                                    <i class="fas fa-school me-1"></i>Kelas E
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modern Modal Edit Guru -->
    <div class="modal fade" id="modalEditGuru" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit text-warning me-2"></i>
                        Edit Data Guru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="../data/guru.php?action=editGuru" method="POST" id="formEditGuru">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="idGuru">
                        <input type="hidden" name="tipeGuruEdit" id="tipeGuruEdit">
                        
                        <div id="selectedGuruEdit">
                            <div class="row mb-3">
                                <div class="col-md-6" id="nipGroupEdit">
                                    <label class="form-label">NIP</label>
                                    <input type="text" class="form-control" name="nipEdit" id="nipEdit" readonly>
                                </div>
                                <div class="col-md-6" id="emailGroupEdit">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="emailEdit" id="emailEdit" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control" name="namaEdit" id="namaEdit" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No HP</label>
                                <input type="text" class="form-control" name="no_hpEdit" id="no_hpEdit">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamatEdit" id="alamatEdit" rows="3"></textarea>
                        </div>
                        
                        <div class="teacher-class-section">
                            <label class="form-label fw-bold">Distribusi Kelas *</label>
                            <p class="text-muted mb-3">Pilih kelas yang akan diampu oleh guru ini</p>
                            <div class="teacher-class-grid">
                                <input type="checkbox" class="btn-check" name="kelas1Edit" id="kelas1Edit" value="1" required>
                                <label class="btn btn-outline-primary" for="kelas1Edit">
                                    <i class="fas fa-school me-1"></i>Kelas A
                                </label>
                                <input type="checkbox" class="btn-check" name="kelas2Edit" id="kelas2Edit" value="2">
                                <label class="btn btn-outline-primary" for="kelas2Edit">
                                    <i class="fas fa-school me-1"></i>Kelas B
                                </label>
                                <input type="checkbox" class="btn-check" name="kelas3Edit" id="kelas3Edit" value="3">
                                <label class="btn btn-outline-primary" for="kelas3Edit">
                                    <i class="fas fa-school me-1"></i>Kelas C
                                </label>
                                <input type="checkbox" class="btn-check" name="kelas4Edit" id="kelas4Edit" value="4">
                                <label class="btn btn-outline-primary" for="kelas4Edit">
                                    <i class="fas fa-school me-1"></i>Kelas D
                                </label>
                                <input type="checkbox" class="btn-check" name="kelas5Edit" id="kelas5Edit" value="5">
                                <label class="btn btn-outline-primary" for="kelas5Edit">
                                    <i class="fas fa-school me-1"></i>Kelas E
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <!-- <script src="../assets/bundles/libscripts.bundle.js"></script> -->

    <!-- Plugin Js-->
    <!-- <script src="../node_modules/owl.carousel2/dist/owl.carousel.min.js"></script>
    <script src="../assets/bundles/apexcharts.bundle.js"></script> -->

    <!-- Jquery Page Js -->
    <!-- Modern Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        let guruTable;
        
        $(document).ready(function() {
            // Initialize modern DataTable
            guruTable = $('#guruTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "../data/guru.php?action=getGuru",
                    type: "POST",
                    dataType: "json"
                },
                columnDefs: [{
                    targets: [0, 6],
                    orderable: false
                }],
                columns: [
                    { data: "no", name: "no" },
                    { data: "guru", name: "guru" },
                    { data: "nip", name: "nip" },
                    { data: "alamat", name: "alamat" },
                    { data: "no_hp", name: "no_hp" },
                    { data: "kelas", name: "kelas" },
                    { data: "action", name: "action" }
                ],
                language: {
                    processing: "<i class='fas fa-spinner fa-spin'></i> Memuat data...",
                    emptyTable: "Belum ada data guru",
                    zeroRecords: "Data tidak ditemukan",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ guru",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 guru",
                    infoFiltered: "(difilter dari _MAX_ total guru)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
            
            // Modern teacher type selection
            $('input[type=radio][name=tipeguru]').change(function() {
                $('#selectedGuru').show();
                if ($(this).val() == 1) {
                    $('#nipGroup').show();
                    $('#emailGroup').hide();
                    $('input[name="nip"]').attr('required', true);
                    $('input[name="email"]').attr('required', false);
                } else {
                    $('#nipGroup').hide();
                    $('#emailGroup').show();
                    $('input[name="nip"]').attr('required', false);
                    $('input[name="email"]').attr('required', true);
                }
            });
            
            // Modern checkbox validation
            function setupCheckboxValidation(containerClass) {
                const checkboxes = $(`.${containerClass} input[type="checkbox"]`);
                checkboxes.on('change', function() {
                    if ($(`.${containerClass} input[type="checkbox"]:checked`).length > 0) {
                        checkboxes.prop('required', false);
                    } else {
                        checkboxes.prop('required', true);
                    }
                });
            }
            
            setupCheckboxValidation('teacher-class-grid');
            
            // Modern form submission - Add Teacher
            $('#formtambahGuru').on('submit', function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                
                $.ajax({
                    url: "../data/guru.php?action=tambahGuru",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data guru berhasil ditambahkan',
                            confirmButtonColor: '#4CAF50'
                        }).then(() => {
                            guruTable.ajax.reload();
                            $('#formtambahGuru')[0].reset();
                            $('#selectedGuru').hide();
                            $('#modalTambahGuru').modal('hide');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menambah data guru',
                            confirmButtonColor: '#f44336'
                        });
                    }
                });
            });
            
            // Modern edit functionality
            $(document).on('click', '#btn-edit', function() {
                const guruId = $(this).attr('data');
                
                $.ajax({
                    url: "../data/guru.php?action=getGuruById",
                    method: "POST",
                    data: { id: guruId },
                    dataType: "json",
                    success: function(data) {
                        // Reset checkboxes
                        $('.teacher-class-grid input[type="checkbox"]').prop('checked', false);
                        
                        // Fill form data
                        $('#idGuru').val(data.id);
                        $('#nipEdit').val(data.nip || '');
                        $('#emailEdit').val(data.email || '');
                        $('#namaEdit').val(data.nama);
                        $('#alamatEdit').val(data.alamat);
                        $('#no_hpEdit').val(data.no_hp);
                        $('#tipeGuruEdit').val(data.tipeguru);
                        
                        // Handle teacher type display
                        if (data.tipeguru == 1) {
                            $('#nipGroupEdit').show();
                            $('#emailGroupEdit').hide();
                        } else {
                            $('#nipGroupEdit').hide();
                            $('#emailGroupEdit').show();
                        }
                        
                        // Check relevant class checkboxes
                        if (data.kelas1) $('#kelas1Edit').prop('checked', true);
                        if (data.kelas2) $('#kelas2Edit').prop('checked', true);
                        if (data.kelas3) $('#kelas3Edit').prop('checked', true);
                        if (data.kelas4) $('#kelas4Edit').prop('checked', true);
                        if (data.kelas5) $('#kelas5Edit').prop('checked', true);
                        
                        // Update checkbox validation
                        const editCheckboxes = $('.teacher-class-grid input[type="checkbox"]');
                        if (editCheckboxes.filter(':checked').length > 0) {
                            editCheckboxes.prop('required', false);
                        }
                        
                        $('#modalEditGuru').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Tidak dapat memuat data guru',
                            confirmButtonColor: '#f44336'
                        });
                    }
                });
            });
            
            // Modern form submission - Edit Teacher
            $('#formEditGuru').on('submit', function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                
                $.ajax({
                    url: "../data/guru.php?action=editGuru",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data guru berhasil diperbarui',
                            confirmButtonColor: '#4CAF50'
                        }).then(() => {
                            guruTable.ajax.reload();
                            $('#modalEditGuru').modal('hide');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat memperbarui data guru',
                            confirmButtonColor: '#f44336'
                        });
                    }
                });
            });
            
            // Modern delete functionality
            $(document).on('click', '#btn-delete', function() {
                const guruId = $(this).attr('data');
                
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data guru ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f44336',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "../data/guru.php?action=hapusGuru",
                            method: "POST",
                            data: { id: guruId },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data guru berhasil dihapus',
                                    confirmButtonColor: '#4CAF50'
                                }).then(() => {
                                    guruTable.ajax.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghapus data guru',
                                    confirmButtonColor: '#f44336'
                                });
                            }
                        });
                    }
                });
            });
            
            // Reset form when modal is hidden
            $('#modalTambahGuru').on('hidden.bs.modal', function() {
                $('#formtambahGuru')[0].reset();
                $('#selectedGuru').hide();
            });
        });
        
        // Modern animations
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in page content
            document.querySelector('.main-content').style.opacity = '1';
            
            // Add loading state to buttons
            document.querySelectorAll('.btn-add, .btn-warning, .btn-primary').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.type === 'submit') {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
                        this.disabled = true;
                        
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.disabled = false;
                        }, 2000);
                    }
                });
            });
        });
    </script>
</body>

</html>