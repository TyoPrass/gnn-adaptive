<?php

session_start();

if (!isset($_SESSION['name'])) {
    header('location: ../sign-in.php');
}

include('../config/db.php');

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Survey Pembelajaran Biologi - MyIRT Adaptive Learning</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --bio-primary: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --bio-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bio-accent: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --bio-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --bio-leaf: #2ed573;
            --bio-plant: #7bed9f;
            --bio-dna: #70a1ff;
        }
        
        body {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .survey-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            position: relative;
        }
        
        .survey-header {
            background: var(--bio-primary);
            color: white;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .survey-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 100px;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M50 10 L60 30 L80 30 L65 45 L70 65 L50 55 L30 65 L35 45 L20 30 L40 30 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 6s ease-in-out infinite;
        }
        
        .survey-header::after {
            content: '🧬';
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 3rem;
            opacity: 0.3;
            animation: rotate 10s linear infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .progress-container {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            height: 8px;
            margin: 1rem 0;
            overflow: hidden;
        }
        
        .progress-bar-bio {
            height: 100%;
            background: linear-gradient(90deg, #fff 0%, rgba(255,255,255,0.8) 100%);
            border-radius: 10px;
            transition: width 0.3s ease;
            position: relative;
        }
        
        .question-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .question-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background: var(--bio-primary);
            border-radius: 0 5px 5px 0;
        }
        
        .question-card:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-color: rgba(46, 213, 115, 0.3);
        }
        
        .question-number {
            background: var(--bio-primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
            box-shadow: 0 4px 15px rgba(46, 213, 115, 0.4);
        }
        
        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        .bio-radio {
            position: relative;
            margin: 0.8rem 0;
            padding-left: 2rem;
        }
        
        .bio-radio input[type="radio"] {
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            opacity: 0;
            cursor: pointer;
        }
        
        .bio-radio-custom {
            position: absolute;
            left: 0;
            top: 2px;
            width: 20px;
            height: 20px;
            border: 2px solid #11998e;
            border-radius: 50%;
            background: white;
            transition: all 0.3s ease;
        }
        
        .bio-radio-custom::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--bio-primary);
            transition: transform 0.2s ease;
        }
        
        .bio-radio input[type="radio"]:checked + .bio-radio-custom {
            background: #e8f8f5;
            border-color: #11998e;
            box-shadow: 0 0 0 3px rgba(17, 153, 142, 0.2);
        }
        
        .bio-radio input[type="radio"]:checked + .bio-radio-custom::after {
            transform: translate(-50%, -50%) scale(1);
        }
        
        .bio-radio-label {
            font-weight: 500;
            color: #34495e;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .bio-radio:hover .bio-radio-label {
            color: #11998e;
        }
        
        .submit-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
        }
        
        .btn-submit-bio {
            background: var(--bio-primary);
            color: white;
            border: none;
            padding: 1rem 3rem;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .btn-submit-bio::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-submit-bio:hover::before {
            left: 100%;
        }
        
        .btn-submit-bio:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(17, 153, 142, 0.6);
        }
        
        .bio-icons {
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            opacity: 0.6;
        }
        
        .instruction-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #f1f8e9 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border-left: 5px solid #11998e;
        }
        
        @media (max-width: 768px) {
            .survey-header {
                padding: 2rem 1rem;
                text-align: center;
            }
            .question-card {
                padding: 1.5rem;
            }
            .question-card:hover {
                transform: translateY(-5px);
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background: var(--bio-primary); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">
                <i class="fas fa-leaf me-2"></i>Biologi Learning
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
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="survey-container">
                        <!-- Header Section -->
                        <div class="survey-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h1 class="display-6 fw-bold mb-3">
                                        <i class="fas fa-microscope me-3"></i>Survey Pembelajaran Biologi
                                    </h1>
                                    <p class="lead mb-3">Bantu kami menentukan level pembelajaran yang tepat untuk Anda</p>
                                    <div class="progress-container">
                                        <div class="progress-bar-bio" id="surveyProgress" style="width: 0%"></div>
                                    </div>
                                    <small class="d-block mt-2">Progress: <span id="progressText">0%</span></small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="fas fa-dna fa-4x" style="opacity: 0.4;"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="p-4">
                            <div class="instruction-card">
                                <h5 class="fw-bold mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>Petunjuk Pengisian Survey
                                </h5>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Jawablah semua pertanyaan dengan jujur sesuai kemampuan Anda
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Pilih "Ya" jika Anda menguasai/memahami materi tersebut
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Pilih "Tidak" jika Anda belum menguasai/memahami materi tersebut
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-success me-2"></i>
                                        Survey ini akan membantu menentukan level pembelajaran yang sesuai
                                    </li>
                                </ul>
                            </div>

                            <!-- Survey Form -->
                            <form action="../action/save-survey.php" method="POST" id="surveyForm">
                                <div id="questionsContainer">
                                    <?php
                                    // Mengambil data pertanyaan survey dari database
                                    $question = mysqli_query($conn, "SELECT * FROM survey_question ORDER BY id");
                                    $questions = mysqli_fetch_all($question, MYSQLI_ASSOC);
                                    $totalQuestions = count($questions);
                                    
                                    foreach ($questions as $index => $q) {
                                    ?>
                                    <div class="question-card" data-question="<?php echo $index + 1; ?>">
                                        <div class="d-flex align-items-start">
                                            <div class="question-number">
                                                <?php echo $q['id']; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="question-text mb-3">
                                                    <?php echo $q['question']; ?>
                                                </div>
                                                
                                                <div class="options-container">
                                                    <div class="bio-radio">
                                                        <input type="radio" 
                                                               id="q<?php echo $q['id']; ?>_yes" 
                                                               name="question<?php echo $q['id']; ?>" 
                                                               value="1" 
                                                               required 
                                                               onchange="updateProgress()">
                                                        <span class="bio-radio-custom"></span>
                                                        <label class="bio-radio-label" for="q<?php echo $q['id']; ?>_yes">
                                                            <i class="fas fa-check-circle text-success me-2"></i>Ya, saya memahami
                                                        </label>
                                                    </div>
                                                    
                                                    <div class="bio-radio">
                                                        <input type="radio" 
                                                               id="q<?php echo $q['id']; ?>_no" 
                                                               name="question<?php echo $q['id']; ?>" 
                                                               value="0" 
                                                               onchange="updateProgress()">
                                                        <span class="bio-radio-custom"></span>
                                                        <label class="bio-radio-label" for="q<?php echo $q['id']; ?>_no">
                                                            <i class="fas fa-times-circle text-danger me-2"></i>Tidak, belum memahami
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="bio-icons">
                                                <?php 
                                                // Array icon biologi
                                                $bioIcons = ['🧬', '🔬', '🦠', '🧪', '🌱', '🦎', '🐛', '🌿', '🧫', '🔍'];
                                                echo $bioIcons[($q['id'] - 1) % count($bioIcons)];
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                                <!-- Submit Section -->
                                <div class="submit-section">
                                    <div class="mb-3">
                                        <h5 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-clipboard-check me-2"></i>Selesaikan Survey
                                        </h5>
                                        <p class="text-muted">Pastikan semua pertanyaan sudah dijawab sebelum mengirim</p>
                                    </div>
                                    
                                    <button type="submit" class="btn-submit-bio" id="submitBtn" disabled>
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Survey
                                    </button>
                                    
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            Data Anda aman dan hanya digunakan untuk keperluan pembelajaran
                                        </small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const totalQuestions = <?php echo $totalQuestions; ?>;
        let answeredQuestions = 0;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize form
            updateProgress();
            
            // Add form validation
            const form = document.getElementById('surveyForm');
            form.addEventListener('submit', handleFormSubmit);
            
            // Add smooth scrolling to next question
            addScrollToNextQuestion();
            
            // Add question animation on scroll
            addScrollAnimations();
        });
        
        function updateProgress() {
            // Count answered questions
            answeredQuestions = 0;
            const questionGroups = document.querySelectorAll('[name^="question"]');
            const uniqueQuestions = new Set();
            
            questionGroups.forEach(input => {
                if (input.checked) {
                    uniqueQuestions.add(input.name);
                }
            });
            
            answeredQuestions = uniqueQuestions.size;
            const progress = (answeredQuestions / totalQuestions) * 100;
            
            // Update progress bar
            document.getElementById('surveyProgress').style.width = progress + '%';
            document.getElementById('progressText').textContent = Math.round(progress) + '%';
            
            // Enable submit button if all questions answered
            const submitBtn = document.getElementById('submitBtn');
            if (answeredQuestions === totalQuestions) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Kirim Survey';
                submitBtn.classList.add('pulse');
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="fas fa-clock me-2"></i>Jawab ${totalQuestions - answeredQuestions} pertanyaan lagi`;
                submitBtn.classList.remove('pulse');
            }
        }
        
        function handleFormSubmit(e) {
            e.preventDefault();
            
            // Show confirmation
            if (confirm('Apakah Anda yakin ingin mengirim survey ini? Data tidak dapat diubah setelah dikirim.')) {
                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
                submitBtn.disabled = true;
                
                // Simulate delay then submit
                setTimeout(() => {
                    e.target.submit();
                }, 1000);
            }
        }
        
        function addScrollToNextQuestion() {
            const radioButtons = document.querySelectorAll('input[type="radio"]');
            
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Add a slight delay then scroll to next question
                    setTimeout(() => {
                        const currentCard = this.closest('.question-card');
                        const nextCard = currentCard.nextElementSibling;
                        
                        if (nextCard && nextCard.classList.contains('question-card')) {
                            nextCard.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center' 
                            });
                        } else if (answeredQuestions === totalQuestions) {
                            // Scroll to submit section
                            document.querySelector('.submit-section').scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center' 
                            });
                        }
                    }, 300);
                });
            });
        }
        
        function addScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Observe all question cards
            document.querySelectorAll('.question-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        }
        
        // Add pulse animation class
        const style = document.createElement('style');
        style.textContent = `
            .pulse {
                animation: pulse 1.5s infinite;
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
        
        // Add success message after form submission
        function showSuccessMessage() {
            const container = document.querySelector('.survey-container');
            container.innerHTML = `
                <div class="survey-header text-center">
                    <div class="py-5">
                        <i class="fas fa-check-circle fa-5x mb-4" style="color: white;"></i>
                        <h2 class="display-5 fw-bold mb-3">Survey Berhasil Dikirim!</h2>
                        <p class="lead">Terima kasih telah mengisi survey pembelajaran biologi.</p>
                        <p>Sistem akan memproses jawaban Anda untuk menentukan level pembelajaran yang tepat.</p>
                        <a href="index.php" class="btn btn-light btn-lg mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            `;
        }
    </script>
</body>
</html>