<?php

require_once dirname(__DIR__) . '/models/Treatment.php';
require_once dirname(__DIR__) . '/models/Appointment.php';

class TreatmentController extends Treatment
{

    public function get(){
        return $this->getTreatment();
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
