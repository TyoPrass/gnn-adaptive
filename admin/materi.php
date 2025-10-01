<?php

include('../config/db.php');
session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

$id = $_GET['module'];
// Enhanced query to get complete context information
$sql = "SELECT m.*, st.sub_topic_desc, st.topic_id, t.topic_desc 
        FROM module m 
        JOIN sub_topic st ON m.sub_topic_id = st.id 
        JOIN topic t ON st.topic_id = t.id 
        WHERE m.id = '{$id}'";
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
            --royal-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --nature-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            --admin-gradient: linear-gradient(135deg, #667eea 0%, #43e97b 100%);
            --content-gradient: linear-gradient(135deg, #667eea 0%, #fee140 100%);
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
            background: var(--content-gradient);
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
        
        .btn-back {
            background: var(--ocean-gradient);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-decoration: none;
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-save {
            background: var(--success-gradient);
            border: none;
            color: white;
            padding: 12px 32px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            font-size: 1.1rem;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
        }
        
        .btn-save:disabled {
            opacity: 0.7;
            transform: none;
            cursor: not-allowed;
        }
        
        /* Navbar Styling */
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
        
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: var(--content-gradient);
            color: white;
            transform: translateX(5px);
        }
        
        .dropdown-header {
            font-weight: 600;
            color: #667eea;
            font-size: 0.9rem;
        }
        
        .badge {
            font-weight: 500;
            font-size: 0.8rem;
        }
        
        .context-info {
            background: var(--nature-gradient);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .breadcrumb-nav {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .breadcrumb-nav a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .breadcrumb-nav a:hover {
            opacity: 1;
            text-decoration: underline;
        }
        
        /* CKEditor Styling */
        .cke {
            border-radius: 10px !important;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
            border: 2px solid #e9ecef !important;
        }
        
        .cke_top {
            background: var(--content-gradient) !important;
            border-bottom: none !important;
        }
        
        .cke_toolbar {
            background: transparent !important;
            border: none !important;
        }
        
        .cke_button {
            border-radius: 5px !important;
            margin: 2px !important;
        }
        
        .cke_button:hover {
            background-color: rgba(255,255,255,0.2) !important;
        }
        
        .cke_button_on {
            background-color: rgba(255,255,255,0.3) !important;
        }
        
        .cke_contents {
            background: white !important;
            min-height: 400px !important;
        }
        
        .cke_wysiwyg_frame {
            min-height: 400px !important;
        }
        
        .editor-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .action-buttons {
            text-align: center;
            padding: 2rem 0;
        }
        
        /* Loading Animation */
        .loading-gif {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }
        
        /* Modern Alert Styling */
        .swal2-popup {
            border-radius: 15px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        
        .swal2-title {
            color: #333 !important;
            font-weight: 600 !important;
        }
        
        .swal2-confirm {
            border-radius: 10px !important;
            padding: 10px 20px !important;
            font-weight: 600 !important;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .content-card {
                margin: 1rem;
                padding: 1rem;
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .context-info {
                padding: 1rem;
            }
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
            <div class="breadcrumb-nav">
                <i class="fas fa-home me-2"></i>
                <a href="index.php">Dashboard</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="topik.php">Topik</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="subtopik.php?topik=<?php echo $result['topic_id']; ?>"><?php echo htmlspecialchars($result['topic_desc']); ?></a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="modul.php?subtopik=<?php echo $result['sub_topic_id']; ?>"><?php echo htmlspecialchars($result['sub_topic_desc']); ?></a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span><?php echo htmlspecialchars($result['module_desc']); ?></span>
            </div>
            
            <h1>
                <i class="fas fa-edit"></i>
                Editor Materi
            </h1>
            <div class="subtitle">
                <i class="fas fa-file-edit me-2"></i>
                Mengedit konten pembelajaran untuk modul: <strong><?php echo htmlspecialchars($result['module_desc']); ?></strong>
                <span class="badge bg-light text-dark ms-3">
                    <i class="fas fa-calendar me-1"></i>
                    <?php echo date('d F Y'); ?>
                </span>
            </div>
        </div>

        <!-- Context Information -->
        <div class="content-card">
            <div class="context-info">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h6 class="mb-1">
                            <i class="fas fa-bookmark me-2"></i>Topik
                        </h6>
                        <strong><?php echo htmlspecialchars($result['topic_desc']); ?></strong>
                    </div>
                    <div class="col-md-3">
                        <h6 class="mb-1">
                            <i class="fas fa-layer-group me-2"></i>Subtopik
                        </h6>
                        <strong><?php echo htmlspecialchars($result['sub_topic_desc']); ?></strong>
                    </div>
                    <div class="col-md-3">
                        <h6 class="mb-1">
                            <i class="fas fa-puzzle-piece me-2"></i>Modul
                        </h6>
                        <strong><?php echo htmlspecialchars($result['module_desc']); ?></strong>
                    </div>
                    <div class="col-md-3">
                        <div class="text-end">
                            <a href="modul.php?subtopik=<?php echo $result['sub_topic_id'] ?>" class="btn btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Modul
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Editor Form -->
            <form action="../data/materi.php?action=simpanMateri" id="formSimpanMateri" method="POST">
                <div class="editor-container">
                    <h4 class="mb-3">
                        <i class="fas fa-file-edit text-primary me-2"></i>
                        Konten Pembelajaran
                    </h4>
                    <textarea name="materi" id="materi" class="mb-3"></textarea>
                </div>
                
                <input type="hidden" name="module" id="module" value="<?php echo $id ?>">
                <input type="hidden" id="type" value="">
                <input type="hidden" id="materi_id">
                
                <div class="action-buttons">
                    <button type="submit" id="saveMateriButton" class="btn btn-save">
                        <i class="fas fa-save me-2"></i>SIMPAN MATERI
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modern JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>

    <script>
    var table;
    $(document).ready(function() {
        // Initialize CKEditor with custom configuration
        CKEDITOR.replace('materi', {
            height: 450,
            toolbar: [
                { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
                { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                '/',
                { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
            ],
            filebrowserUploadUrl: '../data/upload.php',
            filebrowserImageUploadUrl: '../data/upload.php'
        });

        function getMateri() {
            $.ajax({
                url: "../data/materi.php?action=getMateri",
                method: "post",
                data: {
                    module: <?php echo $id ?>,
                },
                dataType: "json",
                success: function(data) {
                    if (data == null) {
                        CKEDITOR.instances.materi.setData('');
                        $('#type').val('1');
                    } else {
                        CKEDITOR.instances.materi.setData(data['materi_desc']);
                        $('#materi_id').val(data['id']);
                        $('#type').val('2');
                    }
                },
                error: function(e) {
                    console.log(e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat memuat data materi'
                    });
                }
            })
        }

        // Load existing material on page load
        getMateri();

        $('#formSimpanMateri').submit(function(e) {
            e.preventDefault();
            
            const materiContent = CKEDITOR.instances.materi.getData();
            const type = $('#type').val();
            
            if (!materiContent.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Konten materi tidak boleh kosong'
                });
                return false;
            }
            
            const saveButton = $('#saveMateriButton');
            const originalText = saveButton.html();
            
            if (type == '1') {
                // Save new material
                $.ajax({
                    url: "../data/materi.php?action=simpanMateri",
                    method: "post",
                    data: {
                        module: $('#module').val(),
                        materi: materiContent,
                    },
                    beforeSend: function() {
                        saveButton.html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');
                        saveButton.prop('disabled', true);
                    },
                    success: function(data) {
                        saveButton.prop('disabled', false);
                        saveButton.html(originalText);
                        getMateri();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Materi berhasil disimpan',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(e) {
                        saveButton.prop('disabled', false);
                        saveButton.html(originalText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan materi'
                        });
                        console.log(e);
                    }
                });
            } else {
                // Update existing material
                $.ajax({
                    url: "../data/materi.php?action=updateMateri",
                    method: "post",
                    data: {
                        materi_id: $('#materi_id').val(),
                        materi: materiContent,
                    },
                    beforeSend: function() {
                        saveButton.html('<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...');
                        saveButton.prop('disabled', true);
                    },
                    success: function(data) {
                        saveButton.prop('disabled', false);
                        saveButton.html(originalText);
                        getMateri();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Materi berhasil diperbarui',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(e) {
                        saveButton.prop('disabled', false);
                        saveButton.html(originalText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat memperbarui materi'
                        });
                        console.log(e);
                    }
                });
            }
            
            return false;
        });

        // Auto-save functionality (optional)
        let autoSaveTimer;
        CKEDITOR.instances.materi.on('change', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                // Optional: implement auto-save here
            }, 30000); // Auto-save after 30 seconds of no changes
        });
    });
    </script>
</body>

</html>