<?php

require_once './controllers/PatientController.php';

if(isset($_POST["id"])){

    $patientController = new PatientController();

    $result = $patientController->delete($_POST["id"]);
    if($result){
        echo "success";
    }
    else{
        echo "fail";
    }
}