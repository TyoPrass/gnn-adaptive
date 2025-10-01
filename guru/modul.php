<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}
// mengambil id subtopik dari URL
$id = $_GET['subtopik'];


// mengambil data sub topik dari database
$sql = "SELECT * FROM sub_topic WHERE id = '{$id}'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_array($query, MYSQLI_ASSOC);
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Modul Pembelajaran - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5.2.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.3.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- DataTables with Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
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
            --module-gradient: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            --learning-gradient: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
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
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .page-header .subtitle {
            margin-top: 0.5rem;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .breadcrumb-card {
            background: white;
            border-radius: 15px;
            padding: 1rem 2rem;
            margin: 1rem 2rem 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 4px solid var(--learning-gradient);
        }
        
        .data-table-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transform: translateY(-1px);
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: rgba(0,0,0,0.05);
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }
        
        .btn-edit {
            background: var(--success-gradient);
            color: white;
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-delete {
            background: var(--secondary-gradient);
            color: white;
        }
        
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
            color: white;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: var(--biology-gradient);
            border: none;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.4);
        }
        
        .btn-info {
            background: var(--ocean-gradient);
            border: none;
            color: white;
        }
        
        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(168, 237, 234, 0.4);
            color: white;
        }
        
        .btn-warning {
            background: var(--warm-gradient);
            border: none;
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 112, 154, 0.4);
            color: white;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            background: var(--module-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
            padding: 1.5rem;
        }
        
        .modal-title {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .floating-stats {
            position: fixed;
            top: 100px;
            right: 30px;
            background: rgba(255,255,255,0.95);
            padding: 1rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            z-index: 1000;
            min-width: 200px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .stat-item:hover {
            background: rgba(102, 126, 234, 0.1);
        }
        
        .stat-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }
        
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.5rem;
        }
        
        .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: none;
            color: #667eea;
        }
        
        .page-link:hover {
            background-color: #667eea;
            color: white;
        }
        
        .page-item.active .page-link {
            background: var(--primary-gradient);
            border: none;
        }
        
        /* Navbar styling */
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
        
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }
        
        .breadcrumb-item {
            display: flex;
            align-items: center;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: '›';
            color: #6c757d;
            font-weight: bold;
            margin: 0 0.5rem;
        }
        
        .breadcrumb-item.active {
            color: var(--module-gradient);
            font-weight: 600;
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
        
        .data-table-container {
            animation: fadeInUp 0.6s ease;
        }
        
        @media (max-width: 768px) {
            .floating-stats {
                position: relative;
                top: auto;
                right: auto;
                margin: 1rem;
                width: calc(100% - 2rem);
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .main-content {
                margin: 10px;
            }
        }
    </style>
</head>

<body>
    <?php
    // Level user handling untuk akses kontrol
    if (!isset($_SESSION['level_user'])) {
        header('location: ../sign-in.php');
        exit();
    }
    
    $level_user = $_SESSION['level_user'];
    $user_name = $_SESSION['name'] ?? 'Guest';
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
                            <li><h6 class="dropdown-header"><?php echo $_SESSION['login']; ?></h6></li>
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
                <i class="fas fa-cube"></i>
                Modul Pembelajaran
            </h1>
            <div class="subtitle">
                <i class="fas fa-layer-group me-2"></i>
                Kelola modul dalam sub topik: <strong><?php echo htmlspecialchars($result['sub_topic_desc']); ?></strong>
                <span class="badge bg-light text-dark ms-3">
                    <i class="fas fa-user me-1"></i>
                    <?php echo htmlspecialchars($user_name); ?>
                </span>
            </div>
        </div>

        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-card">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="index.php" class="text-decoration-none">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="topik.php" class="text-decoration-none">
                            <i class="fas fa-book-open me-1"></i>Topik
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="subtopik.php?topik=<?php echo $result['topic_id']; ?>" class="text-decoration-none">
                            <i class="fas fa-sitemap me-1"></i>Sub Topik
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-cube me-1"></i><?php echo htmlspecialchars($result['sub_topic_desc']); ?>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Enhanced Data Table Container -->
        <div class="data-table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">
                        <i class="fas fa-table text-primary me-2"></i>
                        Daftar Modul Pembelajaran
                    </h3>
                    <p class="text-muted mb-0">Modul dari sub topik: <strong><?php echo htmlspecialchars($result['sub_topic_desc']); ?></strong></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="subtopik.php?topik=<?php echo $result['topic_id']; ?>" class="btn btn-info">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Sub Topik
                    </a>
                    <button class="btn btn-warning" id="btnRefresh">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                    <?php if ($level_user == 1 || $level_user == 2): // Admin atau Guru ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahModul">
                        <i class="fas fa-plus me-2"></i>Tambah Modul
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table id="moduleTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>No</th>
                            <th><i class="fas fa-cube me-2"></i>Nama Modul</th>
                            <?php if ($level_user == 1 || $level_user == 2): // Admin atau Guru ?>
                            <th><i class="fas fa-cogs me-2"></i>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan dimuat melalui DataTables AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Modul -->
    <?php if ($level_user == 1 || $level_user == 2): // Admin atau Guru ?>
    <div class="modal fade" id="modalTambahModul" tabindex="-1" aria-labelledby="modalTambahModulLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formTambahModul">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahModulLabel">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Modul Pembelajaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="subtopik" value="<?php echo $id ?>">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-sitemap me-2"></i>Sub Topik
                            </label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($result['sub_topic_desc']); ?>" disabled>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Modul akan ditambahkan ke dalam sub topik ini.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="modulBaru" class="form-label">
                                <i class="fas fa-cube me-2"></i>Nama Modul
                            </label>
                            <input type="text" class="form-control" name="modul" id="modulBaru" required 
                                   placeholder="Masukkan nama modul pembelajaran">
                            <div class="form-text">
                                <i class="fas fa-lightbulb me-1"></i>
                                Contoh: Pengenalan Sel, Struktur DNA, Sistem Peredaran Darah, dll.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Simpan Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal Edit Modul -->
    <?php if ($level_user == 1 || $level_user == 2): // Admin atau Guru ?>
    <div class="modal fade" id="modalEditModul" tabindex="-1" aria-labelledby="modalEditModulLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditModul">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditModulLabel">
                            <i class="fas fa-edit me-2"></i>Edit Modul Pembelajaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="idModulEdit" value="" />
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-sitemap me-2"></i>Sub Topik
                            </label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($result['sub_topic_desc']); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="modulEdit" class="form-label">
                                <i class="fas fa-cube me-2"></i>Nama Modul
                            </label>
                            <input type="text" class="form-control" id="modulEdit" name="modul" required
                                   placeholder="Masukkan nama modul pembelajaran">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Perbarui nama modul sesuai kebutuhan pembelajaran.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modern JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    var table;
    var levelUser = <?php echo json_encode($level_user); ?>;
    var subtopikId = <?php echo $id ?>;
    
    $(document).ready(function() {
        // Configure DataTable columns based on user level
        var columns = [
            { data: "no", name: "no" },
            { data: "module_desc", name: "module_desc" }
        ];
        
        var columnDefs = [
            { "targets": [0], "orderable": false }
        ];
        
        // Add action column only for Admin and Guru
        if (levelUser == 1 || levelUser == 2) {
            columns.push({ data: "action", name: "action" });
            columnDefs[0].targets.push(2);
        }
        
        // Initialize modern DataTable
        table = $('#moduleTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../data/materi.php?action=getModul",
                "data": {
                    "subtopik": subtopikId,
                },
                "dataType": "json",
                "type": "POST",
            },
            "columnDefs": columnDefs,
            "columns": columns,
            "language": {
                "processing": '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data modul tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ modul",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 modul",
                "infoFiltered": "(difilter dari _MAX_ total modul)",
                "search": "Cari modul:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
        
        // Update statistics
        function updateStats() {
            $.ajax({
                url: '../data/materi.php?action=getModulStats',
                method: 'GET',
                data: { subtopik: subtopikId },
                dataType: 'json',
                success: function(data) {
                    $('#totalModul').text(data.totalModul || '0');
                },
                error: function() {
                    $('#totalModul').text('Error');
                }
            });
        }
        
        // Update time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            $('#currentTime').text(timeString);
        }
        
        // Initialize stats and time
        updateStats();
        updateTime();
        setInterval(updateTime, 60000); // Update every minute
        
        // Refresh button
        $('#btnRefresh').click(function() {
            table.ajax.reload();
            updateStats();
            
            // Visual feedback
            $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...');
            setTimeout(() => {
                $(this).html('<i class="fas fa-sync-alt me-2"></i>Refresh');
            }, 1000);
        });
        
        // Form Tambah Modul (only for Admin and Guru)
        if (levelUser == 1 || levelUser == 2) {
            $('#formTambahModul').submit(function(e) {
                e.preventDefault();
                
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');
                submitBtn.prop('disabled', true);
                
                $.ajax({
                    url: "../data/materi.php?action=tambahModul",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Modul berhasil ditambahkan',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        table.ajax.reload();
                        updateStats();
                        $('#modalTambahModul').modal('hide');
                        $('#formTambahModul')[0].reset();
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan saat menyimpan modul';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                    },
                    complete: function() {
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                    }
                });
            });
        }
        
        // Edit Modul (only for Admin and Guru)
        if (levelUser == 1 || levelUser == 2) {
            $(document).on('click', '#btn-edit', function() {
                const modulId = $(this).attr('data');
                
                $.ajax({
                    url: "../data/materi.php?action=getModulById",
                    method: "POST",
                    data: { modul: modulId },
                    dataType: "json",
                    success: function(data) {
                        $('#modulEdit').val(data.modul);
                        $('#idModulEdit').val(data.id);
                        $('#modalEditModul').modal('show');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data modul'
                        });
                    }
                });
            });
            
            // Form Edit Modul
            $('#formEditModul').submit(function(e) {
                e.preventDefault();
                
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Mengupdate...');
                submitBtn.prop('disabled', true);
                
                $.ajax({
                    url: '../data/materi.php?action=editModul',
                    method: "POST",
                    data: {
                        id: $('#idModulEdit').val(),
                        modul: $('#modulEdit').val()
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Modul berhasil diupdate',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        table.ajax.reload();
                        updateStats();
                        $('#modalEditModul').modal('hide');
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan saat mengupdate modul';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                    },
                    complete: function() {
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                    }
                });
            });
        }
        
        // Delete Modul (only for Admin and Guru)
        if (levelUser == 1 || levelUser == 2) {
            $(document).on('click', '#btn-delete', function() {
                const modulId = $(this).attr('data');
                const modulName = $(this).closest('tr').find('td:eq(1)').text();
                
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: `Apakah Anda yakin ingin menghapus modul <strong>${modulName}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan!</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus!',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                    backdrop: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        $.ajax({
                            url: '../data/materi.php?action=hapusModul',
                            method: 'POST',
                            data: { id: modulId },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'Modul berhasil dihapus',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                
                                table.ajax.reload();
                                updateStats();
                            },
                            error: function(xhr) {
                                let errorMsg = 'Terjadi kesalahan saat menghapus modul';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            });
        }
        
        // Enhanced animations and interactions
        $('.btn').hover(
            function() {
                $(this).css('transform', 'translateY(-2px)');
            },
            function() {
                $(this).css('transform', 'translateY(0)');
            }
        );
        
        // Reset forms when modals are hidden
        $('#modalTambahModul').on('hidden.bs.modal', function() {
            $('#formTambahModul')[0].reset();
        });
        
        $('#modalEditModul').on('hidden.bs.modal', function() {
            $('#formEditModul')[0].reset();
        });
        
        // Breadcrumb click animation
        $('.breadcrumb-item a').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            // Show loading animation
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
            
            toast.fire({
                icon: 'info',
                title: 'Navigasi...',
                background: '#43e97b',
                color: 'white'
            });
            
            setTimeout(() => {
                window.location.href = href;
            }, 500);
        });
    });
    </script>
</body>

</html>