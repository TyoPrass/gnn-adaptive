<?php

session_start();
session_destroy();

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
    <title>Register - MyIRT Adaptive Learning</title>
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
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .alert-danger {
            border-radius: 10px;
        }
        @media (max-width: 768px) {
            .card {
                margin: 1rem;
            }
        }
    </style>
</head>
<body class="bg-primary">
	<div id="layoutAuthentication">
    	<div id="layoutAuthentication_content">
    		<main>
    	        <div class="container">
                	<div class="row justify-content-center">
                    	<div class="col-lg-8">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                        	<div class="card-header">
                        		<h3 class="text-center font-weight-light my-4">Buat Akun Baru</h3>
                        	</div>
                        	<div class="card-body">
        						<form method="POST" action="action/register.php" id="registerMainForm">
            						<?php if (isset($_SESSION['error_message'])) { ?>
            						<div class="alert alert-danger alert-dismissible" role="alert">
                						<?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            						</div>
            						<?php } ?>
            						<!-- Select User Type -->
            						<div class="row mb-3">
            							<div class="col-12">
            								<div class="form-floating">
            									<select class="form-select" id="level" name="level" required>
                									<option value="" selected disabled>--Pilih Jenis User--</option>
                									<option value="2">Guru</option>
                									<option value="3">Murid</option>
            									</select>
            									<label for="level">Jenis User</label>
            								</div>
            							</div>
            						</div>
            						
                                    <div id="registerForm" class="d-none">
                						<!-- Teacher Type Selection -->
                						<div id="teacherTypeSection" class="mb-3" style="display: none;">
                    						<label class="form-label">Jenis Guru:</label>
                    						<div class="row">
                                                <div class="col-md-6">
                    									<div class="form-check">
                        									<input class="form-check-input" type="radio" name="tipeguru" id="tipeguru1" value="1">
                        									<label class="form-check-label" for="tipeguru1">PNS</label>
                    									</div>
                                                </div>
                                                <div class="col-md-6">
                    									<div class="form-check">
                        									<input class="form-check-input" type="radio" name="tipeguru" id="tipeguru2" value="2">
                        									<label class="form-check-label" for="tipeguru2">Honorer</label>
                    									</div>
                        						</div>
                        					</div>
                                       	</div>
                                       	
                                       	<!-- Teacher Credentials -->
										<div id="teacherCredentials" class="mb-3" style="display: none;">
											<div class="form-floating" id="nipGroup" style="display: none;">
                            					<input type="text" class="form-control" id="nip" name="nip" placeholder="NIP">
                            					<label for="nip">NIP</label>
                        					</div>
                        					<div class="form-floating" id="emailGroup" style="display: none;">
                            					<input type="email" class="form-control" id="email" name="email" placeholder="Email">
                            					<label for="email">Email</label>
                        					</div>
                                        </div>
                                        
                                        <!-- Student NIS -->
                                        <div class="row mb-3">
                                  	    	<div class="col-12">
                                  	    		<div class="form-floating" id="nisForm" style="display: none;">
                    								<input type="text" class="form-control" id="nis" name="nis" placeholder="NIS">
                    								<label for="nis">NIS</label>
                								</div>
                							</div>
                                        </div>
                                        <!-- Name Field -->
                                        <div class="row mb-3">
                                  	    	<div class="col-12">
                                  	    		<div class="form-floating">
                    								<input type="text" class="form-control" id="name" name="name" placeholder="Nama" required>
                    								<label for="name">Nama Lengkap</label>
                								</div>
                							</div>
                                        </div>
                                        
                                        <!-- Address Field -->
                    					<div class="row mb-3">
                    						<div class="col-12">
                                  	    		<div class="form-floating">
                    								<textarea class="form-control" id="address" name="address" placeholder="Alamat" style="height: 100px;" required></textarea>
                    								<label for="address">Alamat</label>
                								</div>
                							</div>
                                        </div>
                                        
                                        <!-- Phone Number Field -->
                                        <div class="row mb-3">
                                        	<div class="col-12">
                                  	    		<div class="form-floating">
                    								<input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="0812345678" required>
                    								<label for="phone_number">Nomor Telepon</label>
                								</div>
                							</div>
                                        </div>
                                        
                                        <!-- Class Selection -->
                                        <div class="row mb-3">
                                            <div class="col-12">
                                  	    		<!-- For Students -->
                                  	    		<div class="form-floating selectClass" style="display: none;">
                    								<select class="form-select" id="class_id" name="class_id">
                    									<option value="" selected disabled>--Pilih Kelas--</option>
                    									<option value="1">A</option>
                    									<option value="2">B</option>
                    									<option value="3">C</option>
                   	 									<option value="4">D</option>
                    									<option value="5">E</option>
                									</select>
                									<label for="class_id">Kelas</label>
                								</div>
                								
                								<!-- For Teachers -->
                								<div class="checkKelas" style="display: none;">
                    								<label class="form-label">Pilih Kelas yang Diampu:</label>
                    								<div class="row">
                    									<div class="col-md-4">
                        									<div class="form-check">
                        										<input class="form-check-input" type="checkbox" id="kelas1" name="kelas1" value="1">
                        										<label class="form-check-label" for="kelas1">Kelas A</label>
                    										</div>
                    									</div>
                    									<div class="col-md-4">
                        									<div class="form-check">
                        										<input class="form-check-input" type="checkbox" id="kelas2" name="kelas2" value="2">
                        										<label class="form-check-label" for="kelas2">Kelas B</label>
                    										</div>
                    									</div>
                    									<div class="col-md-4">
                        									<div class="form-check">
                        										<input class="form-check-input" type="checkbox" id="kelas3" name="kelas3" value="3">
                        										<label class="form-check-label" for="kelas3">Kelas C</label>
                    										</div>
                    									</div>
                    									<div class="col-md-4 mt-2">
                        									<div class="form-check">
                        										<input class="form-check-input" type="checkbox" id="kelas4" name="kelas4" value="4">
                        										<label class="form-check-label" for="kelas4">Kelas D</label>
                    										</div>
                    									</div>
                    									<div class="col-md-4 mt-2">
                        									<div class="form-check">
                        										<input class="form-check-input" type="checkbox" id="kelas5" name="kelas5" value="5">
                        										<label class="form-check-label" for="kelas5">Kelas E</label>
                    										</div>
                    									</div>
                									</div>
                								</div>
                                        	</div>
                                 		</div>
                                 		
                                 		<!-- Password Field -->
                                 		<div class="row mb-3">
                                 			<div class="col-12">
                								<div class="form-floating">
                    								<input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    								<label for="password">Password</label>
                								</div>
                							</div>
                						</div>
            						</div>
            						
            						<!-- Submit Button -->
            						<div class="d-grid">
            							<button class="btn btn-primary btn-lg" type="submit">Daftar</button>
            						</div>
            						
            						<hr class="my-4">
            						
            						<!-- Sign In Link -->
            						<div class="d-grid">
            							<a href="sign-in.php" class="btn btn-outline-dark btn-lg">Sudah punya akun? Masuk</a>
            						</div>
        						</form>
                        	</div>
                        </div>
                    	</div>
                	</div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="js/jquery.js"></script>
    <script>
    $(document).ready(function() {
        var requiredCheckboxes = $('.checkKelas input[type="checkbox"]');
        
        // Handle user type selection
        $('#level').on('change', function() {
            var userType = $(this).val();
            $('#registerForm').removeClass('d-none').slideDown();
            
            if (userType == '2') { // Teacher
                $('#teacherTypeSection').show();
                $('#nisForm').hide();
                $('.checkKelas').show();
                $('.selectClass').hide();
                
                // Reset required attributes
                $('#nis').prop('required', false);
                $('#class_id').prop('required', false);
                
            } else if (userType == '3') { // Student
                $('#teacherTypeSection').hide();
                $('#teacherCredentials').hide();
                $('#nisForm').show();
                $('.checkKelas').hide();
                $('.selectClass').show();
                
                // Set required attributes
                $('#nis').prop('required', true);
                $('#class_id').prop('required', true);
                $('#nip').prop('required', false);
                $('#email').prop('required', false);
                
                // Clear teacher type selection
                $('input[name="tipeguru"]').prop('checked', false);
            }
        });
        
        // Handle teacher type selection
        $('input[type=radio][name=tipeguru]').change(function() {
            $('#teacherCredentials').show();
            
            if ($(this).val() == '1') { // PNS
                $('#nipGroup').show();
                $('#emailGroup').hide();
                $('#nip').prop('required', true);
                $('#email').prop('required', false);
            } else { // Honorer
                $('#nipGroup').hide();
                $('#emailGroup').show();
                $('#nip').prop('required', false);
                $('#email').prop('required', true);
            }
        });
        
        // Handle checkbox validation for teachers
        requiredCheckboxes.on('change', function() {
            if (requiredCheckboxes.is(':checked')) {
                requiredCheckboxes.prop('required', false);
            } else {
                if ($('#level').val() == '2') { // Only for teachers
                    requiredCheckboxes.prop('required', true);
                }
            }
        });
        
        // Form validation
        $('#registerMainForm').on('submit', function(e) {
            var userType = $('#level').val();
            var isValid = true;
            var errorMessage = '';
            
            if (!userType) {
                isValid = false;
                errorMessage = 'Pilih jenis user terlebih dahulu.';
            } else if (userType == '2') { // Teacher validation
                var teacherType = $('input[name="tipeguru"]:checked').val();
                if (!teacherType) {
                    isValid = false;
                    errorMessage = 'Pilih jenis guru terlebih dahulu.';
                } else {
                    if (teacherType == '1' && !$('#nip').val()) {
                        isValid = false;
                        errorMessage = 'NIP harus diisi untuk guru PNS.';
                    } else if (teacherType == '2' && !$('#email').val()) {
                        isValid = false;
                        errorMessage = 'Email harus diisi untuk guru honorer.';
                    }
                }
                
                // Check if at least one class is selected
                if (!requiredCheckboxes.is(':checked')) {
                    isValid = false;
                    errorMessage = 'Pilih minimal satu kelas yang diampu.';
                }
            } else if (userType == '3') { // Student validation
                if (!$('#nis').val()) {
                    isValid = false;
                    errorMessage = 'NIS harus diisi.';
                }
                if (!$('#class_id').val()) {
                    isValid = false;
                    errorMessage = 'Pilih kelas terlebih dahulu.';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }
        });
        
        // Show/hide sections based on initial selection
        if ($('#level').val()) {
            $('#level').trigger('change');
        }
    });
    </script>
</body>
</html>