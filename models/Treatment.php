<?php

require_once dirname(__DIR__) . '/core/libraray.php';

class Treatment
{
    private $pdo;

    protected function getAllTreatments(){
        $this->pdo = Database::connect();

        $query = 'SELECT treatments.id,treatments.treatment_date,patients.name as pr_name,patients.pr_code,
        user_infos.user_id,user_infos.user_code as dr_code,user_infos.name as dr_name,
        payments.invoice_code
        FROM `treatments`
        JOIN patients ON patients.id = treatments.pr_id
        JOIN payments ON payments.treatment_id = treatments.id
        JOIN user_infos ON user_infos.user_id = treatments.user_id';

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // get treatment details
    protected function getDetails($treatment_id){
        $this->pdo = Database::connect();

        $query = 'select treatments.id,treatments.note,treatments.treatment_date FROM treatments where treatments.id = :treatment_id';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":treatment_id",$treatment_id);

        if($statement->execute()){

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            $result['medicine_list'] = $this->getTreatMediLists($result['id']);

            return $result;
        }
    }
    // get treatment
    protected function getTreatment($treatment_id)
    {
        $this->pdo = Database::connect();

        $query = 'SELECT treatments.*,patients.name as pr_name,user_infos.name as dr_name,
        MAX(service_prices.service_price) as service_price
        FROM treatments
        JOIN patients ON patients.id = treatments.pr_id
        JOIN user_infos ON user_infos.user_id = treatments.user_id
        JOIN service_prices ON service_prices.user_id = user_infos.user_id 
        WHERE treatments.id = :treatment_id';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":treatment_id",$treatment_id);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC)[0];

        if (count($result) > 0) {

            $result['medi_lists'] = $this->getTreatMediLists($this->getTreatId());
        }

        return $result;
    }

    protected function createTreatment($data)
    {

        $this->pdo = Database::connect();

        $date = date('Y-m-d');
        $data['treatment_date'] = $date;
        $data['created_at'] = $date;
        $data['updated_at'] = $date;

        $query = 'INSERT INTO `treatments`(`pr_id`, `user_id`, `treatment_date`, `duration`, `note`, `created_at`, `updated_at`)
                        VALUES (:pr_id,:user_id,:treatment_date,:duration,:note,:created_at,:updated_at)';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(':pr_id', $data['pr_id']);
        $statement->bindParam(':user_id', $data['dr_id']);
        $statement->bindParam(':treatment_date', $data['treatment_date']);
        $statement->bindParam(':duration', $data['duration']);
        $statement->bindParam(':note', $data['note']);
        $statement->bindParam(":created_at", $data['created_at']);
        $statement->bindParam(":updated_at", $data['updated_at']);

        unset($data['pr_id']);
        unset($data['dr_id']);
        unset($data['treatment_date']);
        unset($data['duration']);
        unset($data['note']);

        if ($statement->execute()) {

            $treatment_id = $this->getTreatId();

            $length = count($data['medi_qty']);

            foreach (range(0, $length - 1) as $index) {
                if(!empty($data['medi_qty'][$index])){
                    if ($this->addTreatList($data['medicine_id'][$index], $treatment_id, $data['medi_qty'][$index])) {
                        $result = $this->reduceMedicineStock($data['medicine_id'][$index]);
                    }
                }
            }

            return $result;
        }
        return false;
    }

    // add treatment medi list
    private function addTreatList($medicine_id, $treatment_id, $qty)
    {
        $this->pdo = Database::connect();

        $query = 'select max(per_price) as per_price from medi_stocks where medicine_id = :medicine_id';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":medicine_id", $medicine_id);

        if ($statement->execute()) {
            $per_price = $statement->fetch(PDO::FETCH_ASSOC)['per_price'];

            $date = date('Y-m-d');

            $query1 = 'INSERT INTO `treat_medi_lists`(`medicine_id`,`treatment_id`,`qty`, `medi_per_price`,`created_at`,`updated_at`)
                        VALUES (:medicine_id,:treatment_id,:qty,:medi_per_price,:created_at,:updated_at)';

            $statement1 = $this->pdo->prepare($query1);

            $statement1->bindParam(":medicine_id", $medicine_id);
            $statement1->bindParam(":treatment_id", $treatment_id);
            $statement1->bindParam(":qty", $qty);
            $statement1->bindParam(":medi_per_price", $per_price);
            $statement1->bindParam(":created_at", $date);
            $statement1->bindParam(":updated_at", $date);

            return $statement1->execute();
        }

        return false;
    }

    // get Trement id of latest treatment
    private function getTreatId()
    {
        $this->pdo = Database::connect();

        $query = 'select max(id) as id from treatments';

        $statement = $this->pdo->prepare($query);

        if ($statement->execute()) {
            $result = $statement->fetch(PDO::FETCH_ASSOC)['id'];

            return $result;
        }
    }
    // reduce Medi Stock
    private function reduceMedicineStock($medicine_id)
    {

        $this->pdo = Database::connect();

        // get quantity of a medicine from using
        $qty = $this->getUsedQty($medicine_id);

        $query = 'select max(qty) as qty from medi_stocks where medicine_id = :medicine_id';

        $statement = $this->pdo->prepare($query);
        $statement->bindParam('medicine_id',$medicine_id);

        if($statement->execute()){
        
        $stock = $statement->fetch(PDO::FETCH_ASSOC)['qty'];

        $total_qty = $stock - $qty;

        // reduce from mediware house
        $query1 = 'update medi_warehouses set medi_warehouses.total_qty = :qty,updated_at = :updated_at
            where medi_warehouses.medicine_id = :medicine_id';

        $date = date('Y-m-d');
        $statement1 = $this->pdo->prepare($query1);

        $statement1->bindParam(":medicine_id", $medicine_id);
        $statement1->bindParam(":updated_at", $date);
        $statement1->bindParam(":qty", $total_qty);

        return $statement1->execute();
        }
    }

    public function getUsedQty($medicine_id)
    {
        $this->pdo = Database::connect();

        $query = 'select sum(qty) as qty from treat_medi_lists where medicine_id = :medicine_id';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":medicine_id", $medicine_id);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC)['qty'];
    }

    // get treatement mediList
    private function getTreatMediLists($treatment_id)
    {
        $this->pdo = Database::connect();

        $query = 'select medicines.name as medi_name,treat_medi_lists.qty,treat_medi_lists.medi_per_price from treat_medi_lists
                join medicines on medicines.id = treat_medi_lists.medicine_id
                where treat_medi_lists.treatment_id = :treatment_id';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":treatment_id", $treatment_id);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
