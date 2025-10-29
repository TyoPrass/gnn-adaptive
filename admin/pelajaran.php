<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// Only admin can access this page
if ($_SESSION['level_user'] != 1) {
    header('location: index.php');
}
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Kelola Mata Pelajaran - MyIRT Adaptive Learning</title>
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
            --mapel-gradient: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
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
            background: var(--mapel-gradient);
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
            background: var(--mapel-gradient);
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
            background: var(--mapel-gradient);
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
            border-color: #FF6B6B;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
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
        
        .badge {
            font-weight: 500;
            font-size: 0.8rem;
            padding: 6px 12px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
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
    </style>
</head>

<body>
    <?php
    $level_user = $_SESSION['level_user'];
    $user_name = $_SESSION['name'] ?? 'Guest';
    $user_login = $_SESSION['login'] ?? 'User';
    
    // Get statistics
    $total_pelajaran = 0;
    $total_topik = 0;
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM pelajaran");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_pelajaran = $row['count'];
    }
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM topic");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_topik = $row['count'];
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
                    
                    <li class="nav-item">
                        <a class="nav-link text-white" href="topik.php">
                            <i class="fas fa-book-open me-1"></i>Topik
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="pelajaran.php">
                            <i class="fas fa-graduation-cap me-1"></i>Mata Pelajaran
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-white" href="murid.php">
                            <i class="fas fa-user-graduate me-1"></i>Murid
                        </a>
                    </li>
                    
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
                <i class="fas fa-graduation-cap"></i>
                Kelola Mata Pelajaran
            </h1>
            <div class="subtitle">
                <i class="fas fa-book me-2"></i>
                Manajemen mata pelajaran untuk sistem pembelajaran adaptif
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
                                <i class="fas fa-graduation-cap fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo $total_pelajaran; ?></h4>
                                <small class="opacity-75">Total Mata Pelajaran</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                <i class="fas fa-book fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo $total_topik; ?></h4>
                                <small class="opacity-75">Total Topik</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">
                    <i class="fas fa-list text-primary me-2"></i>
                    Daftar Mata Pelajaran
                </h3>
                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahPelajaran">
                    <i class="fas fa-plus me-2"></i>Tambah Mata Pelajaran
                </button>
            </div>

            <div class="table-responsive">
                <table id="pelajaranTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>No</th>
                            <th><i class="fas fa-graduation-cap me-2"></i>Nama Mata Pelajaran</th>
                            <th><i class="fas fa-book me-2"></i>Jumlah Topik</th>
                            <th><i class="fas fa-cogs me-2"></i>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pelajaran -->
    <div class="modal fade" id="modalTambahPelajaran" tabindex="-1" aria-labelledby="modalTambahPelajaranLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formTambahPelajaran">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahPelajaranLabel">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Mata Pelajaran Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label for="mapel" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>Nama Mata Pelajaran
                            </label>
                            <input type="text" class="form-control" name="mapel" id="mapel" placeholder="Contoh: Fisika, Kimia, Matematika..." required>
                            <div class="form-text">Masukkan nama mata pelajaran yang akan ditambahkan ke sistem.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pelajaran -->
    <div class="modal fade" id="modalEditPelajaran" tabindex="-1" aria-labelledby="modalEditPelajaranLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditPelajaran">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditPelajaranLabel">
                            <i class="fas fa-edit me-2"></i>Edit Mata Pelajaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id_mapel" id="idMapelEdit" value="" />
                        <div class="mb-4">
                            <label for="mapelEdit" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>Nama Mata Pelajaran
                            </label>
                            <input type="text" class="form-control" name="mapel" id="mapelEdit" placeholder="Masukkan nama mata pelajaran..." required>
                            <div class="form-text">Ubah nama mata pelajaran sesuai kebutuhan.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-save me-2"></i>Update
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
        table = $('#pelajaranTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../data/pelajaran.php?action=getPelajaran",
                "dataType": "json",
                "type": "POST",
            },
            "columnDefs": [
                {
                    "targets": [0, 3],
                    "orderable": false,
                },
            ],
            "columns": [{
                    data: "no",
                    name: "no"
                },
                {
                    data: "mapel",
                    name: "mapel"
                },
                {
                    data: "jumlah_topik",
                    name: "jumlah_topik"
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

        // Submit tambah pelajaran
        $('#formTambahPelajaran').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "../data/pelajaran.php?action=tambahPelajaran",
                method: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Mata pelajaran baru berhasil ditambahkan',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.ajax.reload();
                    $('#modalTambahPelajaran').modal('hide');
                    $('#formTambahPelajaran')[0].reset();
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menambah mata pelajaran'
                    });
                    console.log(e)
                }
            });
        });

        // Tampilkan modal edit pelajaran
        $('#pelajaranTable').on('click', '#btn-edit', function() {
            var id = $(this).attr('data');
            $.ajax({
                url: "../data/pelajaran.php?action=getPelajaranById",
                method: "post",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(data) {
                    $('#mapelEdit').val(data['mapel']);
                    $('#idMapelEdit').val(data['id']);
                    $('#modalEditPelajaran').modal('show');
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat mengambil data mata pelajaran'
                    });
                    console.log(e);
                }
            });
        });

        // Submit edit pelajaran
        $('#formEditPelajaran').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '../data/pelajaran.php?action=editPelajaran',
                method: "post",
                data: $(this).serialize(),
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Mata pelajaran berhasil diperbarui',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.ajax.reload();
                    $('#modalEditPelajaran').modal('hide');
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui mata pelajaran'
                    });
                    console.log(e);
                }
            });
        });

        // Konfirmasi hapus pelajaran
        $('#pelajaranTable').on('click', '#btn-delete', function() {
            const id = $(this).attr('data');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus mata pelajaran ini? Semua topik terkait akan kehilangan referensi pelajaran!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../data/pelajaran.php?action=hapusPelajaran',
                        method: 'post',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Mata pelajaran berhasil dihapus',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.ajax.reload();
                        },
                        error: function(e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus mata pelajaran'
                            });
                            console.log(e);
                        }
                    });
                }
            });
        });
    });
    </script>
</body>

</html>
