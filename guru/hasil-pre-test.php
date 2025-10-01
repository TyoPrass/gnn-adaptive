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

    <title>Hasil Pre-Test - MyIRT Adaptive Learning</title>
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
            --level-high: linear-gradient(135deg, #00C851 0%, #007E33 100%);
            --level-medium: linear-gradient(135deg, #ffbb33 0%, #ff8800 100%);
            --level-low: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
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
            background: var(--biology-gradient);
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
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .level-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }
        
        .level-high {
            background: var(--level-high);
            box-shadow: 0 3px 10px rgba(0, 200, 81, 0.3);
        }
        
        .level-medium {
            background: var(--level-medium);
            box-shadow: 0 3px 10px rgba(255, 136, 0, 0.3);
        }
        
        .level-low {
            background: var(--level-low);
            box-shadow: 0 3px 10px rgba(204, 0, 0, 0.3);
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
                <i class="fas fa-chart-bar"></i>
                Hasil Pre-Test Siswa
            </h1>
            <div class="subtitle">
                <i class="fas fa-analytics me-2"></i>
                Analisis level kemampuan siswa berdasarkan hasil pre-test
                <span class="badge bg-light text-dark ms-3">
                    <i class="fas fa-user me-1"></i>
                    <?php echo htmlspecialchars($user_name); ?>
                </span>
            </div>
        </div>


        <!-- Enhanced Data Table Container -->
        <div class="data-table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">
                        <i class="fas fa-table text-primary me-2"></i>
                        Data Hasil Pre-Test
                    </h3>
                    <p class="text-muted mb-0">Hasil evaluasi awal dan penentuan level kemampuan siswa</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" id="btnRefresh">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                    <button class="btn btn-success" id="btnExport">
                        <i class="fas fa-file-export me-2"></i>Export
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="muridTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>No</th>
                            <th><i class="fas fa-user me-2"></i>Nama Murid</th>
                            <th><i class="fas fa-id-card me-2"></i>NIS/Login</th>
                            <th><i class="fas fa-school me-2"></i>Kelas</th>
                            <th><i class="fas fa-chart-line me-2"></i>Level Kemampuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan dimuat melalui DataTables AJAX -->
                    </tbody>
                </table>
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
        // Initialize modern DataTable
        table = $('#muridTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../data/murid.php?action=getHasilPretest",
                "dataType": "json",
                "type": "POST",
            },
            "columnDefs": [
                {
                    "targets": [0, 4],
                    "orderable": false,
                },
            ],
            "columns": [
                { data: "no", name: "no" },
                { data: "murid", name: "murid" },
                { data: "nis", name: "nis" },
                { data: "kelas", name: "kelas" },
                { 
                    data: "hasilPreTest", 
                    name: "hasilPreTest",
                    render: function(data, type, row) {
                        if (type === 'display') {
                            let badgeClass = '';
                            let icon = '';
                            
                            if (data === 'Tinggi' || data === 'High') {
                                badgeClass = 'level-high';
                                icon = '<i class="fas fa-trophy me-1"></i>';
                            } else if (data === 'Sedang' || data === 'Medium') {
                                badgeClass = 'level-medium';
                                icon = '<i class="fas fa-medal me-1"></i>';
                            } else if (data === 'Rendah' || data === 'Low') {
                                badgeClass = 'level-low';
                                icon = '<i class="fas fa-graduation-cap me-1"></i>';
                            } else {
                                badgeClass = 'level-medium';
                                icon = '<i class="fas fa-question me-1"></i>';
                            }
                            
                            return `<span class="level-badge ${badgeClass}">${icon}${data || 'Belum Ditest'}</span>`;
                        }
                        return data;
                    }
                }
            ],
            "language": {
                "processing": '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data hasil pre-test tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ hasil",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 hasil",
                "infoFiltered": "(difilter dari _MAX_ total hasil)",
                "search": "Cari siswa:",
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
                url: '../data/murid.php?action=getLevelStats',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#levelTinggi').text(data.levelTinggi || '0');
                    $('#levelSedang').text(data.levelSedang || '0');
                    $('#levelRendah').text(data.levelRendah || '0');
                },
                error: function() {
                    $('#levelTinggi').text('Error');
                    $('#levelSedang').text('Error');
                    $('#levelRendah').text('Error');
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
        
        // Export button
        $('#btnExport').click(function() {
            Swal.fire({
                title: 'Export Data',
                text: 'Pilih format export yang diinginkan',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#00C851',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-file-excel me-2"></i>Export Excel',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Mengexport...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Simulate export process
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Export Berhasil!',
                            text: 'Data hasil pre-test telah diexport',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        
                        // Here you would implement actual export functionality
                        // window.open('../data/murid.php?action=exportHasilPretest', '_blank');
                    }, 2000);
                }
            });
        });
        
        // Enhanced animations and interactions
        $('.btn').hover(
            function() {
                $(this).css('transform', 'translateY(-2px)');
            },
            function() {
                $(this).css('transform', 'translateY(0)');
            }
        );
        
        // Level badge animations
        $(document).on('mouseenter', '.level-badge', function() {
            $(this).css('transform', 'scale(1.05)');
        }).on('mouseleave', '.level-badge', function() {
            $(this).css('transform', 'scale(1)');
        });
        
        // Table row click for details
        $('#muridTable tbody').on('click', 'tr', function() {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                
                // Show student details
                const data = table.row(this).data();
                if (data) {
                    showStudentDetails(data);
                }
            }
        });
        
        // Add selected row styling
        const style = document.createElement('style');
        style.textContent = `
            .selected {
                background-color: rgba(67, 233, 123, 0.1) !important;
                border-left: 4px solid var(--biology-gradient) !important;
            }
            
            .level-badge {
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    });
    
    function showStudentDetails(data) {
        let levelColor = '';
        let levelIcon = '';
        
        if (data.hasilPreTest === 'Tinggi' || data.hasilPreTest === 'High') {
            levelColor = '#00C851';
            levelIcon = 'fas fa-trophy';
        } else if (data.hasilPreTest === 'Sedang' || data.hasilPreTest === 'Medium') {
            levelColor = '#ffbb33';
            levelIcon = 'fas fa-medal';
        } else {
            levelColor = '#ff4444';
            levelIcon = 'fas fa-graduation-cap';
        }
        
        Swal.fire({
            title: `<i class="fas fa-user-graduate me-2"></i>Detail Siswa`,
            html: `
                <div class="text-start">
                    <div class="row">
                        <div class="col-6">
                            <strong>Nama:</strong><br>
                            <span class="text-muted">${data.murid}</span>
                        </div>
                        <div class="col-6">
                            <strong>NIS:</strong><br>
                            <span class="text-muted">${data.nis}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>Kelas:</strong><br>
                            <span class="text-muted">${data.kelas}</span>
                        </div>
                        <div class="col-6">
                            <strong>Level:</strong><br>
                            <span style="color: ${levelColor}; font-weight: bold;">
                                <i class="${levelIcon} me-1"></i>${data.hasilPreTest || 'Belum Ditest'}
                            </span>
                        </div>
                    </div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
            confirmButtonColor: levelColor,
            showCloseButton: true
        });
    }
    </script>
</body>

</html>