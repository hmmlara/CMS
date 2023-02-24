<?php

include_once __DIR__."/../models/Doctor.php";

class DoctorController extends Doctor{
    //get all doctor
    public function getDoctors(){
        $doctors=$this->getDoctorLists();
        return $doctors;
    }

     // get doctor code start with dr-
     public function getCode(){
        return $this->getDoctorCode();
    }

    //add doctor
    public function add($data){
        $result=$this->addUser($data);
        return $result;

    }

    //Doctor Detail
    public function getDetail($id){
        return $this->getDoctorDetail($id);
    }

    //update DoctorInfo
    public function update($data){
        return $this->updateDoctor($data);
    }

    //delete Doctor
    public function deleteDoctor($id){
        try{
            $result=$this->deleteDoc($id);
            return $result;
        }
        catch(PDOException $e){
            return false;
        }
    }

    //get Patient and treatment
    public function getPatients($id)
    {
        return $this->getPatientInfo($id);
         
    }
}

?>