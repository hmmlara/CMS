<?php

include_once "./layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor'){
    header('location:_403');
}

require_once "./controllers/PatientController.php";

$patientController = new PatientController();

// gender array
$gender = ["---", "Female", "Male"];

if (isset($_GET["id"])) {
    $patient_infos = $patientController->getById($_GET["id"]);
}

?>



<div class="container-fluid mt-3">
    <h5>Patient Information</h5>
    <a href="all_patients" class="btn btn-sm btn-success"><i class="fas fa-arrow-left"></i></a>
    <div class="row mt-3">
        <div class="col-4">
            <div class="card py-2">
                <div class="card-title text-center">
                    <i class="fas fa-user fs-2 bg-dark text-white p-3 rounded-circle"></i>
                    <h3 class="mt-2"><?php echo $patient_infos["name"]; ?></h3>
                </div>
                <div class="card-body">
                    <div class="row w-100">
                        <div class="col-6">
                            <h6>Code:&nbsp;<span><?php echo $patient_infos["pr_code"]; ?></span></h6>
                            <h6>Phone:&nbsp;<span><?php echo $patient_infos["phone"]; ?></span></h6>
                            <h6>Age:&nbsp;<span><?php echo $patient_infos["age"]; ?></span></h6>
                            <h6>Weight:&nbsp;<span><?php echo $patient_infos["weight"]; ?></span></h6>
                        </div>

                        <div class="col-6">
                            <h6>Height:&nbsp;<span><?php echo $patient_infos["height"]; ?></span></h6>
                            <h6>Gender:&nbsp;<span><?php echo $gender[$patient_infos["gender"]]; ?></span></h6>
                            <h6>Blood Type:&nbsp;<span><?php echo $patient_infos["blood_type"]; ?></span></h6>
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
                        <th scope="col">Doctor Code</th>
                        <th scope="col">Treatment Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<?php
include_once "./layouts/footer.php";
?>