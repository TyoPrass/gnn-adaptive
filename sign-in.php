<?php

session_start();

if (isset($_SESSION['name'])) {
    header('location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - MyIRT Adaptive Learning</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            .card {
                border-radius: 15px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.1);
                backdrop-filter: blur(10px);
            }
            .card-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 15px 15px 0 0 !important;
            }
            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 25px;
                padding: 12px 30px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            }
            .form-check-input:checked {
                background-color: #667eea;
                border-color: #667eea;
            }
            .alert-danger {
                border-radius: 10px;
                border: none;
                background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
                color: white;
            }
            .card-footer {
                background: rgba(255,255,255,0.1);
                border-top: 1px solid rgba(255,255,255,0.2);
                border-radius: 0 0 15px 15px;
            }
            .form-floating > label {
                color: #6c757d;
            }
            .form-floating > .form-control:focus ~ label,
            .form-floating > .form-control:not(:placeholder-shown) ~ label {
                color: #667eea;
            }
            @media (max-width: 768px) {
                .card {
                    margin: 1rem;
                }
            }
            .login-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
                color: white;
            }
        </style>
</head>

<body class="bg-primary">
	<div id="layoutAuthentication">
    	<div id="layoutAuthentication_content">
        	<main>
            	<div class="container">
                	<div class="row justify-content-center">
                    	<div class="col-lg-5 col-md-7">
                        	<div class="card shadow-lg border-0 rounded-lg mt-5">
                            	<div class="card-header text-center">
                            		<i class="fas fa-user-circle login-icon"></i>
                            		<h3 class="font-weight-light my-2">Masuk ke Akun</h3>
                            	</div>
                                <div class="card-body p-4">
        							<form method="POST" action="action/login.php" id="loginForm">
            								<?php if (isset($_SESSION['error_sign_in'])) { ?>
            								<div class="alert alert-danger alert-dismissible" role="alert">
                									<i class="fas fa-exclamation-triangle me-2"></i>
                									<?php echo $_SESSION['error_sign_in']; unset($_SESSION['error_sign_in']); ?>
                									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            								</div>
            								<?php } ?>
            								
            								<div class="form-floating mb-3">
                								<input type="text" class="form-control" id="login" name="login" placeholder="Username/Email/NIS/NIP" required>
                								<label for="login"><i class="fas fa-user me-1"></i>Username/Email/NIS/NIP</label>
            								</div>
            								
            								<div class="form-floating mb-3">
                								<input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                								<label for="password"><i class="fas fa-lock me-1"></i>Password</label>
            								</div>
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                            <label class="form-check-label" for="inputRememberPassword">
                                            	<i class="fas fa-check-circle me-1"></i>Ingat saya
                                            </label>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                        	<a class="small text-decoration-none" href="#" style="color: #667eea;">
                                        		<i class="fas fa-question-circle me-1"></i>Lupa Password?
                                        	</a>
                                        </div>
												
            								<div class="d-grid mb-3">
            									<button class="btn btn-primary btn-lg" type="submit">
            										<i class="fas fa-sign-in-alt me-2"></i>Masuk
            									</button>
            								</div>
        							</form>
   							</div>
   							
							<div class="card-footer text-center py-3">
                				<div class="small">
                					<span class="text-muted">Belum punya akun?</span>
                					<a href="sign-up.php" class="text-decoration-none" style="color: #667eea; font-weight: 600;">
                						<i class="fas fa-user-plus me-1"></i>Daftar Sekarang!
                					</a>
                				</div>
                			</div>                       		</div>
                    	</div>
                   	</div>
            	</div>
    		</main>
    	</div>
        <div id="layoutAuthentication_footer">
        	<footer class="py-4 mt-auto" style="background: rgba(255,255,255,0.1);">
            	<div class="container-fluid px-4">
               		<div class="d-flex align-items-center justify-content-between small text-white">
                    	<div>Copyright &copy; MyIRT Adaptive Learning 2025</div>
                        <div>
                          	<a href="#" class="text-white-50 text-decoration-none">Privacy Policy</a>
                            <span class="mx-1">&middot;</span>
                            <a href="#" class="text-white-50 text-decoration-none">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
	</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
    // Form validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const login = document.getElementById('login').value.trim();
        const password = document.getElementById('password').value.trim();
        
        if (!login || !password) {
            e.preventDefault();
            
            // Create alert if not exists
            let existingAlert = document.querySelector('.alert');
            if (!existingAlert) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Username dan password harus diisi.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                const form = document.getElementById('loginForm');
                form.insertBefore(alertDiv, form.firstChild);
            }
        }
    });
    
    // Auto focus on login field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('login').focus();
    });
    
    // Enter key handling
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('loginForm').dispatchEvent(new Event('submit'));
        }
    });
    </script>
</body>
</html>