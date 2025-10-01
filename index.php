<?php

session_start();
include('config/db.php');

if (!isset($_SESSION['name'])) {
    header('location: sign-in.php');
    exit();
} else {
    if ($_SESSION['level_user'] == 1) {
        header('location: admin/index.php');
        exit();
    } else if ($_SESSION['level_user'] == 2) {
        header('location: guru/index.php');
        exit();
    } else {
        header('location: student/index.php');
        exit();
    }
}

if ($_SESSION['level_user'] == 3) {
    $survey = mysqli_query($conn, "SELECT * FROM survey_result where student_id = '{$_SESSION['student_id']}'");
    $survey_row = mysqli_num_rows($survey);
    if ($survey_row == 1) {
        $_SESSION['survey_taken'] = true;
        $level = mysqli_fetch_array($survey, MYSQLI_ASSOC);
        $_SESSION['level'] = $level['level_result'];
        $_SESSION['levels'] = $survey;
    } else {
        $_SESSION['survey_taken'] = false;
    }
}


?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>:: e-Learn:: Education Dashboard </title>
    <link rel="icon" href="favicon.ico" type="image/x-icon"> <!-- Favicon-->
    <!-- plugin css file  -->
    <link rel="stylesheet" href="node_modules/owl.carousel2/dist/assets/owl.carousel.min.css" />
    <!-- project css file  -->
    <link rel="stylesheet" href="assets/css/e-learn.style.min.css">
</head>

<body>

    <div id="elearn-layout" class="theme-purple">
        <!-- sidebar -->
        <?php include('layout/sidebar.php'); ?>

        <!-- main body area -->
        <div class="main px-lg-4 px-md-4">

            <!-- Body: Header -->
            <?php include('layout/header.php'); ?>

            <!-- Body: Body -->
            <div class="body d-flex py-lg-3 py-md-2">
                <div class="container-xxl">
                    <div class="row clearfix g-3">
                        <div class="col-lg-8 col-md-12 flex-column">
                            <div class="row row-deck g-3">
                                <div class="col-12 col-xl-12 col-lg-12">
                                    <div class="card mb-3 color-bg-200">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-lg-5 order-lg-2">
                                                    <div class="text-center p-4">
                                                        <img src="assets/images/study.svg" alt="..."
                                                            class="img-fluid set-md-img">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-7 order-lg-1">
                                                    <h3 class=" mb-3">Welcome back, <span class="fw-bold">
                                                            <?php echo $_SESSION['name']; ?>
                                                        </span>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($_SESSION['level_user'] == 3) { ?>


                            <div class="card mb-3 color-bg-200">
                                <div class="card-header py-3">
                                    <h6 class="mb-0 fw-bold ">Hasil Survey</h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($_SESSION['survey_taken']) { ?>
                                    <h6 class="mb-0 fw-bold">
                                        Level materi anda adalah Level
                                        <strong><?php echo $_SESSION['level'] ?></strong>
                                    </h6>
                                    <?php  } else { ?>
                                    <h6 class="mb-2 fw-bold">Anda belum mengambil Survey Level Belajar</h6>
                                    <p>Silahkan ambil survey dengan klik tombol <a href="survey.php"
                                            class="btn btn-primary ">Survey</a></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div><!-- Row End -->
                </div>
            </div>

            <!-- Modal Members-->
            <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title  fw-bold" id="addUserLabel">Invite Friend's</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="inviteby_email">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Members Invite"
                                        aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <button class="btn btn-dark" type="button" id="button-addon2">Members
                                        Invite</button>
                                </div>
                            </div>
                            <div class="members_list">
                                <h6 class="fw-bold ">Members of e-Learn</h6>
                                <ul class="list-unstyled list-group list-group-custom list-group-flush mb-0">
                                    <li class="list-group-item py-3 text-center text-md-start">
                                        <div class="d-flex align-items-center flex-column flex-sm-column flex-md-row">
                                            <div class="no-thumbnail mb-2 mb-md-0">
                                                <img class="avatar lg rounded-circle" src="assets/images/xs/avatar2.jpg"
                                                    alt="">
                                            </div>
                                            <div class="flex-fill ms-3 text-truncate">
                                                <h6 class="mb-0  fw-bold">Rachel Carr(you)</h6>
                                                <span class="text-muted">rachel.carr@gmail.com</span>
                                            </div>
                                            <div class="members-action">
                                                <span class="members-role ">Admin</span>
                                                <div class="btn-group">
                                                    <button type="button" class="btn bg-transparent dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="icofont-ui-settings  fs-6"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#"><i
                                                                    class="icofont-ui-password fs-6 me-2"></i>ResetPassword</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#"><i
                                                                    class="icofont-chart-line fs-6 me-2"></i>ActivityReport</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item py-3 text-center text-md-start">
                                        <div class="d-flex align-items-center flex-column flex-sm-column flex-md-row">
                                            <div class="no-thumbnail mb-2 mb-md-0">
                                                <img class="avatar lg rounded-circle" src="assets/images/xs/avatar3.jpg"
                                                    alt="">
                                            </div>
                                            <div class="flex-fill ms-3 text-truncate">
                                                <h6 class="mb-0  fw-bold">Lucas Baker<a href="#"
                                                        class="link-secondary ms-2">(Resend invitation)</a></h6>
                                                <span class="text-muted">lucas.baker@gmail.com</span>
                                            </div>
                                            <div class="members-action">
                                                <div class="btn-group">
                                                    <button type="button" class="btn bg-transparent dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Members
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="icofont-check-circled"></i>
                                                                Member
                                                                <span>Can view, edit, delete, comment on and save
                                                                    files</span>
                                                            </a>

                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fs-6 p-2 me-1"></i>
                                                                Admin
                                                                <span>Member, but can invite and manage team
                                                                    members</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn bg-transparent dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="icofont-ui-settings  fs-6"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#"><i
                                                                    class="icofont-delete-alt fs-6 me-2"></i>Delete
                                                                Member</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item py-3 text-center text-md-start">
                                        <div class="d-flex align-items-center flex-column flex-sm-column flex-md-row">
                                            <div class="no-thumbnail mb-2 mb-md-0">
                                                <img class="avatar lg rounded-circle" src="assets/images/xs/avatar8.jpg"
                                                    alt="">
                                            </div>
                                            <div class="flex-fill ms-3 text-truncate">
                                                <h6 class="mb-0  fw-bold">Una Coleman</h6>
                                                <span class="text-muted">una.coleman@gmail.com</span>
                                            </div>
                                            <div class="members-action">
                                                <div class="btn-group">
                                                    <button type="button" class="btn bg-transparent dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Members
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="icofont-check-circled"></i>
                                                                Member
                                                                <span>Can view, edit, delete, comment on and save
                                                                    files</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fs-6 p-2 me-1"></i>
                                                                Admin
                                                                <span>Member, but can invite and manage team
                                                                    members</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="btn-group">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn bg-transparent dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="icofont-ui-settings  fs-6"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="icofont-ui-password fs-6 me-2"></i>ResetPassword</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="icofont-chart-line fs-6 me-2"></i>ActivityReport</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="icofont-delete-alt fs-6 me-2"></i>Suspend
                                                                    member</a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="icofont-not-allowed fs-6 me-2"></i>Delete
                                                                    Member</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="assets/bundles/libscripts.bundle.js"></script>

    <!-- Plugin Js-->
    <script src="node_modules/owl.carousel2/dist/owl.carousel.min.js"></script>
    <script src="assets/bundles/apexcharts.bundle.js"></script>

    <!-- Jquery Page Js -->
    <script src="js/template.js"></script>
    <script src="js/page/elearn-index.js"></script>
</body>

</html>