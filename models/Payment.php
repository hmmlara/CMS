<?php

require_once dirname(__DIR__).'/core/Database.php';

class Payment
{
    private $pdo;

    protected function getUncheck(){
        $this->pdo = Database::connect();

        $query = "SELECT treatments.id,treatments.treatment_date,patients.name as pr_name,patients.pr_code,
                    user_infos.user_id,user_infos.user_code as dr_code,user_infos.name as dr_name
                    FROM `treatments`
                    JOIN patients ON patients.id = treatments.pr_id
                    JOIN user_infos ON user_infos.user_id = treatments.user_id
                    WHERE treatments.id NOT IN (SELECT payments.treatment_id FROM payments)";
        $statement = $this->pdo->prepare($query);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

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

        $query = 'INSERT INTO `payments`(`treatment_id`, `amount`,`invoice_code`,`service_id`, `created_at`, `updated_at`)
                 VALUES (:treatment_id,:amount,:invoice_code,:service_id,:created_at,:updated_at)';

        $statement = $this->pdo->prepare($query);

        foreach($data as $key => $value){
            $statement->bindParam(":$key",$data[$key]);
        }

        return $statement->execute();
    }

    protected function getServiceId($user_id){
        $this->pdo = Database::connect();

        $query = "select max(id) as id from service_prices where user_id = :user_id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":user_id",$user_id);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC)['id'];
    }
}
