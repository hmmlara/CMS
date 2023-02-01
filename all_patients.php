<?php

include_once "./layouts/header.php";

require_once "./controllers/PatientController.php";

$patientController = new PatientController();

$patients = $patientController->getPatients();

// add id for show
for ($i = 1; $i <= count($patients); $i++) {
    $patients[$i - 1] += ["display_id" => $i];
}

?>

<!-- modal -->
<div class="container mt-3">
    <div class="row">
        <div class="col-6">
            <h5 class="mb-4">Patients</h5>
            <a href="add_patient" class="btn btn-sm btn-success" id="add">Add</a>
        </div>
        <div class="col-6">
            <div class="form-group d-flex float-right mb-3">
                <span class="mt-2">Search:&nbsp;</span>
                <input type="text" name="" id="" class="form-control w-75" placeholder="Enter Patient Code">
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="dropdown float-end">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenu2"
                            data-mdb-toggle="dropdown" aria-expanded="false">
                            filter
                            <i class="fas fa-filter"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                </div>
                <div class="col-6">
                    <button class="btn btn-light btn-sm" id="grid1"><i class="fas fa-th fs-6"></i></button>
                    <button class="btn btn-light btn-sm" id="grid2"><i class="fas fa-list fs-6"></i></button>
                </div>
            </div>
        </div>
    </div>

    <hr class="hr-blurry">

    <div class="row w-100">
        <?php 
            foreach($patients as $patient){
        ?>

        <div class="<?php  echo $_COOKIE["class"]; ?>" id="cms_card">
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
                                <h5><?php echo $patient["name"]; ?></h5>
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
    <!-- <div class="container" id="content-area">
        <table class="table text-center">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Patient Code</th>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Age</th>
                    <th scope="col">Funciton</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div> -->
</div>


<?php
include_once "./layouts/footer.php";
?>