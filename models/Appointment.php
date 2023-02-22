<?php

require_once dirname(__DIR__).'/core/Database.php';

class Appointment{

    private $pdo;

    // get all appointments
    protected function getAllAppoints(){
        $this->pdo = Database::connect();

        $query = 'select * from appointments';

        $statement = $this->pdo->prepare($query);

        if($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    //get Appointments
    protected function getAppoints($date){
        $this->pdo = Database::connect();
        
        $query = 'select appointments.*,patients.pr_code,patients.name as pr_name,patients.id as pr_id,user_infos.user_id as dr_id,user_infos.name as dr_name
                from appointments join patients on patients.id = appointments.pr_id
                join user_infos on user_infos.user_id = appointments.user_id
                where appointments.appointment_date = :date';
        
        $statement = $this->pdo->prepare($query);

        $statement->bindParam(':date',$date);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    //add appointment
    protected function addData($data){

        $data['status'] = 0;
        $data['created_at'] = date('Y-m-d');
        $data['updated_at'] = date('Y-m-d');

        $this->pdo=Database::connect();

        $query = "INSERT INTO `appointments`(`pr_id`, `user_id`, `appointment_date`, `appointment_time`, `status`, `created_at`, `updated_at`) 
                VALUES (:pr_id, :user_id, :appointment_date, :appointment_time, :status, :created_at, :updated_at)";

        $statement = $this->pdo->prepare($query);

        foreach($data as $key => $value){
            $statement->bindParam(":$key", $data[$key]);
        }

        return $statement->execute();
    }
    // update status
    public function updateStatus($id,$status_code){
        $this->pdo = Database::connect();

        $query = "update appointments set status = :status,updated_at = :updated_at where id = :id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":status",$status_code);
        $statement->bindParam(":updated_at",date('Y-m-d'));
        $statement->bindParam(":id",$id);

        return $statement->execute();
    }
}