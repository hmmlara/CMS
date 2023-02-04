<?php

require_once dirname(__DIR__).'/models/Schedule.php';

class ScheduleController extends Schedule
{
    // get all schedule
    public function getAll(){
        return $this->getSchedules();
    }
    // save schedule
    public function save($data)
    {
        return $this->saveSchedule($data);
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
}
