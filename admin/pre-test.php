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

    <title>Kelola Pre-Test | MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    
    <!-- Modern CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --biology-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --pretest-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: var(--pretest-gradient);
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
            background: var(--pretest-gradient);
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--pretest-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #666;
            font-weight: 500;
            margin-top: 0.5rem;
        }
        
        /* Progress Bars */
        .progress-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
        }
        
        .custom-progress {
            height: 10px;
            background: #f1f3f4;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .custom-progress-bar {
            height: 100%;
            background: var(--pretest-gradient);
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        /* Action Buttons */
        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.875rem;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-calculate {
            background: var(--success-gradient);
            color: white;
        }
        
        .btn-calculate:hover {
            color: white;
            filter: brightness(1.1);
        }
        
        .btn-view {
            background: var(--pretest-gradient);
            color: white;
        }
        
        .btn-view:hover {
            color: white;
            filter: brightness(1.1);
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
            background: var(--pretest-gradient);
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
        
        /* Status Badges */
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .status-complete {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }
        
        .status-partial {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }
        
        .status-pending {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
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
            background: var(--pretest-gradient) !important;
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
            
            .action-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
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
    
    // Get statistics for pre-test overview
    $total_classes = 0;
    $total_students = 0;
    $completed_pretests = 0;
    $completed_surveys = 0;
    
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
    
    $result = mysqli_query($conn, "SELECT COUNT(DISTINCT student_id) as count FROM pre_test_result");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $completed_pretests = $row['count'];
    }
    
    $result = mysqli_query($conn, "SELECT COUNT(DISTINCT student_id) as count FROM survey_result");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $completed_surveys = $row['count'];
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
                            <a class="nav-link dropdown-toggle text-white active" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cogs me-1"></i>Pengaturan
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="pre-test.php"><i class="fas fa-tasks me-2"></i>Pre-Test</a></li>
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
            <div class="container">
                <h1>
                    <i class="fas fa-calculator"></i>
                    Kelola Pre-Test & Hitung Level
                </h1>
                <div class="subtitle">
                    <i class="fas fa-cogs me-2"></i>
                    Pantau progress pre-test per kelas dan kelola perhitungan level siswa
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
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_classes; ?></div>
                        <div class="stat-label">
                            <i class="fas fa-school me-1"></i>Total Kelas
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_students; ?></div>
                        <div class="stat-label">
                            <i class="fas fa-users me-1"></i>Total Siswa
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $completed_pretests; ?></div>
                        <div class="stat-label">
                            <i class="fas fa-clipboard-check me-1"></i>Selesai Pre-Test
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $completed_surveys; ?></div>
                        <div class="stat-label">
                            <i class="fas fa-poll me-1"></i>Selesai Survei
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="progress-card">
                        <h6 class="mb-3">
                            <i class="fas fa-chart-pie text-primary me-2"></i>
                            Progress Pre-Test
                        </h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Completion Rate</span>
                            <span class="fw-bold"><?php echo $total_students > 0 ? round(($completed_pretests / $total_students) * 100, 1) : 0; ?>%</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar" style="width: <?php echo $total_students > 0 ? ($completed_pretests / $total_students) * 100 : 0; ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="progress-card">
                        <h6 class="mb-3">
                            <i class="fas fa-chart-line text-success me-2"></i>
                            Progress Survei
                        </h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Completion Rate</span>
                            <span class="fw-bold"><?php echo $total_students > 0 ? round(($completed_surveys / $total_students) * 100, 1) : 0; ?>%</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar" style="width: <?php echo $total_students > 0 ? ($completed_surveys / $total_students) * 100 : 0; ?>%"></div>
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
                            Manajemen Pre-Test per Kelas
                        </h3>
                        <p class="text-muted mb-0">Monitor progress dan kelola perhitungan level untuk setiap kelas</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshAll()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh All
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="calculateAllLevels()">
                            <i class="fas fa-calculator me-1"></i>Hitung Semua Level
                        </button>
                    </div>
                </div>
                
                <div class="table-container">
                    <table id="kelasTable" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>No</th>
                                <th><i class="fas fa-school me-1"></i>Kelas</th>
                                <th><i class="fas fa-users me-1"></i>Jumlah Murid</th>
                                <th><i class="fas fa-clipboard-check me-1"></i>Pre-Test Selesai</th>
                                <th><i class="fas fa-poll me-1"></i>Survei Selesai</th>
                                <th><i class="fas fa-calculator me-1"></i>Level Dihitung</th>
                                <th><i class="fas fa-cogs me-1"></i>Aksi</th>
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
        let kelasTable;
        
        $(document).ready(function() {
            // Initialize modern DataTable
            kelasTable = $('#kelasTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "../data/kelas.php?action=getKelasPost",
                    type: "POST",
                    dataType: "json",
                    error: function(xhr, error, thrown) {
                        console.error('DataTable Ajax Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Loading Data',
                            text: 'Tidak dapat memuat data kelas. Silakan refresh halaman.',
                            confirmButtonColor: '#f44336'
                        });
                    }
                },
                columnDefs: [{
                    targets: [0, 6],
                    orderable: false
                }],
                columns: [
                    { 
                        data: "no", 
                        name: "no",
                        width: "5%"
                    },
                    { 
                        data: "kelas", 
                        name: "kelas",
                        width: "10%",
                        render: function(data, type, row) {
                            return `<div class="fw-bold">
                                        <i class="fas fa-school text-primary me-2"></i>
                                        Kelas ${data}
                                    </div>`;
                        }
                    },
                    { 
                        data: "jumlah_murid", 
                        name: "jumlah_murid",
                        width: "15%",
                        render: function(data, type, row) {
                            return `<div class="text-center">
                                        <span class="badge bg-info">${data} siswa</span>
                                    </div>`;
                        }
                    },
                    { 
                        data: "ambil_post", 
                        name: "ambil_post",
                        width: "15%",
                        render: function(data, type, row) {
                            const total = parseInt(row.jumlah_murid) || 0;
                            const completed = parseInt(data) || 0;
                            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
                            
                            let statusClass = 'status-pending';
                            let icon = 'fa-clock';
                            
                            if (percentage >= 80) {
                                statusClass = 'status-complete';
                                icon = 'fa-check-circle';
                            } else if (percentage >= 40) {
                                statusClass = 'status-partial';
                                icon = 'fa-hourglass-half';
                            }
                            
                            return `<div class="text-center">
                                        <div class="status-badge ${statusClass}">
                                            <i class="fas ${icon}"></i>
                                            ${completed}/${total}
                                        </div>
                                        <small class="text-muted d-block mt-1">${percentage}%</small>
                                    </div>`;
                        }
                    },
                    { 
                        data: "ambil_survey", 
                        name: "ambil_survey",
                        width: "15%",
                        render: function(data, type, row) {
                            const total = parseInt(row.jumlah_murid) || 0;
                            const completed = parseInt(data) || 0;
                            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
                            
                            let statusClass = 'status-pending';
                            let icon = 'fa-clock';
                            
                            if (percentage >= 80) {
                                statusClass = 'status-complete';
                                icon = 'fa-check-circle';
                            } else if (percentage >= 40) {
                                statusClass = 'status-partial';
                                icon = 'fa-hourglass-half';
                            }
                            
                            return `<div class="text-center">
                                        <div class="status-badge ${statusClass}">
                                            <i class="fas ${icon}"></i>
                                            ${completed}/${total}
                                        </div>
                                        <small class="text-muted d-block mt-1">${percentage}%</small>
                                    </div>`;
                        }
                    },
                    { 
                        data: "hasil_pre_test", 
                        name: "hasil_pre_test",
                        width: "15%",
                        render: function(data, type, row) {
                            const total = parseInt(row.jumlah_murid) || 0;
                            const calculated = parseInt(data) || 0;
                            const percentage = total > 0 ? Math.round((calculated / total) * 100) : 0;
                            
                            let statusClass = 'status-pending';
                            let icon = 'fa-calculator';
                            
                            if (percentage >= 80) {
                                statusClass = 'status-complete';
                                icon = 'fa-check-circle';
                            } else if (percentage >= 40) {
                                statusClass = 'status-partial';
                                icon = 'fa-hourglass-half';
                            }
                            
                            return `<div class="text-center">
                                        <div class="status-badge ${statusClass}">
                                            <i class="fas ${icon}"></i>
                                            ${calculated}/${total}
                                        </div>
                                        <small class="text-muted d-block mt-1">${percentage}%</small>
                                    </div>`;
                        }
                    },
                    { 
                        data: "action", 
                        name: "action",
                        width: "25%",
                        render: function(data, type, row) {
                            const kelasId = row.id || '';
                            const canCalculate = parseInt(row.ambil_post) > 0 && parseInt(row.ambil_survey) > 0;
                            
                            let actions = `<div class="d-flex gap-1 justify-content-center">`;
                            
                            // View details button
                            actions += `<button class="action-btn btn-view" onclick="viewClassDetails('${kelasId}', '${row.kelas}')" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>`;
                            
                            // Calculate level button
                            if (canCalculate) {
                                actions += `<button class="action-btn btn-calculate" onclick="calculateClassLevel('${kelasId}', '${row.kelas}')" title="Hitung Level">
                                                <i class="fas fa-calculator"></i>
                                            </button>`;
                            } else {
                                actions += `<button class="action-btn btn-calculate" disabled title="Pre-test dan Survei harus selesai dulu" style="opacity: 0.5; cursor: not-allowed;">
                                                <i class="fas fa-calculator"></i>
                                            </button>`;
                            }
                            
                            actions += `</div>`;
                            return actions;
                        }
                    }
                ],
                language: {
                    processing: "<i class='fas fa-spinner fa-spin'></i> Memuat data kelas...",
                    emptyTable: "Belum ada data kelas",
                    zeroRecords: "Data tidak ditemukan",
                    search: "Cari kelas:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ kelas",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 kelas",
                    infoFiltered: "(difilter dari _MAX_ total kelas)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                drawCallback: function(settings) {
                    // Add animations to new rows
                    $('#kelasTable tbody tr').each(function(index) {
                        $(this).css('animation-delay', (index * 0.05) + 's');
                        $(this).addClass('animate__animated animate__fadeInUp');
                    });
                }
            });
            
            // Custom search functionality
            $('#kelasTable_filter input').attr('placeholder', 'Cari nama kelas...');
        });
        
        // View class details
        function viewClassDetails(kelasId, kelasName) {
            Swal.fire({
                title: `Detail Kelas ${kelasName}`,
                html: `<div class="text-start">
                           <p><strong>ID Kelas:</strong> ${kelasId}</p>
                           <p><strong>Nama Kelas:</strong> ${kelasName}</p>
                           <p class="text-muted">Fitur detail kelas akan segera tersedia...</p>
                       </div>`,
                icon: 'info',
                confirmButtonColor: '#667eea'
            });
        }
        
        // Calculate level for specific class
        function calculateClassLevel(kelasId, kelasName) {
            Swal.fire({
                title: `Hitung Level Kelas ${kelasName}`,
                text: 'Apakah Anda yakin ingin menghitung level untuk semua siswa di kelas ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hitung Level!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '../data/hitung_pretest.php',
                        method: 'POST',
                        data: { 
                            id: kelasId  // Changed from class_id to id as expected by hitung_pretest.php
                        },
                        timeout: 30000  // 30 second timeout for calculation
                    }).then(response => {
                        // Handle both success and error responses
                        if (typeof response === 'string') {
                            // Check for error messages in response
                            if (response.includes('Error:')) {
                                const errorMatch = response.match(/Error: ([^<\n\r]+)/);
                                const errorMsg = errorMatch ? errorMatch[1].trim() : 'Terjadi kesalahan dalam perhitungan';
                                throw new Error(errorMsg);
                            } else if (response.includes('die(') || response.includes('exit(')) {
                                throw new Error('Terjadi kesalahan dalam proses perhitungan');
                            } else {
                                // Success - check for redirect or success indicator
                                return { success: true, message: 'Level berhasil dihitung' };
                            }
                        } else if (response && response.success === false) {
                            throw new Error(response.message || 'Gagal menghitung level');
                        } else {
                            return { success: true, message: 'Level berhasil dihitung' };
                        }
                    }).catch(error => {
                        console.error('AJAX Error:', error);
                        let errorMessage = 'Terjadi kesalahan dalam perhitungan';
                        
                        if (error.responseText) {
                            // Extract meaningful error from HTML response
                            if (error.responseText.includes('Invalid class ID')) {
                                errorMessage = 'ID kelas tidak valid';
                            } else if (error.responseText.includes('No students found')) {
                                errorMessage = 'Tidak ada siswa ditemukan di kelas ini';
                            } else if (error.responseText.includes('Need at least 2 students')) {
                                errorMessage = 'Minimal diperlukan 2 siswa dengan jawaban pre-test lengkap untuk perhitungan';
                            } else if (error.responseText.includes('Error:')) {
                                const errorMatch = error.responseText.match(/Error: ([^<\n\r]+)/);
                                if (errorMatch) {
                                    errorMessage = errorMatch[1].trim();
                                }
                            }
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        
                        Swal.showValidationMessage(errorMessage);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Level Berhasil Dihitung!',
                        text: `Level untuk kelas ${kelasName} telah berhasil dihitung.`,
                        confirmButtonColor: '#4CAF50'
                    }).then(() => {
                        kelasTable.ajax.reload();
                        updateStatistics();
                    });
                }
            });
        }
        
        // Calculate all levels
        function calculateAllLevels() {
            Swal.fire({
                title: 'Hitung Semua Level',
                text: 'Fitur ini akan menghitung level untuk semua kelas yang memiliki siswa dengan pre-test lengkap.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Fitur Dalam Pengembangan',
                        text: 'Untuk saat ini, silakan gunakan tombol "Hitung Level" pada masing-masing kelas.',
                        confirmButtonColor: '#4CAF50'
                    });
                }
            });
        }
        
        // Refresh all data
        function refreshAll() {
            kelasTable.ajax.reload(function() {
                updateStatistics();
                Swal.fire({
                    icon: 'success',
                    title: 'Data Diperbarui',
                    text: 'Semua data telah berhasil diperbarui',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        }
        
        // Update statistics
        function updateStatistics() {
            // Reload page statistics if needed
            setTimeout(() => {
                location.reload();
            }, 2000);
        }
        
        // Auto refresh every 10 minutes
        setInterval(function() {
            kelasTable.ajax.reload(null, false);
        }, 600000);
        
        // Modern animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in page content
            document.querySelector('.main-content').style.opacity = '1';
            
            // Check for success message in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                const calculatedCount = urlParams.get('calculated') || 'beberapa';
                const message = urlParams.get('message') || `Level berhasil dihitung untuk ${calculatedCount} siswa`;
                
                Swal.fire({
                    icon: 'success',
                    title: 'Perhitungan Berhasil!',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                
                // Clean URL
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({path: newUrl}, '', newUrl);
            }
            
            // Add click animation to stat cards
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
            
            // Progress bar animations
            document.querySelectorAll('.custom-progress-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
        
        // Error handling for AJAX requests
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (settings.url && settings.url.includes('getKelasPost')) {
                console.error('AJAX Error in pre-test management:', thrownError);
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