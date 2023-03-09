<?php

require_once dirname(__DIR__).'/models/Report.php';

class ReportController extends Report
{
    public function getMonthlyPatient($year){
        return $this->getMonthPati($year);
    }

    public function getDailyQuantity(){
        return $this->getDailyQty();
    }
    public function getMonthlyPatientIncome($year){
        return $this->getMonthIn($year);
    }

    public function getDailyMedicine($data){
        return $this->getMediRp($data);
    }
}
