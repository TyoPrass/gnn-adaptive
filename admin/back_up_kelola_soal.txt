<?php
include('../config/db.php');
session_start();

// jika belum login redirect ke login
if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// Level user handling untuk akses kontrol
if (!isset($_SESSION['level_user'])) {
    header('location: ../sign-in.php');
    exit();
}

$level_user = $_SESSION['level_user'];
$user_name = $_SESSION['name'] ?? 'Guest';
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Kelola Soal - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5.2.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.3.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --biology-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            --info-gradient: linear-gradient(135deg, #667eea 0%, #38f9d7 100%);
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
            background: var(--info-gradient);
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
            font-size: 2.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border-left: 5px solid;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .stats-card.questions {
            border-left-color: #4169E1;
        }
        
        .stats-card.modules {
            border-left-color: #43e97b;
        }
        
        .stats-card.choices {
            border-left-color: #fa709a;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-number.questions {
            color: #4169E1;
        }
        
        .stats-number.modules {
            color: #43e97b;
        }
        
        .stats-number.choices {
            color: #fa709a;
        }
        
        .filter-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .btn-add {
            background: var(--success-gradient);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .table thead th {
            background: var(--info-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f3f4;
        }
        
        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
            border-radius: 8px;
            margin: 0 2px;
        }
        
        .btn-info {
            background: var(--info-gradient);
            border: none;
        }
        
        .btn-warning {
            background: var(--warning-gradient);
            border: none;
        }
        
        .btn-danger {
            background: var(--danger-gradient);
            border: none;
        }
        
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: var(--info-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
            padding: 1.5rem 2rem;
        }
        
        .modal-title {
            font-weight: 700;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.8rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .choice-group {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .choice-group .form-control {
            flex: 1;
        }
        
        .btn-remove-choice {
            background: var(--danger-gradient);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 0.5rem;
            width: 40px;
            height: 40px;
        }
        
        .btn-add-choice {
            background: var(--success-gradient);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            margin-top: 1rem;
        }
        
        .navbar {
            background: var(--biology-gradient) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white !important;
        }
        
        .dropdown-menu {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .dropdown-item:hover {
            background: rgba(67, 233, 123, 0.1);
            color: #43e97b;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin: 10px;
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>MyIRT Admin
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="topik.php">
                            <i class="fas fa-book-open me-1"></i>Materi
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user_name); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../sign-out.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Keluar
                            </a></li>
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
                <i class="fas fa-question-circle"></i>
                Kelola Soal Pembelajaran
            </h1>
            <p class="mb-0 mt-2 opacity-90">Pengelolaan soal, pertanyaan, dan pilihan jawaban untuk sistem pembelajaran adaptif</p>
        </div>

        <div class="content-wrapper">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card questions">
                        <h6 class="text-muted">Total Soal</h6>
                        <div class="stats-number questions" id="totalQuestions">0</div>
                        <small class="text-muted">Pertanyaan tersedia</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card modules">
                        <h6 class="text-muted">Modul Aktif</h6>
                        <div class="stats-number modules" id="totalModules">0</div>
                        <small class="text-muted">Modul dengan soal</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card choices">
                        <h6 class="text-muted">Total Pilihan</h6>
                        <div class="stats-number choices" id="totalChoices">0</div>
                        <small class="text-muted">Pilihan jawaban</small>
                    </div>
                </div>
            </div>

            <!-- Filter and Add Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-list me-2"></i>Daftar Soal</h4>
                <button type="button" class="btn btn-add" onclick="addSoal()">
                    <i class="fas fa-plus me-2"></i>Tambah Soal Baru
                </button>
            </div>
            
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Filter Modul:</label>
                        <select class="form-select" id="module-filter">
                            <option value="">Semua Modul</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-primary" onclick="filterSoal()">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="resetFilter()">
                            <i class="fas fa-refresh me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Soal Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="soalTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Modul</th>
                            <th width="60%">Pertanyaan</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add/Edit Soal -->
    <div class="modal fade" id="soalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="soalModalTitle">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Soal Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="soalForm">
                        <input type="hidden" id="question_id" name="question_id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="module_id" class="form-label">Modul <span class="text-danger">*</span></label>
                                <select class="form-select" id="module_id" name="module_id" required>
                                    <option value="">Pilih Modul</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="question_text" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="question_text" name="question_text" rows="4" required 
                                      maxlength="1000" 
                                      placeholder="Masukkan pertanyaan pembelajaran..."></textarea>
                            <div class="form-text">Maksimal 1000 karakter</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Pilihan Jawaban <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-info-circle me-1"></i>Pilih salah satu jawaban sebagai jawaban yang benar dengan menandai radio button
                            </p>
                            <div id="choices-container">
                                <div class="choice-group">
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input type="radio" name="correct_answer" value="0" required title="Tandai sebagai jawaban benar">
                                        </div>
                                        <input type="text" class="form-control" name="choices[]" placeholder="Pilihan jawaban 1" 
                                               maxlength="500" required onchange="updateRadioValue(this)">
                                        <button type="button" class="btn btn-remove-choice" onclick="removeChoice(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="choice-group">
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input type="radio" name="correct_answer" value="1" required title="Tandai sebagai jawaban benar">
                                        </div>
                                        <input type="text" class="form-control" name="choices[]" placeholder="Pilihan jawaban 2" 
                                               maxlength="500" required onchange="updateRadioValue(this)">
                                        <button type="button" class="btn btn-remove-choice" onclick="removeChoice(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-add-choice" onclick="addChoice()">
                                <i class="fas fa-plus me-2"></i>Tambah Pilihan
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveSoal()">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View Soal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Detail Soal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewModalContent">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
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
    var soalTable;

    $(document).ready(function() {
        // Initialize DataTable
        initializeSoalTable();
        
        // Load modules for filter and form
        loadModules();
        
        // Load stats
        loadStats();
    });

    function initializeSoalTable() {
        soalTable = $('#soalTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '../data/soal-management.php',
                type: 'POST',
                data: function(d) {
                    d.action = 'getSoalList';
                    d.modul = $('#module-filter').val();
                }
            },
            columns: [
                { data: 'no', orderable: false },
                { data: 'module_desc' },
                { data: 'question_text' },
                { data: 'action', orderable: false }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            },
            pageLength: 10,
            responsive: true,
            order: [[0, 'desc']]
        });
    }

    function loadModules() {
        $.ajax({
            url: '../data/soal-management.php',
            method: 'POST',
            data: { action: 'getModules' },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var options = '<option value="">Semua Modul</option>';
                    var formOptions = '<option value="">Pilih Modul</option>';
                    
                    response.data.forEach(function(module) {
                        options += `<option value="${module.id}">${module.module_desc}</option>`;
                        formOptions += `<option value="${module.id}">${module.module_desc}</option>`;
                    });
                    
                    $('#module-filter').html(options);
                    $('#module_id').html(formOptions);
                }
            }
        });
    }

    function loadStats() {
        $.ajax({
            url: '../data/soal-management.php',
            method: 'POST',
            data: { action: 'getSoalStats' },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#totalQuestions').text(response.data.total_questions || 0);
                    $('#totalModules').text(response.data.total_modules || 0);
                    $('#totalChoices').text(response.data.total_choices || 0);
                }
            }
        });
    }

    function filterSoal() {
        soalTable.ajax.reload();
    }

    function resetFilter() {
        $('#module-filter').val('');
        soalTable.ajax.reload();
    }

    function addSoal() {
        $('#soalForm')[0].reset();
        $('#question_id').val('');
        $('#soalModalTitle').html('<i class="fas fa-plus-circle me-2"></i>Tambah Soal Baru');
        
        // Reset choices to default 2
        resetChoices();
        
        $('#soalModal').modal('show');
    }

    function editSoal(id) {
        $.ajax({
            url: '../data/soal-management.php',
            method: 'POST',
            data: { 
                action: 'getSoalDetail',
                id: id 
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var question = response.data.question;
                    var choices = response.data.choices;
                    
                    $('#question_id').val(question.question_id);
                    $('#module_id').val(question.module_id);
                    $('#question_text').val(question.question);
                    
                    // Load choices
                    resetChoices();
                    var correctAnswerIndex = question.correct_answer || 0; // Ambil index dari database
                    
                    choices.forEach(function(choice, index) {
                        if (index === 0) {
                            $('input[name="choices[]"]').first().val(choice.answer_desc);
                        } else if (index === 1) {
                            $('input[name="choices[]"]').eq(1).val(choice.answer_desc);
                        } else {
                            addChoice(choice.answer_desc);
                        }
                    });
                    
                    // Set radio button untuk jawaban benar berdasarkan index dari database
                    setTimeout(function() {
                        $(`input[name="correct_answer"][value="${correctAnswerIndex}"]`).prop('checked', true);
                    }, 100);
                    
                    $('#soalModalTitle').html('<i class="fas fa-edit me-2"></i>Edit Soal');
                    $('#soalModal').modal('show');
                }
            }
        });
    }

    function viewSoal(id) {
        $.ajax({
            url: '../data/soal-management.php',
            method: 'POST',
            data: { 
                action: 'getSoalDetail',
                id: id 
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var question = response.data.question;
                    var choices = response.data.choices;
                    
                    // Konversi correct_answer ke integer untuk memastikan index benar
                    var correctAnswerIndex = parseInt(question.correct_answer);
                    
                    // Validasi dan ambil jawaban benar
                    var correctAnswerText = 'Index tidak valid';
                    if (!isNaN(correctAnswerIndex) && choices[correctAnswerIndex]) {
                        correctAnswerText = choices[correctAnswerIndex].answer_desc;
                    } else {
                        // Coba cari berdasarkan answer_desc yang match dengan question.answer
                        for (var i = 0; i < choices.length; i++) {
                            if (choices[i].answer_desc === question.correct_answer) {
                                correctAnswerText = choices[i].answer_desc;
                                correctAnswerIndex = i;
                                break;
                            }
                        }
                    }
                    
                    var content = `
                        <div class="row">
                            <div class="col-md-12">
                                <h6><strong>Modul:</strong></h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-book me-2"></i>${question.module_desc}
                                </div>
                                
                                <h6><strong>Pertanyaan:</strong></h6>
                                <div class="alert alert-light border" style="background-color: #f8f9fa;">
                                    ${question.question}
                                </div>
                                
                                <h6><strong>Jawaban Benar:</strong></h6>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i><strong>${correctAnswerText}</strong>
                                </div>
                                
                                <h6><strong>Pilihan Jawaban:</strong></h6>
                                <div class="list-group">
                    `;
                    
                    choices.forEach(function(choice, index) {
                        var isCorrect = index == correctAnswerIndex ? ' list-group-item-success' : '';
                        var icon = index == correctAnswerIndex ? '<i class="fas fa-check-circle text-success me-2"></i>' : '<i class="fas fa-circle text-muted me-2"></i>';
                        content += `<div class="list-group-item${isCorrect}">${icon}${String.fromCharCode(65 + index)}. ${choice.answer_desc}</div>`;
                    });
                    
                    content += `
                                </div>
                            </div>
                        </div>
                    `;
                    
                    $('#viewModalContent').html(content);
                    $('#viewModal').modal('show');
                }
            }
        });
    }

    function deleteSoal(id) {
        Swal.fire({
            title: 'Hapus Soal?',
            text: 'Data soal yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../data/soal-management.php',
                    method: 'POST',
                    data: { 
                        action: 'deleteSoal',
                        id: id 
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Berhasil!', response.message, 'success');
                            soalTable.ajax.reload();
                            loadStats(); // Reload stats
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    }
                });
            }
        });
    }

    function saveSoal() {
        // Validasi pertanyaan
        var questionText = $('#question_text').val().trim();
        if (!questionText || typeof questionText !== 'string') {
            Swal.fire('Error!', 'Pertanyaan harus diisi dengan teks yang valid', 'error');
            return;
        }
        
        if (questionText.length > 1000) {
            Swal.fire('Error!', 'Pertanyaan terlalu panjang (maksimal 1000 karakter)', 'error');
            return;
        }
        
        // Validasi modul
        var moduleId = $('#module_id').val();
        if (!moduleId) {
            Swal.fire('Error!', 'Pilih modul terlebih dahulu', 'error');
            return;
        }
        
        // Validate choices sebagai string
        var choices = $('input[name="choices[]"]').map(function() {
            var value = $(this).val().trim();
            return value;
        }).get().filter(function(choice) {
            return choice && typeof choice === 'string' && choice.length > 0;
        });
        
        if (choices.length < 2) {
            Swal.fire('Error!', 'Minimal harus ada 2 pilihan jawaban yang valid', 'error');
            return;
        }
        
        // Validasi panjang setiap pilihan
        for (var i = 0; i < choices.length; i++) {
            if (choices[i].length > 500) {
                Swal.fire('Error!', 'Pilihan jawaban ke-' + (i + 1) + ' terlalu panjang (maksimal 500 karakter)', 'error');
                return;
            }
        }
        
        // Validate correct answer selection
        var correctAnswerIndex = $('input[name="correct_answer"]:checked').val();
        if (correctAnswerIndex === undefined) {
            Swal.fire('Error!', 'Pilih salah satu jawaban sebagai jawaban yang benar', 'error');
            return;
        }
        
        // Prepare data sebagai object - hanya kirim index, tidak perlu text
        var postData = {
            action: 'saveSoal',
            question_id: $('#question_id').val(),
            module_id: moduleId,
            question_text: questionText,
            correct_answer_index: correctAnswerIndex,
            choices: choices
        };
        
        $.ajax({
            url: '../data/soal-management.php',
            method: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Berhasil!', response.message, 'success');
                    $('#soalModal').modal('hide');
                    soalTable.ajax.reload();
                    loadStats(); // Reload stats
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data', 'error');
            }
        });
    }

    function addChoice(value = '') {
        var choiceIndex = $('.choice-group').length;
        var choiceHtml = `
            <div class="choice-group">
                <div class="input-group">
                    <div class="input-group-text">
                        <input type="radio" name="correct_answer" value="${choiceIndex}" required title="Tandai sebagai jawaban benar">
                    </div>
                    <input type="text" class="form-control" name="choices[]" value="${value}" 
                           placeholder="Pilihan jawaban ${choiceIndex + 1}" 
                           maxlength="500" required onchange="updateRadioValue(this)">
                    <button type="button" class="btn btn-remove-choice" onclick="removeChoice(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        $('#choices-container').append(choiceHtml);
    }

    function removeChoice(button) {
        if ($('.choice-group').length > 2) {
            var wasChecked = $(button).closest('.choice-group').find('input[type="radio"]').is(':checked');
            $(button).closest('.choice-group').remove();
            updateChoicePlaceholders();
            updateRadioValues();
            
            // Jika radio button yang dipilih dihapus, pilih yang pertama
            if (wasChecked && $('.choice-group').length > 0) {
                $('.choice-group').first().find('input[type="radio"]').prop('checked', true);
            }
        } else {
            Swal.fire('Info!', 'Minimal harus ada 2 pilihan jawaban', 'info');
        }
    }

    function resetChoices() {
        $('#choices-container').html(`
            <div class="choice-group">
                <div class="input-group">
                    <div class="input-group-text">
                        <input type="radio" name="correct_answer" value="0" checked required title="Tandai sebagai jawaban benar">
                    </div>
                    <input type="text" class="form-control" name="choices[]" placeholder="Pilihan jawaban 1" required onchange="updateRadioValue(this)">
                    <button type="button" class="btn btn-remove-choice" onclick="removeChoice(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="choice-group">
                <div class="input-group">
                    <div class="input-group-text">
                        <input type="radio" name="correct_answer" value="1" required title="Tandai sebagai jawaban benar">
                    </div>
                    <input type="text" class="form-control" name="choices[]" placeholder="Pilihan jawaban 2" required onchange="updateRadioValue(this)">
                    <button type="button" class="btn btn-remove-choice" onclick="removeChoice(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `);
    }

    function updateChoicePlaceholders() {
        $('.choice-group').each(function(index) {
            $(this).find('input[type="text"]').attr('placeholder', `Pilihan jawaban ${index + 1}`);
        });
    }

    function updateRadioValues() {
        $('.choice-group').each(function(index) {
            $(this).find('input[type="radio"]').val(index);
        });
    }

    function updateRadioValue(inputElement) {
        // Function ini dipanggil ketika nilai pilihan jawaban berubah
        // Bisa digunakan untuk validasi atau update lainnya jika diperlukan
    }
    </script>
</body>
</html>