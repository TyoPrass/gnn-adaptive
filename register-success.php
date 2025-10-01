<?php

session_start();

if (isset($_SESSION['name'])) {
    header('location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <title>Registrasi Berhasil | MyIRT Adaptive Learning</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .success-card {
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            animation: checkmark 0.5s ease-in-out;
        }
        @keyframes checkmark {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card success-card">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle success-icon"></i>
                        </div>
                        <h2 class="text-success mb-3">Registrasi Berhasil!</h2>
                        <?php if (isset($_SESSION['success_message'])) { ?>
                            <p class="text-muted mb-4"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
                        <?php } else { ?>
                            <p class="text-muted mb-4">Selamat! Akun Anda telah berhasil dibuat. Silakan login untuk mulai menggunakan sistem.</p>
                        <?php } ?>
                        
                        <div class="d-grid gap-2">
                            <a href="sign-in.php" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                            </a>
                            <a href="index.php" class="btn btn-outline-secondary">Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>