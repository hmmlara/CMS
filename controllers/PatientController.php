<?php

require_once dirname(__DIR__)."/models/Patient.php";

class PatientController extends Patient
{

    // get all patients data
    public function getPatients(){
        return $this->getAllPatients();
    }

    // get patient by id
    public function getById($id){
        return $this->getPatientById($id);
    }
    // get patient code
    public function getCode(){
        return $this->getPatientCode();
    }

    // save patient
    public function save($data){
        return $this->savePatient($data);
    }

    // update patient
    public function update($data){
        return $this->updatePatient($data);
    }

    // delete patient
    public function delete($id){
        try{
            return $this->deletePatient($id);
        }
        catch(PDOException $e){
            return false;
        }
    }
}
