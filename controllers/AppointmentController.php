<?php
    include_once __DIR__."/../models/Appointment.php";


    class AppointmentController extends Appointment{

        //add appointment
        // public function add($data){
        //     $result=$this->addData($data);
        //     return $result;
        // }
            
        //get app_time
        public function times(){
            $result=$this->getTimes();
            return $result;
        }
    }
?>