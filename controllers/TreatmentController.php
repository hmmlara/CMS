<?php

require_once dirname(__DIR__) . '/models/Treatment.php';
require_once dirname(__DIR__) . '/models/Appointment.php';
require_once dirname(__DIR__) . '/models/Patient.php';

class TreatmentController extends Treatment
{
    
    public function getAll(){
        return $this->getAllTreatments();
    }
    public function getTreatmentDetails($treatment_id){
        return $this->getDetails($treatment_id);
    }
    // get treatment his
    public function getTreatments($pr_id)
    {
        $patient = new Patient();
        return $patient->getPatientTreat($pr_id);
    }
    public function get($treatment_id)
    {
        return $this->getTreatment($treatment_id);
    }
    public function create($data)
    {
        if ($this->complete($data['appoint_id'])) {
            return $this->createTreatment($data);
        }
    }

    private function complete($appointment_id)
    {
        $appoint = new Appointment();
        return $appoint->updateStatus($appointment_id, 3);
    }
}
