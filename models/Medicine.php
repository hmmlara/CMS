<?php

require_once __DIR__ . "/../core/Database.php";
require_once dirname(__DIR__) . '/core/libraray.php';
require_once 'Treatment.php';

class Medicine
{
    private $pdo;

    protected function getMediStockWithId($medicine_id){
        $this->pdo = Database::connect();

        $sql = 'select total_qty from medi_warehouses where medicine_id = :medicine_id';

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":medicine_id",$medicine_id);

        if($statement->execute()){
            return $statement->fetch(PDO::FETCH_ASSOC)['total_qty'];
        }
    }
    public function getMedicineName()
    {
        $this->pdo = Database::connect();

        $sql = "select id,name from medicines";

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMedicine()
    {
        $this->pdo = Database::connect();

        $sql = "select medicines.*,medi_category.category_name,medi_type.type from medicines join medi_category on medicines.category_id = medi_category.id
            join medi_type on medicines.type_id = medi_type.id";

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAddMedicine($data)
    {
        $this->pdo = Database::connect();

        $sql = "INSERT INTO `medicines`(`category_id`, `name`, `type_id`, `description`, `company`, `brand`, `created_at`, `updated_at`)
        VALUES  ( :category_id, :name, :type_id, :description, :company, :brand,:created_at,:updated_at)";

        $statement = $this->pdo->prepare($sql);

        //binding data value with query using foreach
        foreach ($data as $key => $value) {
            $statement->bindParam(":$key", $data[$key]);
        }

        //for created_at and updated_at
        $date_now = date('Y-m-d');
        $statement->bindParam(":created_at", $date_now);
        $statement->bindParam(":updated_at", $date_now);

        // var_dump($statement);

        // check for medicine warehouse
        if ($statement->execute()) {

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
        $this->pdo = Database::connect();

        $sql = "INSERT INTO `medi_stocks`(`medicine_id`, `qty`, `price`,`per_price`, `man_date`, `exp_date`,`enter_date`, `created_at`, `updated_at`) VALUES
        ( :medicine_id, :qty, :price,:per_price,:man_date, :exp_date,:enter_date,:created_at,:updated_at)";

        $statement = $this->pdo->prepare($sql);

        //binding data value with query using foreach
        foreach ($data as $key => $value) {
            $statement->bindParam(":$key", $data[$key]);
        }

        //for created_at and updated_at
        $date_now = date('Y-m-d');
        $statement->bindParam(":created_at", $date_now);
        $statement->bindParam(":updated_at", $date_now);

        // var_dump($statement);
        if ($statement->execute()) {

            // id , qty from medi_stocks
            $result = $this->getMediStock();

            // update medi_warehouses total qty where medicine_id == ??
            return $this->updateMediWarehouseStock($result);
        }

        return false;
    }

    public function getWarehouseStock()
    {

        $this->pdo = Database::connect();

        $sql = "select *,medicines.name from medi_warehouses join medicines on medi_warehouses.medicine_id = medicines.id";

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMediStkHis($medicine_id)
    {

        $this->pdo = Database::connect();

        $sql = "select medi_stocks.*,medicines.name from medi_stocks
            join medicines on medicines.id = medi_stocks.medicine_id where medicine_id = :medicine_id";

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":medicine_id", $medicine_id);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    // create warehouse stock
    private function createWarehouse($medicine_id)
    {

        $this->pdo = Database::connect();

        $sql = "insert into medi_warehouses (medicine_id,total_qty,created_at,updated_at)
                values (:medicine_id,0,:created_at,:updated_at)";

        $statement = $this->pdo->prepare($sql);

        $time = date('Y-m-d');
        $statement->bindParam(":medicine_id", $medicine_id);
        $statement->bindParam(":created_at", $time);
        $statement->bindParam(":updated_at", $time);

        return $statement->execute();
    }

    // get medicine_id,qty of new added stock from medi_stocks table
    private function getMediStock()
    {

        $this->pdo = Database::connect();

        $sql = "select medicine_id,qty from medi_stocks";

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {

            // return latest array value
            return $result[count($result) - 1];
        }

        return false;

    }

    // get total_qty from medi_warehouses where medicine_id = ??
    private function getWarehouseQty($medicine_id)
    {

        $this->pdo = Database::connect();

        $sql = "select total_qty from medi_warehouses where medicine_id = :medicine_id";

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":medicine_id", $medicine_id);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result["total_qty"];
    }

    // update medi warehouse
    private function updateMediWarehouseStock(array $data = [])
    {

        // start query
        $this->pdo = Database::connect();

        // get total_qty from medi_warehouses
        /*
        data prepare
         */
        $treatment = new Treatment();

        $used_qty = $treatment->getUsedQty($data["medicine_id"]);

        $qtys = $this->getSpecificItem($data['medicine_id']);

        if (count($data) > 0) {

            if ($used_qty == null) {

                $total_qty = $qtys;

                $sql = "update medi_warehouses set total_qty = :total_qty where medicine_id = :medicine_id";

                $statement = $this->pdo->prepare($sql);

                $statement->bindParam(":total_qty", $total_qty);
                $statement->bindParam(":medicine_id", $data["medicine_id"]);

                return $statement->execute();
            } else {
                return $this->reduceForNewAddedMediStock($data["medicine_id"]);
            }
        }

        // if ($id != null && count($data) == 0) {
        //     $total_qty = $this->getWarehouseQty($id);

        //     if ($used_qty == null) {

        //         $total_qty = $qtys;

        //         $sql = "update medi_warehouses set total_qty = :total_qty where medicine_id = :medicine_id";

        //         $statement = $this->pdo->prepare($sql);

        //         $statement->bindParam(":total_qty", $total_qty);
        //         $statement->bindParam(":medicine_id", $id);

        //         return $statement->execute();
        //     }
        // }
        // else{
        //     return $treatment->reduceMedicineStock($data['medicine_id']);
        // }

    }

    public function getEditStock($id)
    {
        $this->pdo = Database::connect();
        $sql = "select medi_stocks.*,medicines.name from medi_stocks
            join medicines on medicines.id = medi_stocks.medicine_id
             where medi_stocks.id = :id";
        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":id", $id);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    public function updateMedicineStock($data)
    {
        $data["updated_at"] = date('Y-m-d');

        $this->pdo = Database::connect();

        $medicine_id = $data['medi_id'];
        unset($data["medi_id"]);

        $sql = "UPDATE `medi_stocks` SET  `qty` = :qty , `price` = :price ,per_price = :per_price, `man_date` = :man_date, `exp_date` = :exp_date ,`enter_date` = :enter_date, `updated_at` = :updated_at WHERE `medi_stocks`.`id` = :id";
        $statement = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $statement->bindParam(":$key", $data[$key]);

        }
        if ($statement->execute()) {
            return $this->updateMediWarehouseStock([], $medicine_id);
        }
        return false;
    }

    // get medicine detail
    public function getMedicineDetails($id)
    {
        $this->pdo = Database::connect();

        $sql = 'select medicines.*,medi_type.type,medi_category.category_name from medicines
                join medi_type on medi_type.id = medicines.type_id
                join medi_category on medi_category.id = medicines.category_id
                where medicines.id = :id';

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":id", $id);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    // get medi stocks of specific item
    private function getSpecificItem($medicine_id)
    {

        $this->pdo = Database::connect();

        $sql = 'select sum(qty) as qty from medi_stocks where medicine_id = :medicine_id';

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":medicine_id", $medicine_id);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC)['qty'];
        }

        return false;
    }

    // get all qty from medi warehouses
    private function reduceForNewAddedMediStock($medicine_id)
    {
        $this->pdo = Database::connect();

        $stock_qty = $this->getSpecificItem($medicine_id);

        $query = 'select sum(qty) as qty from treat_medi_lists where medicine_id = :medicine_id';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":medicine_id", $medicine_id);

        if ($statement->execute()) {
            $used_qty = $statement->fetch(PDO::FETCH_ASSOC)['qty'];

            echo $used_qty;

            $total_qty = $stock_qty - $used_qty;

            $query1 = 'update medi_warehouses set total_qty = :total_qty where medicine_id = :medicine_id';

            $statement1 = $this->pdo->prepare($query1);

            $statement1->bindParam(":total_qty",$total_qty);
            $statement1->bindParam(":medicine_id",$medicine_id);

            return $statement1->execute();
        }
        return false;
    }
}
