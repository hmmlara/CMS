<?php

require_once './controllers/ReceptionController.php';

$repController = new ReceptionController();
if(isset($_POST["id"])){
    $result = $repController->getDetail($_POST["id"]);
    if($result){
        echo json_encode($result);
    }
    else{
        echo "fail";
    }
}