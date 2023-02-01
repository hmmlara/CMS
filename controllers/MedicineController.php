<?php

require_once __DIR__."/../models/Medicine.php";

class MedicineController extends Medicine
{
    //get all medicine
    public function getMedicine()
    {
        $medicine=$this->getAllMedicine();
        return $medicine;
    }
    public function getNameAndId(){
        return $this->getMedicineName();
    }

    //get add medicine
    public function addMedicine($data)
    {
        $addmedicine=$this->getAddMedicine($data);
        return $addmedicine;
    }

    // add medicine stock
    public function addStock($data){
        return $this->addMediStock($data);
    }
}

?>