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

    <title>Daftar Topik - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --info-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .topic-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .topic-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .topic-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .topic-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .topic-item {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .topic-item:hover {
            border-color: rgba(102, 126, 234, 0.3);
            transform: translateX(10px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .topic-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-gradient);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .topic-item:hover::before {
            transform: scaleY(1);
        }
        
        .progress-ring {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, #667eea 0deg, #764ba2 calc(var(--progress) * 3.6deg), #e9ecef calc(var(--progress) * 3.6deg));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .progress-ring::before {
            content: '';
            width: 80%;
            height: 80%;
            border-radius: 50%;
            background: white;
            position: absolute;
        }
        
        .progress-text {
            position: relative;
            z-index: 1;
            font-weight: bold;
            font-size: 0.8rem;
            color: #667eea;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .status-completed {
            background: var(--success-gradient);
            color: white;
        }
        
        .status-in-progress {
            background: var(--warning-gradient);
            color: white;
        }
        
        .status-not-started {
            background: var(--secondary-gradient);
            color: white;
        }
        
        .topic-stats {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .search-box {
            background: white;
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .search-box:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .topic-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-right: 1rem;
        }
        
        .action-btn {
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-learn {
            background: var(--primary-gradient);
            color: white;
        }
        
        .btn-learn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        .btn-subtopic {
            background: var(--info-gradient);
            color: white;
        }
        
        .btn-subtopic:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        /* DataTable Custom Styling */
        .dataTables_wrapper {
            padding: 0;
        }
        
        .dataTables_filter input {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 0.5rem 1rem;
        }
        
        .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .dataTables_length select {
            border-radius: 20px;
            border: 2px solid #e9ecef;
            padding: 0.25rem 0.5rem;
        }
        
        .page-link {
            border-radius: 20px;
            margin: 0 2px;
            border: none;
            color: #667eea;
        }
        
        .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: transparent;
        }
        
        .table thead th {
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody td {
            border: none;
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        
        .table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .topic-icon-small {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            margin-right: 0.5rem;
        }
        
        .progress-ring-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            font-weight: bold;
            font-size: 0.7rem;
        }
        
        @media (max-width: 768px) {
            .topic-item {
                text-align: center;
            }
            .topic-item:hover {
                transform: translateY(-5px);
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .action-btn {
                padding: 0.25rem 0.75rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--primary-gradient); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>MyIRT Learning
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-user me-1"></i><?php echo $_SESSION['name']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../sign-out.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="topic-header topic-card">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-5 fw-bold mb-3">
                                    <i class="fas fa-book-open me-3"></i>Daftar Topik Pembelajaran
                                </h1>
                                <p class="lead mb-0">Jelajahi berbagai topik pembelajaran yang tersedia</p>
                            </div>
                            <div class="col-lg-4 text-center">
                                <i class="fas fa-books fa-5x" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="topic-stats topic-card">
                        <div class="row">
                            <div class="col-md-3 stat-item">
                                <div class="stat-number" id="totalTopics">0</div>
                                <small class="text-muted">Total Topik</small>
                            </div>
                            <div class="col-md-3 stat-item">
                                <div class="stat-number" id="completedTopics">0</div>
                                <small class="text-muted">Selesai</small>
                            </div>
                            <div class="col-md-3 stat-item">
                                <div class="stat-number" id="inProgressTopics">0</div>
                                <small class="text-muted">Sedang Berjalan</small>
                            </div>
                            <div class="col-md-3 stat-item">
                                <div class="stat-number" id="notStartedTopics">0</div>
                                <small class="text-muted">Belum Mulai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text" style="background: white; border-color: #e9ecef;">
                            <i class="fas fa-search" style="color: #667eea;"></i>
                        </span>
                        <input type="text" class="search-box form-control" id="searchTopics" placeholder="Cari topik...">
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select search-box" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="completed">Selesai</option>
                        <option value="in-progress">Sedang Berjalan</option>
                        <option value="not-started">Belum Mulai</option>
                    </select>
                </div>
            </div>

            <!-- Topics List -->
            <div class="row">
                <div class="col-12">
                    <div class="topic-card p-4">
                        <div class="table-responsive">
                            <table id="topikTable" class="table table-hover align-middle" style="width:100%">
                                <thead>
                                    <tr style="background: var(--primary-gradient); color: white;">
                                        <th class="text-center" width="5%">No</th>
                                        <th width="25%">Topik</th>
                                        <th width="20%">Progress</th>
                                        <th width="15%">Status</th>
                                        <th class="text-center" width="35%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
    var table;
    
    $(document).ready(function() {
        // Initialize DataTable
        table = $('#topikTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../data/materi.php?action=getTopik",
                "dataType": "json",
                "type": "POST",
            },
            "columnDefs": [
                {
                    "targets": [0, 4], // first column and action column
                    "orderable": false,
                },
            ],
            "columns": [
                {
                    "data": "no",
                    "name": "no",
                    "className": "text-center"
                },
                {
                    "data": "topic_desc",
                    "name": "topic_desc",
                    "render": function(data, type, row) {
                        const topicIcons = ['fas fa-dna', 'fas fa-microscope', 'fas fa-leaf', 'fas fa-seedling', 'fas fa-bacteria'];
                        const topicColors = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a'];
                        const iconClass = topicIcons[Math.floor(Math.random() * topicIcons.length)];
                        const iconColor = topicColors[Math.floor(Math.random() * topicColors.length)];
                        
                        return `
                            <div class="d-flex align-items-center">
                                <div class="topic-icon-small" style="background: ${iconColor};">
                                    <i class="${iconClass}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">${data}</div>
                                    <small class="text-muted">Topik Pembelajaran</small>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    "data": "topic_desc",
                    "name": "progress",
                    "orderable": false,
                    "render": function(data, type, row) {
                        // Random progress for demo
                        const progress = Math.floor(Math.random() * 101);
                        const color = progress === 100 ? '#43e97b' : progress > 50 ? '#667eea' : '#f093fb';
                        
                        return `
                            <div class="d-flex align-items-center">
                                <div class="progress-ring-small" style="background: conic-gradient(from 0deg, ${color} 0deg, ${color} ${progress * 3.6}deg, #e9ecef ${progress * 3.6}deg); color: ${color};">
                                    ${progress}%
                                </div>
                                <div class="ms-2">
                                    <div class="progress" style="width: 80px; height: 6px;">
                                        <div class="progress-bar" style="width: ${progress}%; background: ${color};"></div>
                                    </div>
                                    <small class="text-muted">${progress}% selesai</small>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    "data": "topic_desc",
                    "name": "status",
                    "orderable": false,
                    "render": function(data, type, row) {
                        // Random status for demo
                        const statuses = [
                            {class: 'status-completed', text: 'Selesai', icon: 'fas fa-check-circle'},
                            {class: 'status-in-progress', text: 'Sedang Berjalan', icon: 'fas fa-play-circle'},
                            {class: 'status-not-started', text: 'Belum Mulai', icon: 'fas fa-circle'}
                        ];
                        const status = statuses[Math.floor(Math.random() * statuses.length)];
                        
                        return `
                            <span class="status-badge ${status.class}">
                                <i class="${status.icon} me-1"></i>${status.text}
                            </span>
                        `;
                    }
                },
                {
                    "data": "action",
                    "name": "action",
                    "className": "text-center",
                    "render": function(data, type, row) {
                        return `
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <a href="subtopik.php?topic_id=${row.id}" class="action-btn btn-subtopic btn-sm">
                                    <i class="fas fa-list me-1"></i>Subtopik
                                </a>
                                <a href="modul.php?topic_id=${row.id}" class="action-btn btn-learn btn-sm">
                                    <i class="fas fa-play me-1"></i>Mulai Belajar
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            "language": {
                "processing": "<div class='spinner-border text-primary' role='status'><span class='visually-hidden'>Loading...</span></div>",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "<div class='text-center py-4'><i class='fas fa-book-open fa-3x text-muted mb-3'></i><br><h5 class='text-muted'>Tidak ada data topik</h5><p class='text-muted'>Belum ada topik pembelajaran yang tersedia</p></div>",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari topik:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "pageLength": 10,
            "responsive": true,
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "drawCallback": function(settings) {
                // Update stats after data is loaded
                updateStatsFromTable();
                
                // Add hover effects
                $('#topikTable tbody tr').hover(
                    function() { $(this).addClass('table-hover-effect'); },
                    function() { $(this).removeClass('table-hover-effect'); }
                );
            }
        });
        
        // Custom search functionality
        $('#searchTopics').on('keyup', function() {
            table.search(this.value).draw();
        });
        
        // Hide default search and length menu to use custom ones
        $('.dataTables_filter').hide();
    });
    
    function updateStatsFromTable() {
        // Get current page data
        const data = table.rows({page: 'current'}).data();
        const total = table.page.info().recordsTotal;
        
        // For demo purposes, create random stats
        const completed = Math.floor(total * 0.3);
        const inProgress = Math.floor(total * 0.5);
        const notStarted = total - completed - inProgress;
        
        animateNumber('#totalTopics', total);
        animateNumber('#completedTopics', completed);
        animateNumber('#inProgressTopics', inProgress);
        animateNumber('#notStartedTopics', notStarted);
    }
    
    function animateNumber(selector, target) {
        const element = $(selector);
        let current = 0;
        const increment = target / 20;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.text(Math.floor(current));
        }, 50);
    }
    
    // Filter functionality
    $('#filterStatus').on('change', function() {
        const status = $(this).val();
        if (status) {
            table.columns(3).search(status).draw();
        } else {
            table.columns(3).search('').draw();
        }
    });
    
    function startTopic(topicId) {
        if (confirm('Apakah Anda yakin ingin memulai topik ini?')) {
            alert('Topik dimulai! Anda akan diarahkan ke halaman pembelajaran.');
            window.location.href = `modul.php?topic_id=${topicId}`;
        }
    }
    </script>
</body>
</html>