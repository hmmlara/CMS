<?php

require_once __DIR__."/../core/Database.php";

class MediType{
    private $pdo;

    public function getMediType()
    {
        $this->pdo=Database::connect();

        $sql="select * from medi_type";

        $statement = $this->pdo->prepare($sql);

        if($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
    
    public function addMediType($data)
    {
        $this->pdo=Database::connect();
        $sql="insert into medi_type (type,created_at,updated_at) values (:type,:created_at,:updated_at)";

        $statement=$this->pdo->prepare($sql);

        //building data value with query using foreach
        foreach($data as $key => $value)
        {
            $statement->bindParam(":$key",$data[$key]);
        }

        $date_now = date('Y-m-d');
        $statement->bindParam(":created_at",$date_now);
        $statement->bindParam(":updated_at",$date_now);
        return $statement->execute();

    }

    public function getEditMeditype($id)
    {
        $this->pdo=Database::connect();
        $sql="select * from medi_type where id = :id";
        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":id", $id);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }
    public function updateMeditype($data)
    {
        $this->pdo = Database::connect();

        // change updated_at data from $data
        $data["updated_at"] = date('Y-m-d');

        $sql = "UPDATE `medi_type` SET `type`= :type,created_at= :created_at,`updated_at`= :updated_at 
                    WHERE id = :id";

        $statment = $this->pdo->prepare($sql);

        // binding data value with query using foreach
        foreach ($data as $key => $value) {
            $statment->bindParam(":$key", $data[$key]);
        }

        return $statment->execute();
    }
    
    // delete type
    public function deleteType($id){
        $this->pdo = Database::connect();

        $sql = "delete from medi_type where id = :id";

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":id",$id);

        return $statement->execute();
    }
}

?>