<?php

include_once __DIR__."/../models/Reception.php";


class ReceptionController extends Reception{

     // get doctor code start with dr-
     public function getCode(){
        return $this->getReceptionCode();
    }
    
    //add Receptionist
     public function add($data){
        $result=$this->addUser($data);
        return $result;
     }

     //get all receptionists
    public function getReceptionists(){
        $receptions=$this->getReceptionLists();
        return $receptions;
    }

    //get Reception Details
    public function getDetail($id){
        $detail=$this->getReceptionDetail($id);
        return $detail;
    }

    //update Reception
    public function update($data){
        $update=$this->updateReception($data);
        return $update;
    }

    //delete Reception
    public function delete($id){
       try{
        $delete=$this->deleteReception($id);
        return $delete;
       }
       catch(PDOException $e){
        return false;
       }
    }

    
}

?>