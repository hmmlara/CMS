<?php

require_once dirname(__DIR__).'/core/Database.php';

class Appointment{

    private $pdo;

    //get Appointments
    protected function getAppoints($date){
        $this->pdo = Database::connect();
        
        $query = 'select appointments.*,patients.pr_code as pr_name,user_infos.name as dr_name
                from appointments join patients on patients.id = appointments.pr_id
                join user_infos on user_infos.user_id = appointments.user_id
                where appointments.appointment_date = :date and status = 0';
        
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

    // delete appointment
    protected function delAppoint($id){
        $this->pdo = Database::connect();

        $query = "delete from appointments where id = :id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":id",$id);

        return $statement->execute();
    }

}