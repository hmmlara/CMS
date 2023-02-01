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

    public function getDetail($id){
        return $this->getDoctorDetail($id);
    }
}

?>