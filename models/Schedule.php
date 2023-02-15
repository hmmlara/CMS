<?php

require_once dirname(__DIR__).'/core/Database.php';
require_once dirname(__DIR__).'/core/libraray.php';

class Schedule
{

    private $pdo;

    // get schedule
    protected function getSchedules(){
        
        $this->pdo = Database::connect();

        $query = "select schedules.*,user_infos.name from schedules 
                    join user_infos on user_infos.user_id = schedules.user_id";
        
        $statement = $this->pdo->prepare($query);

        $result = [];
        $statement->execute();

        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $value){
            $result[$value["id"]] = $value;
        }

        return (count($result) > 0)? $result : $result = [];
    }

    // add schedule
    protected function saveSchedule($data)
    {
        // change 24 hours format
        $data["shift_start"] = format_24(date("g:i a",strtotime($data["shift_start"])));
        $data["shift_end"] = format_24(date("g:i a",strtotime($data["shift_end"])));

        // for created_at and updated_at
        $time = date('Y-m-d');

        $data["created_at"] = $time;
        $data["updated_at"] = $time;
        
        $this->pdo = Database::connect();

        $query = "insert into schedules (user_id,shift_day,shift_start,shift_end,created_at,updated_at)
                    values (:user_id,:shift_day,:shift_start,:shift_end,:created_at,:updated_at)";

        $statement = $this->pdo->prepare($query);

        // bind param through array
        foreach ($data as $key => $value) {
            $statement->bindParam(":$key", $data[$key]);
        }

        return $statement->execute();
    }

    // update schedule
    protected function updateSchdule($data){

        // make for updated_at
        $data["updated_at"] = date('Y-m-d');
        
        $this->pdo = Database::connect();

        $query = 'update schedules set user_id=:user_id,shift_day=:shift_day,shift_start=:shift_start,shift_end=:shift_end,updated_at=:updated_at
                    where id = :id';

        $statement = $this->pdo->prepare($query);

        // bindParam with loop
        foreach($data as $key => $value){
            $statement->bindParam(":$key",$data[$key]);
        }

        return $statement->execute();
    }

    protected function deleteSchedule($id){

        $this->pdo = Database::connect();

        $query = "delete from schedules where id = :id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam("id",$id);

        return $statement->execute();
    }

    protected function getById($id){
        $this->pdo = Database::connect();

        $query = "select * from schedules where user_id = :user_id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":user_id",$id);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
