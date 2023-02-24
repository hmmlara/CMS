<?php

include_once "./layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor' || $auth->hasRole() == 'reception'){
    header('location:_403');
}

//after Auth
include_once "./controllers/DoctorController.php";
include_once "./core/libraray.php";
include_once "./core/Paginator.php";

$doctorController=new DoctorController();
$doctors=$doctorController->getDoctors();

$gender=["","Female","male"];
$specialities=["","General Medicine","Nephrologists","Paediatrician","Physical Medicine","Mental Health","Obsteric & Gynae"];

if(isset($_GET["id"])){
    $doctorInfos =$doctorController->getDetail($_GET['id']);
    $patients=$doctorController->getPatients($_GET['id']);
    unset($_SESSION['dr_id']);
    unset($_SESSION['doctorInfos']);
    unset($_SESSION['patients']);
    // var_dump($doctorInfos);

    // if(!$doctorInfos){
    //     echo "<script>
    //         window.location.href='http://localhost/PMS/_404.php'    
    //     </script>";
    // }
}

if(!isset($_SESSION['doctorInfos'])&& !isset($_SESSION['patients'])){
    $_SESSION['doctorInfos'] = $doctorInfos;
    $_SESSION['patients'] = $patients;
    $_SESSION['dr_id'] = $_GET['id'];
    // var_dump($patients);
}

// add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;
$per_page = 3;
$num_of_pages = ceil(count($_SESSION['patients']) / $per_page);
$pagi_patients = Pagination::paginator($pages, $_SESSION['patients'], $per_page);


// add id for show

for ($i = 1; $i <= count($pagi_patients); $i++) {
    $pagi_patients[$i - 1] += ["display_id" => $i];
}    

?>


<div class="container mt-3">

    <div class="row">
        <div class="col-md-12 mb-3">
            <h4>Doctor Info</h4>
            <a href="all_doctors.php" class="btn btn-success"><i class="fas fa-arrow-left"></i></a>
        </div>

        <div class="col-md-12">
            <div class="bg-white p-4">
                <div class="row">
                    <div class="col-3">
                        <div class="w-50 mx-auto">
                            <img src="uploads/<?php echo $_SESSION['doctorInfos']['img'] ?>" alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mt-4">
                            <small><?php echo $_SESSION['doctorInfos']["user_code"];?></small>
                            <h3>Doctor <?php echo $_SESSION['doctorInfos']['name'] ?></h3>

                        </div>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-white float-end" id="dropdownMenuLink" data-mdb-toggle="dropdown"
                            aria-expanded="false"><i class="fas fa-ellipsis-v fs-5"></i></button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="doctor_edit?id=<?php echo $_SESSION["dr_id"]; ?>">Edit</a></li>
                            <h6 id="del_id" hidden><?php echo $_SESSION['dr_id']; ?></h6>
                            <li><button class="dropdown-item" id="del_doc">Delete</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <!-- Tabs navs -->
            <ul class="nav nav-tabs nav-fill mb-3 mt-3 bg-light" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active fw-bold" id="ex2-tab-1" data-mdb-toggle="tab" href="#ex2-tabs-1"
                        role="tab" aria-controls="ex2-tabs-1" aria-selected="true">Treatment</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-bold" id="ex2-tab-2" data-mdb-toggle="tab" href="#ex2-tabs-2" role="tab"
                        aria-controls="ex2-tabs-2" aria-selected="false">Information</a>
                </li>
                <!-- <li class="nav-item" role="presentation">
                    <a class="nav-link fw-bold" id="ex2-tab-2" data-mdb-toggle="tab" href="#ex2-tabs-2" role="tab"
                        aria-controls="ex2-tabs-2" aria-selected="false">Treatment</a>
                </li> -->
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="ex2-content">
                <div class="tab-pane fade show active" id="ex2-tabs-1" role="tabpanel" aria-labelledby="ex2-tab-1">
                    <table class="table  table-active  mt-5 text-center text-dark">
                        <thead class="table-dark  ">
                            <th>No</th>
                            <th>Patient Code</th>
                            <th>Patient Name</th>
                            <th>Treatment Date</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($pagi_patients as $patient) {
                        ?>
                            <tr>
                                <td><?php echo $patient["display_id"]?></td>
                                <td><?php echo $patient["pr_code"];?></td>
                                <td><?php echo $patient["patient_name"]; ?></td>
                                <td><?php echo $patient["treatment_date"]; ?></td>
                            </tr>
                            <?php
                            }
                        ?>
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <?php 
                        // pagi page
                        $server_page = $_SERVER["PHP_SELF"];
                        $pre_page = ($server_page . '?pages=' . ($pages - 1));
                    ?>
                    <nav aria-label="Page navigation example mx-auto">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($pages == 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($pages == 2) ? 'doctor_info' : $pre_page; ?>"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <?php
                                $ellipse = false;
                                $ends = 1;
                                $middle = 2;
                
                                for ($page = 1; $page <= $num_of_pages; $page++) {
                            ?>
                            <?php
                                if($page == $pages){
                                    $ellipse = true;
                            ?>
                            <li class='page-item active'>
                                <a class='page-link'
                                    href='<?php echo ($page - 1 < 1) ? 'doctor_info' : $server_page . "?pages=" . $page; ?>'>
                                    <?php echo $page; ?>
                                </a>
                            </li>
                            <?php
                                }
                                else{
                            // condition for ... in pagination
                                if ($page <= $ends || ($pages && $page >= $pages - $middle && $page <= $pages + $middle) || $page > $num_of_pages - $ends) { 
                            ?>
                            <li class='page-item'>
                                <a class='page-link'
                                    href='<?php echo ($page - 1 < 1) ? 'doctor_info' : $server_page . "?pages=" . $page; ?>'>
                                    <?php echo $page; ?>
                                </a>
                            </li>
                            <?php
                                $ellipse = true;
                            }
                                elseif($ellipse){
                            ?>
                            <li class='page-item'>
                                <a class='page-link'>&hellip;</a>
                            </li>
                            <?php
                                $ellipse = false;
                                }
                            }
                            ?>
                            <?php
                            }
                            ?>
                            <li class="page-item <?php echo ($pages == $num_of_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo $server_page; ?>?pages=<?php echo $pages + 1; ?>"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- pagination -->
                </div>

                <div class="tab-pane fade overflow-auto" id="ex2-tabs-2" role="tabpanel" aria-labelledby="ex2-tab-2"
                    style="min-height: 300px;">
                    <div class="w-100 ">
                        <div class="card" style="height: 250px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class='mb-4'>Doctor
                                            Code:&nbsp;&nbsp;<span><?php echo $_SESSION['doctorInfos']['user_code'] ?></span></h5>
                                        <h5 class='mb-4'>Date of
                                            Birth:&nbsp;&nbsp;<span><?php echo $_SESSION['doctorInfos']['age'] ?></span>
                                        </h5>
                                        <h5 class='mb-4'>
                                            Education:&nbsp;&nbsp;<span><?php echo $_SESSION['doctorInfos']['education'] ?></span>
                                        </h5>
                                        <h5 class='mb-4'>
                                            Gender:&nbsp;&nbsp;<span><?php echo $gender[$_SESSION['doctorInfos']['gender']]?></span>
                                        </h5>
                                    </div>
                                    <div class="col-md-6">

                                        <h5 class='mb-4'>
                                            martial_status:&nbsp;&nbsp;<span><?php echo $_SESSION['doctorInfos']['martial_status'] ?></span>
                                        </h5>

                                        <h5 class='mb-4'>
                                            Nrc:&nbsp;&nbsp;<span><?php echo $_SESSION['doctorInfos']['nrc'] ?></span>
                                        </h5>

                                        <!-- <h5 class='mb-4'>
                                            created_at:&nbsp;&nbsp;<span><?php echo $_SESSION['doctorInfos']['created_at'] ?></span>
                                        </h5> -->

                                        <h5 class='mb-4'>
                                            Phone:&nbsp;&nbsp;<span><?php echo $_SESSION['doctorInfos']['phone'] ?></span>
                                        </h5>

                                        <h5 class='mb-4'>
                                            Speciality:&nbsp;&nbsp;<span><?php echo $specialities[$_SESSION['doctorInfos']['specialities']] ?></span>
                                        </h5>

                                        <h5 class='mb-4'>
                                            Updated_at:&nbsp;&nbsp;<span><?php echo $_SESSION['doctorInfos']['updated_at'] ?></span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs content -->
        </div>
    </div>

    <div class="row mt-3">


        <div class="col-md-9">
            <!-- <div class="card " style="height: 250px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class='mb-4'>Doctor
                                Code:&nbsp;&nbsp;<span><?php echo $doctorInfos['user_code'] ?></span></h5>
                            <h5 class='mb-4'>Age:&nbsp;&nbsp;<span><?php echo $doctorInfos['age'] ?></span></h5>
                            <h5 class='mb-4'>Education:&nbsp;&nbsp;<span><?php echo $doctorInfos['education'] ?></span>
                            </h5>
                            <h5 class='mb-4'>
                                Gender:&nbsp;&nbsp;<span><?php echo $gender[$doctorInfos['gender']]?></span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class='mb-4'>
                                martial_status:&nbsp;&nbsp;<span><?php echo $doctorInfos['martial_status'] ?></span>
                            </h5>
                            <h5 class='mb-4'>Nrc:&nbsp;&nbsp;<span><?php echo $doctorInfos['nrc'] ?></span></h5>
                            <h5 class='mb-4'>
                                created_at:&nbsp;&nbsp;<span><?php echo $doctorInfos['created_at'] ?></span></h5>
                            <h5 class='mb-4'>
                                Updated_at:&nbsp;&nbsp;<span><?php echo $doctorInfos['updated_at'] ?></span></h5>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

</div>

<?php
include_once "./layouts/footer.php";
?>