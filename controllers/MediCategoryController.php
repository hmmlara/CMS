<?php
require_once __DIR__.'/../models/MediCategory.php';

class MediCategoryController extends MedicineCategory
{
    public function getMediCategory()
    {
        $result=$this->getMedicineCategory();
        return $result;
    }
    
    public function addCategory($data)
    {
        $result=$this->getAddCategory($data);
        return $result;
    }
    
    public function delete($id){
        try{
            return $this->deleteCategory($id);
        }
        catch(PDOException $e){
            return false;
        }
    }
}
?>