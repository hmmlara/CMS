<?php

require_once dirname(__DIR__).'/models/Schedule.php';

class ScheduleController extends Schedule
{
    // get all schedule
    public function getAll(){
        return $this->getSchedules();
    }
    // save opt doc schedule
    public function save($data)
    {
        return $this->saveSchedule($data);
    }

    // save for main doc
    public function saveMain($data){
        return $this->saveSchMain($data);
    }

    // update schedule
    public function update($data){
        return $this->updateSchdule($data);
    }

    // delete schedule
    public function delete($id){
        try{
            return $this->deleteSchedule($id);
        }
        catch(PDOException $e){
            return false;
        }
    }

    // get schedule by user id
    public function getSpecificSchedule($user_id){
        return $this->getById($user_id);
    }
}
