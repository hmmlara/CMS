<?php 

require_once './controllers/ReportController.php';


$reportController = new ReportController();
if(isset($_POST['year'])){
    $result = $reportController->getMonthlyPatientIncome($_POST['year']);

    if($result != null){
        echo json_encode($result);
    }
    else{
        echo json_encode('fail');
    }
}