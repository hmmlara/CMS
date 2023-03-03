<?php

require_once './controllers/ReportController.php';

$reportController = new ReportController();

if(isset($_POST['year'])){
    $result = $reportController->getMonthlyPatient($_POST['year']);

    if(count($result) > 0){
        echo json_encode($result);
    }
    else{
        echo json_encode('fail');
    }
}