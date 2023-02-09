<?php

require_once './controllers/MediTypeController.php';

if(isset($_POST["id"])){
    $meditypeController = new MediTypeController();

    $result = $meditypeController->delete($_POST["id"]);

    if($result){
        echo "success";
    }
    else{
        echo "fail";
    }
}