<?php 

require_once './controllers/ScheduleController.php';

$scheduleController = new ScheduleController();

if($_POST['id']){
    $result = $scheduleController->getSpecificSchedule($_POST["id"]);

    if(count($result) > 0){
        echo json_encode($result);   
    }
    else{
        echo 'fail';
    }
}