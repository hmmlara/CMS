<?php

require_once dirname(__DIR__).'/models/Report.php';

class ReportController extends Report
{
    public function getMonthlyPatient($year){
        return $this->getMonthPati($year);
    }
}
