<?php

require_once __DIR__.'/../models/MediType.php';

class MediTypeController extends MediType
{
    //get all medicine 
    public function getMedicineType()
    {
        $result=$this->getMediType();
        return $result;
    }
    public function add($data)
    {
        $result=$this->addMediType($data);
        return $result;
    }

    public function getById($id)
    {
        return $this->getEditMeditype($id);
    }
    
    public function editMedicineType($data)
    {
        $result=$this->updateMeditype($data);
        return $result;
    }
}
 
?>