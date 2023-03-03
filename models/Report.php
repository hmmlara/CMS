<?php

require_once dirname(__DIR__).'/core/Database.php';
class Report
{
    private $pdo;

    protected function getMonthPati($year){
        $this->pdo = Database::connect();

        $query = "SELECT
                    COUNT(*) AS count
                    FROM treatments
                    WHERE YEAR(treatment_date) = :year
                    GROUP BY YEAR(treatment_date), MONTH(treatment_date)
                    ORDER BY YEAR(treatment_date), MONTH(treatment_date);";

        $statment = $this->pdo->prepare($query);

        $statment->bindParam(':year', $year);

        $statment->execute();

        return $statment->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getDailyQty(){
        $this->pdo=Database::connect();

        $query="SELECT MONTHNAME(MAX(treatment_date)) AS month,YEAR(treatment_date) as year,COUNT(treatments.treatment_date) as treatments,treatments.treatment_date,
        SUM(payments.amount) as income
        FROM treatments
        JOIN payments ON payments.treatment_id = treatments.id
    
        GROUP BY treatments.treatment_date";

        $statment=$this->pdo->prepare($query);

        // $statment->bindParam(':month',$month);

        $statment->execute();

        return $statment->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getMonthIn($year){
        $this->pdo = Database::connect();

        $query = "SELECT
        COALESCE(SUM(payments.amount),0) AS count
        FROM treatments
        JOIN payments ON payments.treatment_id = treatments.id
        WHERE YEAR(treatment_date) = :year
        GROUP BY YEAR(treatment_date), MONTH(treatment_date)
        ORDER BY YEAR(treatment_date), MONTH(treatment_date);";

        $statment = $this->pdo->prepare($query);

        $statment->bindParam(':year',$year);

        $statment->execute();

        return $statment->fetchAll(PDO::FETCH_ASSOC);
    }
}
