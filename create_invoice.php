<?php

require_once './controllers/PaymentController.php';

$paymentController = new PaymentController();

if(isset($_POST['data'])){
    $result = $paymentController->create($_POST["data"]);

    if($result){
        echo 'success';
    }
    else{
        echo 'fail';
    }
}