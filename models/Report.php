<?php

require_once dirname(__DIR__).'/core/Database.php';
class Report
{
    private $pdo;

    protected function getMonthPati($year){
        $this->pdo = Database::connect();

        $query = "SELECT
                    MONTHNAME(MAX(treatment_date)) AS month,
                    YEAR(treatment_date) AS year,
                    COUNT(*) AS count
                    FROM treatments
                    WHERE YEAR(treatment_date) = :year AND treatment_date >= LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 1 YEAR
                    GROUP BY YEAR(treatment_date), MONTH(treatment_date)
                    ORDER BY YEAR(treatment_date), MONTH(treatment_date);";

        $statment = $this->pdo->prepare($query);

        $statment->bindParam(':year', $year);

        $statment->execute();

        return $statment->fetchAll(PDO::FETCH_ASSOC);
    }
}
