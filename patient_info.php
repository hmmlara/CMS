<?php

include_once "./layouts/header.php";
require_once './core/Paginator.php';

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor'){
    header('location:_403');
}

require_once "./controllers/PatientController.php";
require_once './controllers/TreatmentController.php';

$patientController = new PatientController();
$treatmentController = new TreatmentController();
// gender array
$gender = ["---", "Female", "Male"];

if (isset($_GET["id"])) {
    $patient_infos = $patientController->getById($_GET["id"]);
    $treatments = $treatmentController->getTreatments($_GET["id"]);
    unset($_SESSION['patient_infos']);
    unset($_SESSION['treatments']);
    // unset($_SESSION['pr_id']);
}

if(!isset($_SESSION['patient_infos']) && !isset($_SESSION['treatments'])){
    $_SESSION['patient_infos'] = $patient_infos;
    $_SESSION['treatments'] = $treatments;
}

if(count($_SESSION['treatments']) > 0){
    for ($i = 1; $i <= count($_SESSION['treatments']); $i++) {
        $_SESSION['treatments'][$i - 1] += ["display_id" => $i];
    }
}

// add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;

$per_page = 5;
$num_of_pages = ceil(count($_SESSION['treatments']) / $per_page);
$pagi_treatment = Pagination::paginator($pages, $_SESSION['treatments'], $per_page);
?>



<div class="container-fluid mt-3">
    <h5>Patient Information</h5>
    <a href="all_patients" class="btn btn-sm btn-success"><i class="fas fa-arrow-left"></i></a>
    <div class="row mt-3">
        <div class="col-4">
            <div class="card py-2">
                <div class="card-title text-center">
                    <i class="fas fa-user fs-2 bg-dark text-white p-3 rounded-circle"></i>
                    <h3 class="mt-2"><?php echo $_SESSION['patient_infos']["name"]; ?></h3>
                </div>
                <div class="card-body">
                    <div class="row w-100">
                        <div class="col-6">
                            <h6>Code:&nbsp;<span><?php echo $_SESSION['patient_infos']["pr_code"]; ?></span></h6>
                            <h6>Phone:&nbsp;<span><?php echo $_SESSION['patient_infos']["phone"]; ?></span></h6>
                            <h6>Age:&nbsp;<span><?php echo $_SESSION['patient_infos']["age"]; ?></span></h6>
                            <h6>Weight:&nbsp;<span><?php echo $_SESSION['patient_infos']["weight"]; ?></span></h6>
                        </div>

                        <div class="col-6">
                            <h6>Height:&nbsp;<span><?php echo $_SESSION['patient_infos']["height"]; ?></span></h6>
                            <h6>Gender:&nbsp;<span><?php echo $gender[$_SESSION['patient_infos']["gender"]]; ?></span></h6>
                            <h6>Blood Type:&nbsp;<span><?php echo $_SESSION['patient_infos']["blood_type"]; ?></span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <table class="table table-collapse text-center">
                <thead class="bg-dark text-white">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Doctor Name</th>
                        <th scope="col">Treatment Date</th>
                        <th>Function</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($pagi_treatment as $treatment){
                    ?>
                    <tr>
                        <td><?php echo $treatment['display_id'];?></td>
                        <td><?php echo $treatment['name'];?></td>
                        <td><?php echo $treatment['treatment_date'];?></td>
                        <td>
                            <a href="app_patient_treatment_details?treatment_id=<?php echo $treatment['id'];?>" class="btn btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                        </td>
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
                <a class="page-link"
                    href="<?php echo ($pages == 2) ? 'patient_info' : $pre_page; ?>"
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
                                    href='<?php echo ($page - 1 < 1) ? 'patient_info' : $server_page . "?pages=" . $page; ?>'>
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
                                        href='<?php echo ($page - 1 < 1) ? 'patient_info' : $server_page . "?pages=" . $page; ?>'>
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
    </div>
</div>


<?php
include_once "./layouts/footer.php";
?>