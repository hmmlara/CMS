<?php

include_once './layouts/header.php';
require_once './controllers/PatientController.php';
require_once './controllers/DoctorController.php';
require_once './controllers/MedicineController.php';

$patientController = new PatientController();
$total_patients = count($patientController->getPatients());

$doctorController = new DoctorController();
$total_doctors = count($doctorController->getDoctors());

$medicineController = new MedicineController();
$total_medicines = count($medicineController->getMedicine());

if(!$auth->isAuth()){
    header('location:login_form.php');
}

if($auth->hasRole() == 'doctor'){
    header('location:patient.php');
}
?>

<div class="container-fluid mt-2 p-4">
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="all_patients">
                <div class="card bg-danger text-white shadow-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="fs-6 text-center">
                                    Patients
                                </div>
                                <div class="fs-6 font-weight-bold text-center"><?php echo $total_patients;?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white shadow-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="fs-6 text-center">
                                Medicines
                            </div>
                            <div class="fs-6 font-weight-bold text-center"><?php echo $total_medicines; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-capsules fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white shadow-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="fs-6 text-center">
                                Doctors
                            </div>
                            <div class="fs-6 font-weight-bold text-center"><?php echo $total_doctors; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white shadow-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="fs-6 text-center">
                                Appointments
                            </div>
                            <div class="fs-6 font-weight-bold text-center">100</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once './layouts/footer.php';
?>