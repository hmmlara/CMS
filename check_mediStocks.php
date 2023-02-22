<?php 

require_once './controllers/MedicineController.php';

$medicineController = new MedicineController();

if(isset($_POST['medicine_id'])){
    $result = $medicineController->getMediWarehouseId($_POST["medicine_id"]);

    if(!empty($result)){
        echo json_encode($result);
    }
    else{
        echo 'fail';
    }
}