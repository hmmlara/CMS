<?php

require_once dirname(__DIR__).'/models/Payment.php';

class PaymentController extends Payment
{
    public function getUncheckTreat(){
        return $this->getUncheck();
    }
    public function getAll(){
        return $this->getPayments();
    }
    public function create($data){
        return $this->createPayment($data);
    }

    public function getService($user_id){
        return $this->getServiceId($user_id);
    }
}
