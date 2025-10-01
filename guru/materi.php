<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

// mengambil data materi sesuai id modul
$id = $_GET['module'];
$sql = "SELECT * FROM module WHERE id = '{$id}'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_array($query, MYSQLI_ASSOC);
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Editor Materi - MyIRT Adaptive Learning</title>
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
            --material-gradient: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
            --editor-gradient: linear-gradient(135deg, #667eea 0%, #43e97b 100%);
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
            background: var(--material-gradient);
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
            border-left: 4px solid var(--material-gradient);
        }
        
        .editor-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
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
            color: var(--material-gradient);
            font-weight: 600;
        }
        
        /* Enhanced CKEditor styling */
        .cke {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .cke_top {
            background: var(--editor-gradient) !important;
            border-bottom: 2px solid rgba(255,255,255,0.2) !important;
        }
        
        .cke_toolbar {
            background: transparent !important;
        }
        
        .cke_button {
            background: rgba(255,255,255,0.1) !important;
            border-radius: 5px !important;
            margin: 2px !important;
            transition: all 0.3s ease !important;
        }
        
        .cke_button:hover {
            background: rgba(255,255,255,0.2) !important;
            transform: translateY(-1px) !important;
        }
        
        .cke_button_icon {
            filter: brightness(0) invert(1) !important;
        }
        
        .cke_contents {
            border-radius: 0 0 10px 10px;
            min-height: 400px;
        }
        
        .auto-save-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--success-gradient);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1050;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .auto-save-indicator.show {
            opacity: 1;
        }
        
        .editor-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .word-count {
            background: rgba(67, 233, 123, 0.1);
            color: #43e97b;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        
        .editor-container {
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
            
            .editor-actions {
                flex-direction: column;
                align-items: stretch;
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
    
    <!-- Auto-save indicator -->
    <div class="auto-save-indicator" id="autoSaveIndicator">
        <i class="fas fa-save me-2"></i>Tersimpan otomatis
    </div>
    
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
                <i class="fas fa-edit"></i>
                Editor Materi Pembelajaran
            </h1>
            <div class="subtitle">
                <i class="fas fa-cube me-2"></i>
                Mengedit konten materi: <strong><?php echo htmlspecialchars($result['module_desc']); ?></strong>
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
                        <a href="modul.php?subtopik=<?php echo $result['sub_topic_id']; ?>" class="text-decoration-none">
                            <i class="fas fa-cube me-1"></i>Modul
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-edit me-1"></i><?php echo htmlspecialchars($result['module_desc']); ?>
                    </li>
                </ol>
            </nav>
        </div>


        <!-- Enhanced Editor Container -->
        <div class="editor-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Editor Konten Materi
                    </h3>
                    <p class="text-muted mb-0">Mengedit materi: <strong><?php echo htmlspecialchars($result['module_desc']); ?></strong></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="modul.php?subtopik=<?php echo $result['sub_topic_id']; ?>" class="btn btn-info">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Modul
                    </a>
                </div>
            </div>

            <?php if ($level_user == 1 || $level_user == 2): // Admin atau Guru dapat mengedit ?>
            <form id="formSimpanMateri">
                <div class="editor-actions">
                    <button type="button" class="btn btn-warning" id="btnPreview">
                        <i class="fas fa-eye me-2"></i>Preview
                    </button>
                    <button type="button" class="btn btn-success" id="btnAutoSave">
                        <i class="fas fa-magic me-2"></i>Auto Save: ON
                    </button>
                    <div class="word-count">
                        <i class="fas fa-calculator"></i>
                        <span id="wordCount">0 kata</span>
                    </div>
                    <div class="ms-auto">
                        <button type="submit" class="btn btn-primary" id="saveMateriButton">
                            <i class="fas fa-save me-2"></i>Simpan Materi
                        </button>
                    </div>
                </div>
                
                <div class="editor-wrapper">
                    <textarea name="materi" id="materi" class="form-control"></textarea>
                </div>
                
                <input type="hidden" name="module" id="module" value="<?php echo $id ?>">
                <input type="hidden" id="type" value="">
                <input type="hidden" id="materi_id">
            </form>
            <?php else: ?>
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-lock me-2"></i>
                <strong>Akses Terbatas!</strong> Anda tidak memiliki izin untuk mengedit materi pembelajaran.
            </div>
            <div class="content-preview" id="contentPreview">
                <!-- Konten materi akan ditampilkan di sini untuk user biasa -->
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modern JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CKEditor 5 with enhanced features -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    
    <script>
    var editor;
    var autoSaveInterval;
    var levelUser = <?php echo json_encode($level_user); ?>;
    var moduleId = <?php echo $id ?>;
    var isEditorReady = false;
    var hasUnsavedChanges = false;
    
    $(document).ready(function() {
        // Initialize CKEditor 5 for Admin and Guru only
        if (levelUser == 1 || levelUser == 2) {
            ClassicEditor
                .create(document.querySelector('#materi'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'underline', 'strikethrough', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                            'alignment', '|',
                            'numberedList', 'bulletedList', '|',
                            'outdent', 'indent', '|',
                            'link', 'blockQuote', 'insertTable', '|',
                            'imageUpload', 'mediaEmbed', '|',
                            'undo', 'redo', '|',
                            'sourceEditing'
                        ]
                    },
                    fontSize: {
                        options: [ 9, 11, 13, 'default', 17, 19, 21, 27, 35 ]
                    },
                    fontFamily: {
                        options: [
                            'default',
                            'Arial, Helvetica, sans-serif',
                            'Courier New, Courier, monospace',
                            'Georgia, serif',
                            'Lucida Sans Unicode, Lucida Grande, sans-serif',
                            'Tahoma, Geneva, sans-serif',
                            'Times New Roman, Times, serif',
                            'Trebuchet MS, Helvetica, sans-serif',
                            'Verdana, Geneva, sans-serif'
                        ]
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                        ]
                    },
                    table: {
                        contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                    },
                    language: 'id'
                })
                .then(editorInstance => {
                    editor = editorInstance;
                    isEditorReady = true;
                    
                    // Load existing material
                    getMateri();
                    
                    // Setup auto-save
                    setupAutoSave();
                    
                    // Update word count on content change
                    editor.model.document.on('change:data', () => {
                        updateWordCount();
                        hasUnsavedChanges = true;
                        updateEditorStatus('Mengedit...');
                    });
                    
                    updateEditorStatus('Siap');
                })
                .catch(error => {
                    console.error('Error initializing CKEditor:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat editor. Silakan refresh halaman.'
                    });
                });
        } else {
            // For non-admin/guru users, just load and display content
            getMateri();
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
        
        updateTime();
        setInterval(updateTime, 60000); // Update every minute
        
        // Form submission
        $('#formSimpanMateri').submit(function(e) {
            e.preventDefault();
            if (levelUser == 1 || levelUser == 2) {
                simpanMateri();
            }
        });
        
        // Preview button
        $('#btnPreview').click(function() {
            if (editor && isEditorReady) {
                const content = editor.getData();
                showPreview(content);
            }
        });
        
        // Auto-save toggle
        $('#btnAutoSave').click(function() {
            toggleAutoSave();
        });
        
        // Warn before leaving with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    });
    
    function getMateri() {
        $.ajax({
            url: "../data/materi.php?action=getMateri",
            method: "POST",
            data: { module: moduleId },
            dataType: "json",
            success: function(data) {
                if (data == null) {
                    $('#type').val('1'); // New material
                    if (levelUser != 1 && levelUser != 2) {
                        $('#contentPreview').html('<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Belum ada materi yang tersedia untuk modul ini.</div>');
                    }
                } else {
                    $('#materi_id').val(data.id);
                    $('#type').val('2'); // Edit existing
                    
                    if (editor && isEditorReady) {
                        editor.setData(data.materi_desc);
                        setTimeout(updateWordCount, 100);
                    } else if (levelUser != 1 && levelUser != 2) {
                        // Show content for non-editor users
                        $('#contentPreview').html('<div class="content-display">' + data.materi_desc + '</div>');
                    }
                }
                hasUnsavedChanges = false;
            },
            error: function(xhr) {
                console.error('Error loading material:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat materi'
                });
            }
        });
    }
    
    function simpanMateri() {
        if (!editor || !isEditorReady) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Editor belum siap. Mohon tunggu sebentar.'
            });
            return;
        }
        
        const submitBtn = $('#saveMateriButton');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');
        submitBtn.prop('disabled', true);
        
        const isNewMaterial = $('#type').val() == '1';
        const action = isNewMaterial ? 'simpanMateri' : 'updateMateri';
        const requestData = {
            module: moduleId,
            materi: editor.getData()
        };
        
        if (!isNewMaterial) {
            requestData.materi_id = $('#materi_id').val();
        }
        
        $.ajax({
            url: `../data/materi.php?action=${action}`,
            method: "POST",
            data: requestData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Materi berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                });
                
                hasUnsavedChanges = false;
                updateEditorStatus('Tersimpan');
                showAutoSaveIndicator();
                
                // Reload to get updated data
                setTimeout(() => {
                    getMateri();
                }, 500);
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan saat menyimpan materi';
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
    }
    
    function setupAutoSave() {
        autoSaveInterval = setInterval(() => {
            if (hasUnsavedChanges && editor && isEditorReady) {
                const content = editor.getData();
                if (content.trim().length > 0) {
                    autoSaveMateri();
                }
            }
        }, 30000); // Auto-save every 30 seconds
    }
    
    function autoSaveMateri() {
        if (!editor || !isEditorReady) return;
        
        const isNewMaterial = $('#type').val() == '1';
        const action = isNewMaterial ? 'simpanMateri' : 'updateMateri';
        const requestData = {
            module: moduleId,
            materi: editor.getData()
        };
        
        if (!isNewMaterial) {
            requestData.materi_id = $('#materi_id').val();
        }
        
        $.ajax({
            url: `../data/materi.php?action=${action}`,
            method: "POST",
            data: requestData,
            success: function(response) {
                hasUnsavedChanges = false;
                updateEditorStatus('Auto-saved');
                showAutoSaveIndicator();
                
                // Update material ID if it was a new material
                if (isNewMaterial && response.id) {
                    $('#materi_id').val(response.id);
                    $('#type').val('2');
                }
            },
            error: function(xhr) {
                console.error('Auto-save failed:', xhr);
            }
        });
    }
    
    function toggleAutoSave() {
        const btn = $('#btnAutoSave');
        if (autoSaveInterval) {
            clearInterval(autoSaveInterval);
            autoSaveInterval = null;
            btn.html('<i class="fas fa-pause me-2"></i>Auto Save: OFF');
            btn.removeClass('btn-success').addClass('btn-secondary');
            $('#autoSaveStatus').text('Nonaktif');
        } else {
            setupAutoSave();
            btn.html('<i class="fas fa-magic me-2"></i>Auto Save: ON');
            btn.removeClass('btn-secondary').addClass('btn-success');
            $('#autoSaveStatus').text('Aktif');
        }
    }
    
    function updateWordCount() {
        if (editor && isEditorReady) {
            const content = editor.getData();
            const text = content.replace(/<[^>]*>/g, '').trim();
            const wordCount = text.split(/\s+/).filter(word => word.length > 0).length;
            $('#wordCount').text(`${wordCount} kata`);
        }
    }
    
    function updateEditorStatus(status) {
        $('#editorStatus').text(status);
    }
    
    function showAutoSaveIndicator() {
        const indicator = $('#autoSaveIndicator');
        indicator.addClass('show');
        setTimeout(() => {
            indicator.removeClass('show');
        }, 2000);
    }
    
    function showPreview(content) {
        Swal.fire({
            title: 'Preview Materi',
            html: `<div style="text-align: left; max-height: 400px; overflow-y: auto;">${content}</div>`,
            width: '80%',
            showCloseButton: true,
            confirmButtonText: 'Tutup',
            customClass: {
                popup: 'preview-modal'
            }
        });
    }
    
    // Breadcrumb click animation
    $('.breadcrumb-item a').on('click', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            Swal.fire({
                title: 'Perubahan Belum Disimpan',
                text: 'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Tinggalkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        } else {
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
        }
    });
    </script>
</body>

</html>