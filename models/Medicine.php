<?php

require_once __DIR__."/../core/Database.php";

class Medicine{
    private $pdo;

    public function getMedicineName(){
        $this->pdo=Database::connect();

        $sql="select id,name from medicines";

        $statement=$this->pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllMedicine()
    {
        $this->pdo=Database::connect();

        $sql="select *,medi_category.category_name,medi_type.type from medicines join medi_category on medicines.category_id = medi_category.id
            join medi_type on medicines.type_id = medi_type.id";

        $statement=$this->pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAddMedicine($data)
    {
        $this->pdo=Database::connect();

        $sql="INSERT INTO `medicines`(`category_id`, `name`, `type_id`, `description`, `company`, `brand`, `created_at`, `updated_at`) 
        VALUES  ( :category_id, :name, :type_id, :description, :company, :brand,:created_at,:updated_at)";

        $statement=$this->pdo->prepare($sql);

        //binding data value with query using foreach
        foreach ($data as $key => $value)
        {
            $statement->bindParam(":$key",$data[$key]);
        }

        //for created_at and updated_at
        $date_now = date('Y-m-d');
        $statement->bindParam(":created_at",$date_now);
        $statement->bindParam(":updated_at",$date_now);
        
        // var_dump($statement);

        // check for medicine warehouse
        if($statement->execute()){

            $getNewMedicine = $this->getMedicineName();

            // get latest array value
            $getMedi_id = $getNewMedicine[count($getNewMedicine) - 1]["id"];
            
            // return true medi warehose success

            return $this->createWarehouse($getMedi_id);
        }

        return false;
    }
    public function addMediStock($data)
    {
        $this->pdo=Database::connect();

        $sql="INSERT INTO `medi_stocks`(`medicine_id`, `qty`, `price`, `man_date`, `exp_date`, `created_at`, `updated_at`) VALUES  
        ( :medicine_id, :qty, :price, :man_date, :exp_date,:created_at,:updated_at)";

        $statement=$this->pdo->prepare($sql);

        //binding data value with query using foreach
        foreach ($data as $key => $value)
        {
            $statement->bindParam(":$key",$data[$key]);
        }

        //for created_at and updated_at
        $date_now = date('Y-m-d');
        $statement->bindParam(":created_at",$date_now);
        $statement->bindParam(":updated_at",$date_now);
        
        // var_dump($statement);
        if($statement->execute()){

            // id , qty from medi_stocks
            $result = $this->getMediStock();
            
            // update medi_warehouses total qty where medicine_id == ??
            return $this->updateMediWarehouseStock($result);
        }

        return false;
    }

    // create warehouse stock
    private function createWarehouse($medicine_id){
        
        $this->pdo = Database::connect();

        $sql = "insert into medi_warehouses (medicine_id,total_qty,created_at,updated_at) 
                values (:medicine_id,0,:created_at,:updated_at)";
        
        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":medicine_id",$medicine_id);
        $statement->bindParam(":created_at",date('Y-m-d'));
        $statement->bindParam(":updated_at",date('Y-m-d'));

        return $statement->execute();
    }
    

    // get medicine_id,qty of new added stock from medi_stocks table
    private function getMediStock(){

        $this->pdo = Database::connect();

        $sql = "select medicine_id,qty from medi_stocks";

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) > 0){
            
            // return latest array value
            return $result[count($result) - 1];
        }

        return false;

    }
    // get total_qty from medi_warehouses where medicine_id = ?? 
    private function getWarehouseQty($medicine_id){

        $this->pdo = Database::connect();

        $sql = "select total_qty from medi_warehouses where medicine_id = :medicine_id";
        
        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":medicine_id",$medicine_id);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result["total_qty"];
    }
     // update medi warehouse
     private function updateMediWarehouseStock($data){

        // get total_qty from medi_warehouses
        /*
         data prepare
         */
        $total_qty = $this->getWarehouseQty($data["medicine_id"]);

        $total_qty += $data["qty"];

        // start query
        $this->pdo = Database::connect();

        $sql = "update medi_warehouses set total_qty = :total_qty where medicine_id = :medicine_id";

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":total_qty",$total_qty);
        $statement->bindParam(":medicine_id",$data["medicine_id"]);

        return $statement->execute();
     }
}
?>