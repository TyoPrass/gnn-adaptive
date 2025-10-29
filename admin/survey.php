<?php

include('../config/db.php');
session_start();

// jika belum login redirect ke login
if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// Only admin can access this page
if (!isset($_SESSION['level_user']) || $_SESSION['level_user'] != 1) {
    header('location: index.php');
    exit();
}
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Kelola Survey | MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5.2.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.3.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --survey-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --biology-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
            background: var(--survey-gradient);
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
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .content-body {
            padding: 2rem;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        
        .stats-card.ketertarikan {
            border-color: #667eea;
        }
        
        .stats-card.keterlibatan {
            border-color: #43e97b;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .stats-label {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .table-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .table thead th {
            background: var(--survey-gradient);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
        }
        
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        
        .badge-category {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .badge-ketertarikan {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .badge-keterlibatan {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }
        
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 0.2rem;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            background: var(--survey-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .navbar-nav .nav-link.active {
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            font-weight: 600;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stats-card, .table-card {
            animation: fadeInUp 0.6s ease;
        }
    </style>
</head>

<body>
    <?php
    $level_user = $_SESSION['level_user'];
    $user_name = $_SESSION['name'] ?? 'Guest';
    $user_login = $_SESSION['login'] ?? 'User';
    
    // Get statistics
    $total_survey = 0;
    $total_ketertarikan = 0;
    $total_keterlibatan = 0;
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM survey_question");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_survey = $row['count'];
    }
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM survey_question WHERE category = '1'");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_ketertarikan = $row['count'];
    }
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM survey_question WHERE category = '2'");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_keterlibatan = $row['count'];
    }
    
    // Get pelajaran list for dropdown
    $pelajaran_list = [];
    $result = mysqli_query($conn, "SELECT * FROM pelajaran ORDER BY mapel ASC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $pelajaran_list[] = $row;
        }
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
                        <a class="nav-link text-white" href="murid.php">
                            <i class="fas fa-user-graduate me-1"></i>Murid
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="topik.php">
                            <i class="fas fa-book-open me-1"></i>Materi
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
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="survey.php">
                            <i class="fas fa-poll me-1"></i>Survey
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
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-poll"></i>
                Kelola Survey Motivasi
            </h1>
            <p class="mb-0 mt-2 opacity-75">
                <i class="fas fa-info-circle me-2"></i>
                Kelola pertanyaan survey untuk mengukur ketertarikan dan keterlibatan siswa
            </p>
        </div>

        <div class="content-body">
            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card" style="border-color: #667eea;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="stats-number text-primary mb-0"><?php echo $total_survey; ?></p>
                                <p class="stats-label mb-0">
                                    <i class="fas fa-clipboard-list me-1"></i>Total Pertanyaan
                                </p>
                            </div>
                            <div class="text-primary" style="font-size: 3rem; opacity: 0.2;">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card ketertarikan">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="stats-number mb-0" style="color: #667eea;"><?php echo $total_ketertarikan; ?></p>
                                <p class="stats-label mb-0">
                                    <i class="fas fa-heart me-1"></i>Ketertarikan
                                </p>
                            </div>
                            <div style="font-size: 3rem; opacity: 0.2; color: #667eea;">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card keterlibatan">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="stats-number mb-0" style="color: #43e97b;"><?php echo $total_keterlibatan; ?></p>
                                <p class="stats-label mb-0">
                                    <i class="fas fa-users me-1"></i>Keterlibatan
                                </p>
                            </div>
                            <div style="font-size: 3rem; opacity: 0.2; color: #43e97b;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSurvey">
                    <i class="fas fa-plus me-2"></i>Tambah Pertanyaan Survey
                </button>
                <button class="btn btn-outline-secondary" onclick="refreshTable()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>

            <!-- Table -->
            <div class="table-card">
                <h5 class="mb-3">
                    <i class="fas fa-table text-primary me-2"></i>
                    Daftar Pertanyaan Survey
                </h5>
                <div class="table-responsive">
                    <table id="surveyTable" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pertanyaan</th>
                                <th>Kategori</th>
                                <th>Mata Pelajaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Survey -->
    <div class="modal fade" id="modalTambahSurvey" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Pertanyaan Survey
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTambahSurvey">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pertanyaan Survey <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="question" rows="3" required 
                                placeholder="Masukkan pertanyaan survey..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Ketertarikan (Interest)</option>
                                <option value="2">Keterlibatan (Engagement)</option>
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Ketertarikan: sikap & minat siswa | Keterlibatan: kemampuan & prestasi
                            </small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_mapel" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                <?php foreach ($pelajaran_list as $p) { ?>
                                    <option value="<?php echo $p['id_mapel']; ?>"><?php echo $p['mapel']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Survey -->
    <div class="modal fade" id="modalEditSurvey" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Pertanyaan Survey
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditSurvey">
                    <input type="hidden" name="id" id="editId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pertanyaan Survey <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="question" id="editQuestion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" id="editCategory" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Ketertarikan (Interest)</option>
                                <option value="2">Keterlibatan (Engagement)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_mapel" id="editIdMapel" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                <?php foreach ($pelajaran_list as $p) { ?>
                                    <option value="<?php echo $p['id_mapel']; ?>"><?php echo $p['mapel']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        let surveyTable;
        
        $(document).ready(function() {
            // Initialize DataTable
            surveyTable = $('#surveyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "../data/survey.php?action=getSurvey",
                    type: "POST"
                },
                columns: [
                    { data: "no", width: "5%" },
                    { 
                        data: "question",
                        width: "45%",
                        render: function(data, type, row) {
                            return '<div class="text-wrap">' + data + '</div>';
                        }
                    },
                    { 
                        data: "category",
                        width: "15%",
                        render: function(data, type, row) {
                            if (data == '1' || data == 'Ketertarikan') {
                                return '<span class="badge-category badge-ketertarikan"><i class="fas fa-heart me-1"></i>Ketertarikan</span>';
                            } else {
                                return '<span class="badge-category badge-keterlibatan"><i class="fas fa-users me-1"></i>Keterlibatan</span>';
                            }
                        }
                    },
                    { 
                        data: "mapel",
                        width: "15%",
                        render: function(data, type, row) {
                            return '<span class="badge bg-info">' + (data || '-') + '</span>';
                        }
                    },
                    { 
                        data: "action",
                        width: "20%",
                        orderable: false
                    }
                ],
                order: [[0, 'asc']],
                language: {
                    processing: "Memuat data...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
            
            // Form Tambah Survey
            $('#formTambahSurvey').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../data/survey.php?action=tambahSurvey',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalTambahSurvey').modal('hide');
                        $('#formTambahSurvey')[0].reset();
                        surveyTable.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pertanyaan survey berhasil ditambahkan',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menambah data'
                        });
                    }
                });
            });
            
            // Form Edit Survey
            $('#formEditSurvey').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../data/survey.php?action=editSurvey',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalEditSurvey').modal('hide');
                        surveyTable.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pertanyaan survey berhasil diupdate',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengupdate data'
                        });
                    }
                });
            });
        });
        
        // Edit button handler
        $(document).on('click', '#btn-edit', function() {
            const id = $(this).attr('data');
            $.ajax({
                url: '../data/survey.php?action=getSurveyById',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    $('#editId').val(data.id);
                    $('#editQuestion').val(data.question);
                    $('#editCategory').val(data.category);
                    $('#editIdMapel').val(data.id_mapel);
                    $('#modalEditSurvey').modal('show');
                }
            });
        });
        
        // Delete button handler
        $(document).on('click', '#btn-delete', function() {
            const id = $(this).attr('data');
            Swal.fire({
                title: 'Hapus Pertanyaan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../data/survey.php?action=hapusSurvey',
                        method: 'POST',
                        data: { id: id },
                        success: function(response) {
                            surveyTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Pertanyaan survey berhasil dihapus',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus data'
                            });
                        }
                    });
                }
            });
        });
        
        function refreshTable() {
            surveyTable.ajax.reload();
            Swal.fire({
                icon: 'success',
                title: 'Diperbarui!',
                text: 'Data berhasil diperbarui',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    </script>
</body>

</html>
