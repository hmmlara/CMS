<?php

ob_start();
session_start();

require_once './controllers/auth/AuthController.php';

$auth = new AuthController();

if (isset($_POST['logout'])) {
    $auth->logout();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Clinic Management System</title>

    <link rel="stylesheet" href="css/mdb.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/print.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- for script -->
    <script src="js/mdb.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src='js/index.global.js'></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/chart.umd.min.js"></script>
</head>

<body onload="noti()">
    <?php
        // for active ui
        $page = explode('.php', $_SERVER["PHP_SELF"])[0];
    ?>
    <!-- navbar start -->
    <nav class="navbar navbar-expand-lg bg-light <?php echo (strpos($page, 'login') !== false) ? 'd-none' : ''; ?>">
        <div class="container-fluid">
            <a class="navbar-brand text-dark" href="index.php">CMS</a>
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-list text-white"></i>
            </button>
            <div class="collapse navbar-collapse bg-light" id="navbarNav">
                <ul class="navbar-nav">
                    <?php
                        if ($auth->hasRole() == 'admin') {
                            ?>
                    <li class="nav-item <?php echo (strpos($page, 'index') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" aria-current="page" href="index">Dashboard</a>
                    </li>
                    <li class="nav-item <?php echo (strpos($page, 'patient') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="all_patients">Patients</a>
                    </li>

                    <li class="nav-item <?php echo (strpos($page, 'doctor') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="all_doctors">Doctors</a>
                    </li>

                    <li class="nav-item <?php echo (strpos($page, 'reception') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="all_receptionists">Receptionists</a>
                    </li>
                    <li class="nav-item <?php echo (strpos($page, 'medi') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="medicine">Medicines</a>
                    </li>

                    <li class="nav-item <?php echo (strpos($page, 'sche') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="schedule">Schedules</a>
                    </li>

                    <li class="nav-item <?php echo (strpos($page, 'appoint') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="add_appointment">Appointments</a>
                    </li>
                    
                    <li class="nav-item <?php echo (strpos($page, 'invoice') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="invoices">Invoice</a>
                    </li>
                    
                    <li class="nav-item <?php echo (strpos($page, 'report') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="reports">Reports</a>
                    </li>
                    <?php
                    }
                    ?>

                    <!-- doctor -->
                    <?php
                        if ($auth->hasRole() == 'doctor') {
                    ?>
                    <li class="nav-item <?php echo (strpos($page, 'appoint') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="add_appointment">Appointments</a>
                    </li>
                    <li class="nav-item <?php echo (strpos($page, 'schedule') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="schedule">Schedules</a>
                    </li>
                    <?php
                        }
                    ?>

                    <!-- reception -->
                    <?php 
                        if($auth->hasRole() == 'reception')
                        {
                    ?>
                    <li class="nav-item <?php echo (strpos($page, 'appoint') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="add_appointment">Appointments</a>
                    </li>
                    <li class="nav-item <?php echo (strpos($page, 'patient') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="all_patients">Patients</a>
                    </li>
                    <li class="nav-item <?php echo (strpos($page, 'schedule') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="schedule">Schedules</a>
                    </li>
                    <li class="nav-item <?php echo (strpos($page, 'invoice') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="invoices">Invoice</a>
                    </li>
                    <?php 
                        }
                    ?>
                    <!-- reception -->
                </ul>
            </div>
            <!-- Right elements -->
            <div class="d-flex align-items-center">
                <!-- Avatar -->
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center hidden-arrow text-dark" href="#"
                        id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                        <small><?php echo $auth->getName(); ?></small>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                        <li>
                            <form action="" method="post">
                                <button type="submit" class="dropdown-item" name="logout">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Right elements -->
        </div>
        </div>
    </nav>