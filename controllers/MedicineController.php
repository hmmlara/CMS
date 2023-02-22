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

    public function getStockMedicine(){
        return $this->getWarehouseStock();
    }

    public function getStockHistory($medicine_id){
        return $this->getMediStkHis($medicine_id);
    }

    public function getById($id)
    {
        return $this->getEditStock($id);
    }

    public function editMedicineStock($data)
    {
        $result=$this->updateMedicineStock($data);
        return $result;
    }

    // get medicine details
    public function getDetails($id){
        return $this->getMedicineDetails($id);
    }

    public function getMediWarehouseId($medicine_id){
        return $this->getMediStockWithId($medicine_id);
    }
}

?>