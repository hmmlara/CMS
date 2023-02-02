<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/mdb.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

  <!-- navbar start -->
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
        <a class="navbar-brand text-dark" href="index.php">CMS</a>
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-list text-white"></i>
            </button>
            <div class="collapse navbar-collapse bg-light" id="navbarNav">
                <ul class="navbar-nav">
                    <?php 
                        // for active ui
                        $page = explode('.php',$_SERVER["PHP_SELF"])[0];
                    ?>
                    <li class="nav-item <?php echo (strpos($page,'index') !== false )? 'active' : '';?>">
                        <a class="nav-link mx-2" aria-current="page" href="index">Dashboard</a>
                    </li>

                        <li class="nav-item <?php echo (strpos($page,'patient') !== false )? 'active' : ''; ?>">
                        <a class="nav-link mx-2" href="all_patients">Patients</a>
                    </li>
                    
                    <li class="nav-item <?php echo (strpos($page,'doctor') !== false )? 'active' : '';?>">
                        <a class="nav-link mx-2" href="all_doctors">Doctors</a>
                    </li>

                    <li class="nav-item <?php echo (strpos($page,'reception') !== false )? 'active' : '';?>">
                        <a class="nav-link mx-2" href="">Receptionists</a>
                    </li>
                    <li class="nav-item <?php echo (strpos($page,'medi') !== false )? 'active' : '';?>">
                        <a class="nav-link mx-2" href="medicine">Medicines</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link mx-2" href="#">Schedules</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link mx-2" href="#">Appointments</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
