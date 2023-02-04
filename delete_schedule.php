<?php 

require_once './controllers/ScheduleController.php';

$scheduleController = new ScheduleController();

if(isset($_POST["id"])){
    $result = $scheduleController->delete($_POST["id"]);

    if($result){
        echo "success";
    }
    else{
        echo "fail";
    }
}