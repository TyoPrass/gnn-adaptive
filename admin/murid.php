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

    <title>Kelola Murid - MyIRT Adaptive Learning</title>
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
            --student-gradient: linear-gradient(135deg, #43e97b 0%, #667eea 100%);
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
            background: var(--student-gradient);
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
            background: var(--biology-gradient);
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
            background: var(--biology-gradient);
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
            background-color: rgba(67, 233, 123, 0.05);
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
            background: var(--ocean-gradient);
            color: white;
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(168, 237, 234, 0.4);
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
            background: var(--biology-gradient);
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
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.4);
            color: white;
        }
        
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: var(--biology-gradient);
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
            border-color: #43e97b;
            box-shadow: 0 0 0 0.2rem rgba(67, 233, 123, 0.25);
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-success {
            background: var(--biology-gradient);
            color: white;
        }
        
        .badge-warning {
            background: var(--warm-gradient);
            color: white;
        }
        
        .badge-danger {
            background: var(--secondary-gradient);
            color: white;
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
            background: var(--biology-gradient);
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
        .table-container {
            animation: fadeInUp 0.6s ease;
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
            background: var(--biology-gradient) !important;
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
                            <a class="nav-link text-white active" href="murid.php">
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
                <i class="fas fa-user-graduate"></i>
                Kelola Data Murid
            </h1>
            <div class="subtitle">
                <i class="fas fa-users me-2"></i>
                Manajemen siswa dalam sistem pembelajaran adaptif
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
                        Daftar Murid
                    </h3>
                    <p class="text-muted mb-0">Kelola data siswa, nilai quiz, dan informasi akademik</p>
                </div>
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahMurid">
                    <i class="fas fa-plus"></i>
                    Tambah Murid
                </button>
            </div>
            
            <div class="table-container">
                <table id="muridTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>No</th>
                            <th><i class="fas fa-user me-1"></i>Murid</th>
                            <th><i class="fas fa-id-card me-1"></i>NIS/Login</th>
                            <th><i class="fas fa-map-marker-alt me-1"></i>Alamat</th>
                            <th><i class="fas fa-school me-1"></i>Kelas</th>
                            <th><i class="fas fa-brain me-1"></i>Quiz Adaptive</th>
                            <th><i class="fas fa-laptop me-1"></i>Quiz E-Learning</th>
                            <th><i class="fas fa-cogs me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Murid -->
    <div class="modal fade" id="modalTambahMurid" tabindex="-1" aria-labelledby="modalTambahMuridLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../data/murid.php?action=tambahMurid" method="POST" id="formtambahMurid">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahMuridLabel">
                            <i class="fas fa-user-plus me-2"></i>Tambah Murid Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="nis" class="col-sm-2 col-form-label">NIS</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nis" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="alamat" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="no_hp" class="col-sm-2 col-form-label">No. HP</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="no_hp" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM class");
                                    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);

                                    foreach ($result as $r) { ?>
                                    <option value="<?php echo $r['id'] ?>"><?php echo $r['class_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Murid -->
    <div class="modal fade" id="modalEditMurid" tabindex="-1" aria-labelledby="modalEditMuridLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../data/murid.php?action=editMurid" method="POST" id="formEditMurid">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditMuridLabel">
                            <i class="fas fa-user-edit me-2"></i>Edit Data Murid
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="idMurid" value="" />
                        <div class="mb-3 row">
                            <label for="nis" class="col-sm-2 col-form-label">NIS</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="editNis" id="editNis" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="editNama" id="editNama" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="editAlamat" id="editAlamat" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="no_hp" class="col-sm-2 col-form-label">No. HP</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="edit_no_hp" id="edit_no_hp" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="editKelas" id="editKelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM class");
                                    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);

                                    foreach ($result as $r) { ?>
                                    <option value="<?php echo $r['id'] ?>"><?php echo $r['class_name'] ?></option>
                                    <?php }

                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info text-white">Simpan</button>
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
        table = $('#muridTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../data/murid.php?action=getMurid",
                "dataType": "json",
                "type": "POST",
            },
            "columnDefs": [ //Set column definition initialisation properties.
                {
                    "targets": [0, 4, 5], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],
            "columns": [{
                    data: "no",
                    name: "no"
                },
                {
                    data: "murid",
                    name: "murid"
                },
                {
                    data: "nis",
                    name: "nis",
                },
                {
                    data: "alamat",
                    name: "alamat",
                },
                {
                    data: "kelas",
                    name: "kelas",
                }, 
                {
                    data: "quiz_adaptive",
                    name: "quiz_adaptive",
                }, 
                {
                    data: "quiz_e_learning",
                    name: "quiz_e_learning",
                }, 
                {
                    data: "action",
                    name: "action"
                }
            ]
        });


        // Submit Tambah Murid
        $('#formtambahMurid').submit(function() {
            $.ajax({
                url: "../data/murid.php?action=tambahMurid",
                method: "POST",
                data: $('#formtambahMurid').serialize(),
                success: function(data) {
                    Swal.fire(
                        '',
                        'Sukses Tambah Murid',
                        'success'
                    );
                    table.ajax.reload();
                    $('#modalTambahMurid').modal('hide');
                },
                error: function(e) {
                    console.log(e)
                }
            })
            return false;
        })

        // Tampilkan modal Edit Murid
        $('#muridTable').on('click', '#btn-edit', function() {
            $.ajax({
                url: "../data/murid.php?action=getMuridById",
                method: "post",
                type: "ajax",
                data: {
                    id: $(this).attr('data'),
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#idMurid').val(data['id']);
                    $('#editNis').val(data['nis']);
                    $('#editNama').val(data['nama']);
                    $('#editAlamat').val(data['alamat']);
                    $('#edit_no_hp').val(data['no_hp']);
                    $('#editKelas').val(data['kelas']);
                },
                error: function(e) {
                    console.log(e);
                }
            })
            $('#modalEditMurid').modal('show');
        })

        // Submit edit murid
        $('#formEditMurid').submit(function() {
            $.ajax({
                url: '../data/murid.php?action=editMurid',
                method: "post",
                type: "ajax",
                data: $('#formEditMurid').serialize(),
                success: function(data) {
                    Swal.fire(
                        '',
                        'Update Murid Berhasil',
                        'success'
                    );
                    table.ajax.reload();
                    $('#modalEditMurid').modal('hide');
                },
                error: function(e) {
                    console.log(e);
                }
            });
            return false;
        })

        // Konfirmasi hapus murid
        $('#muridTable').on('click', '#btn-delete', function() {
            Swal.fire({
                title: "Konfirmasi Hapus Murid?!",
                text: "Apakah anda yakin untuk manghapus data Murid ini?",
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Ya!',
                denyButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hapus terkonfirmasi
                    $.ajax({
                        url: '../data/murid.php?action=hapusMurid',
                        method: 'post',
                        type: 'ajax',
                        data: {
                            id: $(this).attr('data')
                        },
                        success: function(data) {
                            Swal.fire(
                                '',
                                'Hapus Murid Berhasil',
                                'success'
                            );
                            table.ajax.reload();
                            $('#modalEditMurid').modal('hide');
                        },
                        error: function(e) {
                            console.log(e);
                        }
                    })
                } else {
                    // Batal hapus
                    Swal.fire(
                        'Cancelled',
                        'Hapus Murid Dibatalkan',
                        'error'
                    );
                }
            })
        })
    });
    </script>
</body>

</html>