<?php

require_once __DIR__."/../core/Database.php";

class MedicineCategory {

    private $pdo;
    public function getMedicineCategory()
    {
        $this->pdo=Database::connect();
        $sql="select * from medi_category";
        $statement=$this->pdo->prepare($sql);
        if($statement->execute())
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function getAddCategory($data)
    {
        $this->pdo=Database::connect();
        $sql="INSERT INTO `medi_category` (`category_name`, `created_at`, `updated_at`) VALUES ( :category_name, :created_at, :updated_at)";
        var_dump($data);
        $statement=$this->pdo->prepare($sql);
        //building data value with query using foreach
        foreach($data as $key => $value)
        {
            $statement->bindParam(":$key",$data[$key]);
        }
        //$statement->bindParam(":category_name",$category_name);

        $date_now = date('Y-m-d');
        $statement->bindParam(":created_at",$date_now);
        $statement->bindParam(":updated_at",$date_now);
        return $statement->execute();

        
    }
}

?>