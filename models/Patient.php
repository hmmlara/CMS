<?php

require_once dirname(__DIR__) . "/core/Database.php";

class Patient
{
    private $pdo;

    // get Treament data
    public function getPatientTreat($pr_id)
    {
        $this->pdo = Database::connect();

        $query = "SELECT treatments.id,treatments.treatment_date,user_infos.name,user_infos.user_code
                    FROM treatments
                    JOIN user_infos  ON treatments.user_id = user_infos.user_id
                    WHERE treatments.pr_id = :pr_id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":pr_id",$pr_id);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get All Patients Data
    protected function getAllPatients()
    {

        $this->pdo = Database::connect();

        $query = "select id,pr_code,name,phone from patients";

        $statment = $this->pdo->prepare($query);

        if ($statment->execute()) {
            return $statment->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
    }

    protected function getPatientById($id)
    {

        $this->pdo = Database::connect();

        $query = "select * from patients where id = :id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":id", $id);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    // Get Patient Code pr()
    protected function getPatientCode()
    {

        $this->pdo = Database::connect();

        $query = "select pr_code from patients";

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // return the latest pr_code of an array
        return (count($result) > 0) ? $result[count($result) - 1]["pr_code"] : '';
    }

    // save patient
    protected function savePatient($data)
    {

        $this->pdo = Database::connect();

        $query = "INSERT INTO `patients`(`pr_code`, `name`, `phone`, `age`, `weight`, `height`, `gender`, `blood_type`, `created_at`, `updated_at`) VALUES (:pr_code,:name,:phone,:age,:weight,:height,:gender,:blood_type,:created_at,:updated_at)";

        $statment = $this->pdo->prepare($query);

        // binding data value with query using foreach
        foreach ($data as $key => $value) {
            $statment->bindParam(":$key", $data[$key]);
        }

        // for created_at and updated_at
        $date_now = date('Y-m-d');

        $statment->bindParam(":created_at", $date_now);
        $statment->bindParam(":updated_at", $date_now);

        return $statment->execute();
    }

    // update patient data
    protected function updatePatient($data)
    {

        $this->pdo = Database::connect();

        // change updated_at data from $data
        $data["updated_at"] = date('Y-m-d');

        $query = "UPDATE `patients` SET `pr_code`= :pr_code,`name`= :name,`phone`= :phone,`age`= :age,`weight`= :weight,`height`= :height,`gender`= :gender,`blood_type`= :blood_type,`created_at`= :created_at,`updated_at`= :updated_at
                    WHERE id = :id";

        $statment = $this->pdo->prepare($query);

        // binding data value with query using foreach
        foreach ($data as $key => $value) {
            $statment->bindParam(":$key", $data[$key]);
        }

        return $statment->execute();
    }

    protected function deletePatient($id)
    {

        $this->pdo = Database::connect();

        $query = "delete from patients where id = :id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":id", $id);

        return $statement->execute();
    }
}
