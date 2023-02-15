<?php

require_once dirname(__DIR__).'/core/Database.php';

class Appointment{

    private $pdo;

    //add appointment
    // protected function addData($data){
    //     $this->pdo=Database::connect();

    //     $sql="";
    // }

    //get AppointmentTimes
    protected function getTimes(){
        $this->pdo=Database::connect();

        $sql="SELECT schedules.shift_day,schedules.shift_start,schedules.shift_end,user_infos.name
        from schedules join user_infos
        where schedules.user_id = user_infos.user_id";

        $statement=$this->pdo->prepare($sql);
        $statement->execute();

        $result=$statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;


    }

}