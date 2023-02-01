<?php

include_once "./layouts/header.php";

require_once "./controllers/PatientController.php";
require_once "./core/libraray.php";

$patientController = new PatientController();

$patients = $patientController->getPatients();

// add id for show
for ($i = 1; $i <= count($patients); $i++) {
    $patients[$i - 1] += ["display_id" => $i];
}

if(isset($_POST["search"])){
    $patients = search_data($patients,$_POST["search_val"]);
}

?>

<!-- modal -->
<div class="container mt-3">
    <div class="row">
        <div class="col-8">
            <h5 class="mb-4">Patients</h5>
            <a href="add_patient" class="btn btn-sm btn-dark" id="add">Add</a>
        </div>
        <div class="col-4 mb-3">
            <form action="" method="post">
                <div class="form-group d-flex float-right mb-3">
                    <span class="mt-2">Search:&nbsp;</span>
                    <input type="text" name="search_val" id="" class="form-control w-50 mx-3" placeholder="Enter Patient Code">
                    <button type="submit" name="search" class="btn btn-sm btn-dark">Search</button>
                </div>
            </form>

            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <button class="btn btn-light btn-sm mx-2" id="grid1"><i class="fas fa-th fs-6"></i></button>
                    <button class="btn btn-light btn-sm" id="grid2"><i class="fas fa-list fs-6"></i></button>
                </div>
            </div>
        </div>

        <hr class="hr-blurry">

        <div class="row w-100 overflow-auto" style="max-height: 400px;">
            <?php 
            foreach($patients as $patient){
        ?>

            <div class="<?php  echo (isset($_COOKIE["class"]))? $_COOKIE["class"] : 'col-3 mb-4'; ?>" id="cms_card">
                <a href="patient_info.php?id=<?php echo $patient["id"]; ?>" class="text-dark">
                    <div class="card shadow-3">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col-3">
                                    <div class="fs-6 text-center">
                                        <i class="fas fa-user bg-dark p-3 rounded-circle text-white"></i>
                                    </div>
                                </div>
                                <div class="col-9 px-3">
                                    <small><?php echo $patient["pr_code"]; ?></small>
                                    <h6><?php echo $patient["name"]; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <?php
            }
        ?>
        </div>
    </div>
</div>


<?php
include_once "./layouts/footer.php";
?>