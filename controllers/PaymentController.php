<?php

require_once dirname(__DIR__).'/models/Payment.php';

class PaymentController extends Payment
{
    public function getAll(){
        return $this->getPayments();
    }
    public function create($data){
        return $this->createPayment($data);
    }
}
