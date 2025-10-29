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

    <title>Hasil Pre-Test | MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    
    <!-- Modern CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --biology-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --result-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --danger-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --hover-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .main-content {
            opacity: 0;
            animation: fadeIn 0.8s ease-in-out forwards;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        
        /* Modern Page Header */
        .page-header {
            background: var(--result-gradient);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="%23fff" stop-opacity=".1"/><stop offset="100%" stop-color="%23fff" stop-opacity="0"/></radialGradient></defs><circle cx="10" cy="10" r="10" fill="url(%23a)"/></svg>') repeat;
            opacity: 0.1;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .subtitle {
            font-size: 1.1rem;
            margin-top: 0.5rem;
            opacity: 0.9;
        }
        
        /* Content Card */
        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .content-card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-2px);
        }
        
        /* Statistics Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--result-gradient);
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--result-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #666;
            font-weight: 500;
            margin-top: 0.5rem;
        }
        
        /* Level Badge Styles */
        .level-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .level-beginner {
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            color: #8b1538;
        }
        
        .level-intermediate {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #8b4513;
        }
        
        .level-advanced {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #2d5aa0;
        }
        
        .level-expert {
            background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);
            color: #6b46c1;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-completed {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }
        
        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }
        
        .status-not-started {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }
        
        /* Modern Table Styling */
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .table {
            margin: 0;
        }
        
        .table thead th {
            background: var(--result-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
            font-size: 0.9rem;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e5e7eb;
        }
        
        /* DataTables Custom Styling */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.2s ease;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--result-gradient) !important;
            border-color: transparent !important;
            color: white !important;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                padding: 2rem 0;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .content-card {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }
        
        /* Loading Animation */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
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
    
    // Get statistics
    $total_students = 0;
    $completed_pretest = 0;
    $completed_survey = 0;
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM student");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_students = $row['count'];
    }
    
    // Count completed pretest (using correct table name)
    $result = mysqli_query($conn, "SELECT COUNT(DISTINCT student_id) as count FROM pre_test_result WHERE 1");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $completed_pretest = $row['count'];
    }
    
    // Count completed survey (using correct table name)
    $result = mysqli_query($conn, "SELECT COUNT(DISTINCT student_id) as count FROM survey_result WHERE 1");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $completed_survey = $row['count'];
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
                        <a class="nav-link text-white active" href="hasil-pre-test.php">
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
            <div class="container">
                <h1>
                    <i class="fas fa-chart-line"></i>
                    Hasil Pre-Test & Level
                </h1>
                <div class="subtitle">
                    <i class="fas fa-analytics me-2"></i>
                    Pantau progress dan level pembelajaran siswa berdasarkan hasil pre-test dan survei
                    <span class="badge bg-light text-dark ms-3">
                        <i class="fas fa-calendar me-1"></i>
                        <?php echo date('d F Y'); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_students; ?></div>
                        <div class="stat-label">
                            <i class="fas fa-users me-1"></i>Total Siswa
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $completed_pretest; ?></div>
                        <div class="stat-label">
                            <i class="fas fa-clipboard-check me-1"></i>Selesai Pre-Test
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $completed_survey; ?></div>
                        <div class="stat-label">
                            <i class="fas fa-poll me-1"></i>Selesai Survei
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Card -->
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="mb-1">
                            <i class="fas fa-table text-primary me-2"></i>
                            Data Hasil Level Siswa
                        </h3>
                        <p class="text-muted mb-0">Tabel lengkap hasil pre-test, survei, dan level pembelajaran siswa</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportData()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                
                <div class="table-container">
                    <table id="muridTable" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>No</th>
                                <th><i class="fas fa-user-graduate me-1"></i>Murid</th>
                                <th><i class="fas fa-id-card me-1"></i>NIS/Login</th>
                                <th><i class="fas fa-school me-1"></i>Kelas</th>
                                <th><i class="fas fa-poll me-1"></i>Survei</th>
                                <th><i class="fas fa-clipboard-check me-1"></i>Pre-Test</th>
                                <th><i class="fas fa-layer-group me-1"></i>Level</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        let muridTable;
        
        $(document).ready(function() {
            // Initialize modern DataTable
            muridTable = $('#muridTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "../data/murid.php?action=getHasilPretest",
                    type: "POST",
                    dataType: "json",
                    error: function(xhr, error, thrown) {
                        console.error('DataTable Ajax Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Loading Data',
                            text: 'Tidak dapat memuat data hasil pre-test. Silakan refresh halaman.',
                            confirmButtonColor: '#f44336'
                        });
                    }
                },
                columnDefs: [{
                    targets: [0, 4, 5, 6],
                    orderable: false
                }],
                columns: [
                    { 
                        data: "no", 
                        name: "no",
                        width: "5%"
                    },
                    { 
                        data: "murid", 
                        name: "murid",
                        width: "20%",
                        render: function(data, type, row) {
                            return `<div class="d-flex align-items-center">
                                        <div class="avatar me-2">
                                            <i class="fas fa-user-circle text-primary fa-2x"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">${data}</div>
                                            <small class="text-muted">Siswa</small>
                                        </div>
                                    </div>`;
                        }
                    },
                    { 
                        data: "nis", 
                        name: "nis",
                        width: "15%",
                        render: function(data, type, row) {
                            return `<span class="badge bg-secondary">${data || '-'}</span>`;
                        }
                    },
                    { 
                        data: "kelas", 
                        name: "kelas",
                        width: "10%",
                        render: function(data, type, row) {
                            return `<span class="badge bg-primary">${data || '-'}</span>`;
                        }
                    },
                    { 
                        data: "hasilSurvei", 
                        name: "hasilSurvei",
                        width: "15%",
                        render: function(data, type, row) {
                            if (data && data.includes('Level')) {
                                return `<span class="status-badge status-completed">
                                            <i class="fas fa-check me-1"></i>${data}
                                        </span>`;
                            } else {
                                return `<span class="status-badge status-pending">
                                            <i class="fas fa-clock me-1"></i>${data || 'Belum'}
                                        </span>`;
                            }
                        }
                    },
                    { 
                        data: "hasilIrt", 
                        name: "hasilIrt",
                        width: "15%",
                        render: function(data, type, row) {
                            if (data && data.includes('Level ') && !data.includes('belum')) {
                                return `<span class="status-badge status-completed">
                                            <i class="fas fa-check me-1"></i>${data}
                                        </span>`;
                            } else if (data && data.includes('belum')) {
                                return `<span class="status-badge status-pending">
                                            <i class="fas fa-hourglass me-1"></i>${data}
                                        </span>`;
                            } else {
                                return `<span class="status-badge status-not-started">
                                            <i class="fas fa-clock me-1"></i>${data || 'Belum'}
                                        </span>`;
                            }
                        }
                    },
                    { 
                        data: "hasilPreTest", 
                        name: "hasilPreTest",
                        width: "20%",
                        render: function(data, type, row) {
                            if (data && data.includes('Level ') && !data.includes('belum')) {
                                // Extract level number from string like "Level 1", "Level 2", etc.
                                const levelMatch = data.match(/Level\s+(\d+)/i);
                                let levelClass = 'level-beginner';
                                let levelText = 'Pemula';
                                let levelIcon = 'fa-seedling';
                                
                                if (levelMatch) {
                                    const levelNum = parseInt(levelMatch[1]);
                                    if (levelNum >= 3) {
                                        levelClass = 'level-expert';
                                        levelText = 'Ahli';
                                        levelIcon = 'fa-crown';
                                    } else if (levelNum >= 2) {
                                        levelClass = 'level-advanced';
                                        levelText = 'Mahir';
                                        levelIcon = 'fa-star';
                                    } else {
                                        levelClass = 'level-intermediate';
                                        levelText = 'Menengah';
                                        levelIcon = 'fa-leaf';
                                    }
                                }
                                
                                return `<div class="level-badge ${levelClass}">
                                            <i class="fas ${levelIcon} me-1"></i>
                                            ${data}
                                        </div>`;
                            } else if (data && data.includes('belum')) {
                                return `<span class="status-badge status-pending">
                                            <i class="fas fa-hourglass me-1"></i>${data}
                                        </span>`;
                            } else {
                                return `<span class="status-badge status-not-started">
                                            <i class="fas fa-clock me-1"></i>${data || 'Belum Dimulai'}
                                        </span>`;
                            }
                        }
                    }
                ],
                language: {
                    processing: "<i class='fas fa-spinner fa-spin'></i> Memuat data hasil pre-test...",
                    emptyTable: "Belum ada data hasil pre-test",
                    zeroRecords: "Data tidak ditemukan",
                    search: "Cari siswa:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ siswa",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 siswa",
                    infoFiltered: "(difilter dari _MAX_ total siswa)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                order: [[1, 'asc']],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                drawCallback: function(settings) {
                    // Add animations to new rows
                    $('#muridTable tbody tr').each(function(index) {
                        $(this).css('animation-delay', (index * 0.05) + 's');
                        $(this).addClass('animate__animated animate__fadeInUp');
                    });
                }
            });
            
            // Custom search functionality
            $('#muridTable_filter input').attr('placeholder', 'Cari nama siswa, NIS, atau kelas...');
        });
        
        // Export functionality
        function exportData() {
            Swal.fire({
                title: 'Export Data',
                text: 'Pilih format export yang diinginkan',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Excel (.xlsx)',
                cancelButtonText: 'PDF',
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#2196F3'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Export to Excel
                    window.location.href = '../data/export.php?type=excel&data=hasil-pretest';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Export to PDF
                    window.location.href = '../data/export.php?type=pdf&data=hasil-pretest';
                }
            });
        }
        
        // Refresh data functionality
        function refreshData() {
            muridTable.ajax.reload(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Data Diperbarui',
                    text: 'Data hasil pre-test berhasil diperbarui',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        }
        
        // Auto refresh every 5 minutes
        setInterval(function() {
            muridTable.ajax.reload(null, false);
        }, 300000);
        
        // Modern animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in page content
            document.querySelector('.main-content').style.opacity = '1';
            
            // Add click animation to stat cards
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
            
            // Enhanced search with debouncing
            let searchTimeout;
            const searchInput = document.querySelector('#muridTable_filter input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        // Add search animation
                        document.querySelector('#muridTable_processing').style.display = 'block';
                    }, 300);
                });
            }
            
            // Tooltip initialization for badges
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        // Error handling for AJAX requests
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (settings.url.includes('getHasilPretest')) {
                console.error('AJAX Error in hasil pre-test:', thrownError);
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Bermasalah',
                    text: 'Terjadi kesalahan saat memuat data. Silakan periksa koneksi internet Anda.',
                    confirmButtonColor: '#f44336'
                });
            }
        });
    </script>
</body>

</html>