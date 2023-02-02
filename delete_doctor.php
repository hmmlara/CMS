<?php

require_once './controllers/DoctorController.php';

if(isset($_POST["id"])){
    $doctorController = new DoctorController();

    $result = $doctorController->deleteDoctor($_POST["id"]);

    if($result){
        echo 'success';
    }
    else{
        echo "fail";
    }
}