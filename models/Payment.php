<?php

require_once dirname(__DIR__).'/core/Database.php';

class Payment
{
    private $pdo;

    protected function getPayments(){
        $this->pdo = Database::connect();

        $query = "select treatment_id from payments";

        $statement = $this->pdo->prepare($query);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($result != null){
            return $result;
        }
    }

    protected function createPayment($data){

        $date = date('Y-m-d');

        $data['created_at'] = $date;
        $data['updated_at'] = $date;

        $this->pdo = Database::connect();

        $query = 'INSERT INTO `payments`(`treatment_id`, `amount`, `created_at`, `updated_at`)
                 VALUES (:treatment_id,:amount,:created_at,:updated_at)';

        $statement = $this->pdo->prepare($query);

        foreach($data as $key => $value){
            $statement->bindParam(":$key",$data[$key]);
        }

        return $statement->execute();
    }
}
