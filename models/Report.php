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
