<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/libraray.php';

class Schedule
{

    private $pdo;

    // get schedule
    protected function getSchedules()
    {

        $this->pdo = Database::connect();

        $query = "select schedules.*,user_infos.name from schedules
                    join user_infos on user_infos.user_id = schedules.user_id";

        $statement = $this->pdo->prepare($query);

        $result = [];
        $statement->execute();

        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $result[$value["id"]] = $value;
        }

        return (count($result) > 0) ? $result : $result = [];
    }

    // add schedule
    protected function saveSchedule($data)
    {
        // change 24 hours format
        $data["shift_start"] = format_24(date("g:i a", strtotime($data["shift_start"])));
        $data["shift_end"] = format_24(date("g:i a", strtotime($data["shift_end"])));

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

    // save for main doc
    protected function saveSchMain($data)
    {

        // change 24 hours format
        $data["shift_start"] = format_24(date("g:i a", strtotime($data["shift_start"])));
        $data["shift_end"] = format_24(date("g:i a", strtotime($data["shift_end"])));

        // for created_at and updated_at
        $time = date('Y-m-d');

        $data["created_at"] = $time;
        $data["updated_at"] = $time;

        // start date and end date
        $start = sprintf('%0d', explode('-', $data['start_day'])[2]);
        $end = sprintf('%0d', explode('-', $data['end_day'])[2]);
        $month_start = sprintf('%0d', explode('-', $data['start_day'])[1]);
        $month_end = sprintf('%0d', explode('-', $data['end_day'])[1]);
        $year = sprintf('%04d', explode('-', $data['start_day'])[0]);

        // echo '<pre>';
        // var_dump($start, $end);
        // echo '</pre>';

        $this->pdo = Database::connect();

        $query = "insert into schedules (user_id,shift_day,shift_start,shift_end,created_at,updated_at)
                     values (:user_id,:shift_day,:shift_start,:shift_end,:created_at,:updated_at)";

        $statement = $this->pdo->prepare($query);

        $result = false;

        if ($month_start == $month_end) {
            // format to 0x if num is less than 10
            foreach (range($start, $end) as $index) {
                $day_num = ($index >= 10) ? $index : "0$index";
                $shift_day = explode('-', $data['start_day'])[0] . "-" . sprintf('%02d', explode('-', $data["start_day"])[1]) . "-" . $day_num;

                if (date('l', strtotime($shift_day)) == $data['w_day']) {
                    echo 'hello';
                    // bind param
                    $statement->bindParam(":user_id", $data['user_id']);
                    $statement->bindParam(":shift_day", $shift_day);
                    $statement->bindParam(":shift_start", $data['shift_start']);
                    $statement->bindParam(":shift_end", $data['shift_end']);
                    $statement->bindParam(":created_at", $data['created_at']);
                    $statement->bindParam(":updated_at", $data['updated_at']);

                    $result = $statement->execute();
                }
            }
        } else {
            foreach (range($month_start, $month_end) as $parent) {
                $days = cal_days_in_month(CAL_GREGORIAN, $parent, $year);


                // format to 0x if num is less than 10
                foreach (range($start, $end) as $index) {
                    $day_num = ($index >= 10) ? $index : "0$index";
                    $month = $parent;
                    $shift_day = $year . "-" . sprintf('%02d',$month). "-" . $day_num;
                    if ($index > $days) {
                        break;
                    } else {
                        // echo 'hello';
                        if (date('l', strtotime($shift_day)) == $data['w_day']) {
                            // bind param
                            $statement->bindParam(":user_id", $data['user_id']);
                            $statement->bindParam(":shift_day", $shift_day);
                            $statement->bindParam(":shift_start", $data['shift_start']);
                            $statement->bindParam(":shift_end", $data['shift_end']);
                            $statement->bindParam(":created_at", $data['created_at']);
                            $statement->bindParam(":updated_at", $data['updated_at']);

                            $result = $statement->execute();
                        }
                    }
                }
                // echo $month;
            }
        }
        return $result;

    }
    // update schedule
    protected function updateSchdule($data)
    {

        // make for updated_at
        $data["updated_at"] = date('Y-m-d');

        $this->pdo = Database::connect();

        $query = 'update schedules set user_id=:user_id,shift_day=:shift_day,shift_start=:shift_start,shift_end=:shift_end,updated_at=:updated_at
                    where id = :id';

        $statement = $this->pdo->prepare($query);

        // bindParam with loop
        foreach ($data as $key => $value) {
            $statement->bindParam(":$key", $data[$key]);
        }

        return $statement->execute();
    }

    protected function deleteSchedule($id)
    {

        $this->pdo = Database::connect();

        $query = "delete from schedules where id = :id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam("id", $id);

        return $statement->execute();
    }

    protected function getById($id)
    {
        $this->pdo = Database::connect();

        $query = "select * from schedules where user_id = :user_id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":user_id", $id);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
