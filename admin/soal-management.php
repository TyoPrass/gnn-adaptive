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

// ✅ AMBIL DATA LANGSUNG DARI DATABASE (TANPA AJAX)
$modul_filter = $_GET['modul'] ?? '';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Build query
$where = "WHERE 1=1";
if (!empty($modul_filter)) {
    $where .= " AND a.id = '" . mysqli_real_escape_string($conn, $modul_filter) . "'";
}
if (!empty($search)) {
    $where .= " AND (a.module_desc LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                OR m.question LIKE '%" . mysqli_real_escape_string($conn, $search) . "%')";
}

// Count total
$count_query = "SELECT COUNT(m.id) as total 
                FROM module AS a
                JOIN module_question AS m ON m.module_id = a.id
                $where";
$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $per_page);

// Get data
$data_query = "SELECT 
               m.id as question_id,
               a.module_desc, 
               m.question, 
               m.answer as correct_answer,
               a.id as module_id
               FROM module AS a
               JOIN module_question AS m ON m.module_id = a.id
               $where
               ORDER BY m.id DESC 
               LIMIT $per_page OFFSET $offset";
$data_result = mysqli_query($conn, $data_query);

// Get stats
$stats_query = "SELECT 
                (SELECT COUNT(*) FROM module_question) as total_questions,
                (SELECT COUNT(DISTINCT module_id) FROM module_question) as total_modules,
                (SELECT COUNT(*) FROM module_question_choice) as total_choices";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get modules for filter
$modules_query = "SELECT id, module_desc FROM module ORDER BY module_desc";
$modules_result = mysqli_query($conn, $modules_query);
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Kelola Soal - MyIRT Adaptive Learning (Simple)</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5.2.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.3.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
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
        
        .stats-card.questions { border-left-color: #4169E1; }
        .stats-card.modules { border-left-color: #43e97b; }
        .stats-card.choices { border-left-color: #fa709a; }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-number.questions { color: #4169E1; }
        .stats-number.modules { color: #43e97b; }
        .stats-number.choices { color: #fa709a; }
        
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
        
        .pagination {
            gap: 5px;
        }
        
        .page-link {
            border-radius: 8px;
            border: none;
            margin: 0 2px;
            color: #667eea;
        }
        
        .page-item.active .page-link {
            background: var(--info-gradient);
            border: none;
        }
        
        .navbar {
            background: var(--biology-gradient) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
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
            <p class="mb-0 mt-2 opacity-90">Manajemen Kelola Soal</p>
        </div>

        <div class="content-wrapper">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card questions">
                        <h6 class="text-muted">Total Soal</h6>
                        <div class="stats-number questions"><?php echo $stats['total_questions']; ?></div>
                        <small class="text-muted">Pertanyaan tersedia</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card modules">
                        <h6 class="text-muted">Modul Aktif</h6>
                        <div class="stats-number modules"><?php echo $stats['total_modules']; ?></div>
                        <small class="text-muted">Modul dengan soal</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card choices">
                        <h6 class="text-muted">Total Pilihan</h6>
                        <div class="stats-number choices"><?php echo $stats['total_choices']; ?></div>
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
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Filter Modul:</label>
                            <select class="form-select" name="modul" id="module-filter">
                                <option value="">Semua Modul</option>
                                <?php while ($module = mysqli_fetch_assoc($modules_result)): ?>
                                    <option value="<?php echo $module['id']; ?>" 
                                            <?php echo ($modul_filter == $module['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($module['module_desc']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cari Soal:</label>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari pertanyaan..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="soal-management-simple.php" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-refresh me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Info -->
            <?php if (!empty($search) || !empty($modul_filter)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Menampilkan <strong><?php echo $total_records; ?></strong> hasil
                    <?php if (!empty($search)): ?>
                        untuk pencarian "<strong><?php echo htmlspecialchars($search); ?></strong>"
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Soal Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Modul</th>
                            <th width="60%">Pertanyaan</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($data_result) > 0):
                            $no = $offset + 1;
                            while ($row = mysqli_fetch_assoc($data_result)): 
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?php echo htmlspecialchars($row['module_desc']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    $question = htmlspecialchars($row['question']);
                                    echo (strlen($question) > 100) ? substr($question, 0, 100) . '...' : $question;
                                    ?>
                                </td>
                                <td>
                                    <div class='btn-group' role='group'>
                                        <button type='button' class='btn btn-sm btn-info' 
                                                onclick='viewSoal(<?php echo $row['question_id']; ?>)' 
                                                title='Lihat Detail'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                        <button type='button' class='btn btn-sm btn-warning' 
                                                onclick='editSoal(<?php echo $row['question_id']; ?>)' 
                                                title='Edit'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button type='button' class='btn btn-sm btn-danger' 
                                                onclick='deleteSoal(<?php echo $row['question_id']; ?>)' 
                                                title='Hapus'>
                                            <i class='fas fa-trash'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else: 
                        ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Tidak ada data soal</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <!-- Previous -->
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?>&modul=<?php echo urlencode($modul_filter); ?>&search=<?php echo urlencode($search); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        
                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&modul=<?php echo urlencode($modul_filter); ?>&search=<?php echo urlencode($search); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <!-- Next -->
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?>&modul=<?php echo urlencode($modul_filter); ?>&search=<?php echo urlencode($search); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                    <p class="text-center text-muted">
                        Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?> 
                        (Total: <?php echo $total_records; ?> soal)
                    </p>
                </nav>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Add/Edit Soal - Sama seperti sebelumnya -->
    <div class="modal fade" id="soalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="soalModalTitle">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Soal Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="soalForm" method="POST" action="../data/soal-save.php">
                        <input type="hidden" id="question_id" name="question_id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="module_id" class="form-label">Modul <span class="text-danger">*</span></label>
                                <select class="form-select" id="module_id" name="module_id" required>
                                    <option value="">Pilih Modul</option>
                                    <?php 
                                    mysqli_data_seek($modules_result, 0);
                                    while ($module = mysqli_fetch_assoc($modules_result)): 
                                    ?>
                                        <option value="<?php echo $module['id']; ?>">
                                            <?php echo htmlspecialchars($module['module_desc']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="question_text" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="question_text" name="question_text" rows="4" required 
                                      maxlength="1000" placeholder="Masukkan pertanyaan pembelajaran..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Pilihan Jawaban <span class="text-danger">*</span></label>
                            <div id="choices-container">
                                <div class="input-group mb-2">
                                    <div class="input-group-text">
                                        <input type="radio" name="correct_answer" value="0" required checked>
                                    </div>
                                    <input type="text" class="form-control" name="choices[]" placeholder="Pilihan 1" required>
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-text">
                                        <input type="radio" name="correct_answer" value="1" required>
                                    </div>
                                    <input type="text" class="form-control" name="choices[]" placeholder="Pilihan 2" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-success" onclick="addChoice()">
                                <i class="fas fa-plus me-1"></i>Tambah Pilihan
                            </button>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View Soal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Detail Soal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewModalContent">
                    <!-- Content loaded by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function addSoal() {
        $('#soalForm')[0].reset();
        $('#question_id').val('');
        $('#soalModalTitle').html('<i class="fas fa-plus-circle me-2"></i>Tambah Soal Baru');
        $('#soalModal').modal('show');
    }

    function editSoal(id) {
        $.ajax({
            url: '../data/soal-management.php',
            method: 'POST',
            data: { action: 'getSoalDetail', id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var question = response.data.question;
                    var choices = response.data.choices;
                    
                    $('#question_id').val(question.question_id);
                    $('#module_id').val(question.module_id);
                    $('#question_text').val(question.question);
                    
                    // Reset choices
                    $('#choices-container').html('');
                    choices.forEach(function(choice, index) {
                        addChoiceWithValue(choice.answer_desc, index);
                    });
                    
                    // Set correct answer
                    $(`input[name="correct_answer"][value="${question.correct_answer}"]`).prop('checked', true);
                    
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
            data: { action: 'getSoalDetail', id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var question = response.data.question;
                    var choices = response.data.choices;
                    var correctIndex = parseInt(question.correct_answer);
                    
                    var content = `
                        <div class="alert alert-info">
                            <strong>Modul:</strong> ${question.module_desc}
                        </div>
                        <div class="mb-3">
                            <strong>Pertanyaan:</strong>
                            <p>${question.question}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Pilihan Jawaban:</strong>
                            <div class="list-group mt-2">
                    `;
                    
                    choices.forEach(function(choice, index) {
                        var isCorrect = (index == correctIndex);
                        var className = isCorrect ? 'list-group-item-success' : '';
                        var icon = isCorrect ? '<i class="fas fa-check-circle text-success me-2"></i>' : '<i class="fas fa-circle text-muted me-2"></i>';
                        content += `<div class="list-group-item ${className}">${icon}${String.fromCharCode(65 + index)}. ${choice.answer_desc}</div>`;
                    });
                    
                    content += `</div></div>`;
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
                    data: { action: 'deleteSoal', id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    }
                });
            }
        });
    }

    function addChoice() {
        var index = $('#choices-container .input-group').length;
        var html = `
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="radio" name="correct_answer" value="${index}" required>
                </div>
                <input type="text" class="form-control" name="choices[]" placeholder="Pilihan ${index + 1}" required>
            </div>
        `;
        $('#choices-container').append(html);
    }

    function addChoiceWithValue(value, index) {
        var html = `
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="radio" name="correct_answer" value="${index}" required>
                </div>
                <input type="text" class="form-control" name="choices[]" value="${value}" placeholder="Pilihan ${index + 1}" required>
            </div>
        `;
        $('#choices-container').append(html);
    }

    // Handle form submit
    $('#soalForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validasi jawaban benar dipilih
        if (!$('input[name="correct_answer"]:checked').length) {
            Swal.fire('Error!', 'Jawaban benar harus dipilih', 'error');
            return false;
        }
        
        // Validasi minimal 2 pilihan
        var choices = $('input[name="choices[]"]').filter(function() {
            return $(this).val().trim() !== '';
        }).length;
        
        if (choices < 2) {
            Swal.fire('Error!', 'Minimal harus ada 2 pilihan jawaban', 'error');
            return false;
        }
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '../data/soal-save.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                console.log('Error response:', xhr.responseText);
                Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan. Cek console untuk detail.', 'error');
            }
        });
    });
    </script>
</body>
</html>
