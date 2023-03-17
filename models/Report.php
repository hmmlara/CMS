<?php

require_once dirname(__DIR__) . '/core/Database.php';
class Report
{
    private $pdo;

    protected function getMonthPati($year)
    {
        $this->pdo = Database::connect();

        $query = "SELECT
                    COUNT(*) AS count,MONTHNAME(treatment_date) as month
                    FROM treatments
                    WHERE YEAR(treatment_date) = :year
                    GROUP BY YEAR(treatment_date), MONTH(treatment_date)
                    ORDER BY YEAR(treatment_date), MONTH(treatment_date);";

        $statment = $this->pdo->prepare($query);

        $statment->bindParam(':year', $year);

        $statment->execute();

        // for monthly 
        $db_result = $statment->fetchAll(PDO::FETCH_ASSOC);
        $months = [];
        $result = [];
        foreach(range(1,12) as $data){
            array_push($months,date('F',mktime(0,0,0,$data)));
        }
        
        foreach($months as $month){
            if(in_array($month,array_column($db_result,'month'))){
                foreach($db_result as $data){
                    if($data['month'] == $month){
                        array_push($result,["count" => $data["count"]]);
                    }
                }
            }
            else{
                array_push($result,["count" => 0]);
            }
        }

        return $result;
    }

    protected function getDailyQty()
    {
        $this->pdo = Database::connect();

        $query = "SELECT MONTHNAME(MAX(treatment_date)) AS month,YEAR(treatment_date) as year,COUNT(treatments.treatment_date) as treatments,treatments.treatment_date,
        SUM(payments.amount) as income
        FROM treatments
        JOIN payments ON payments.treatment_id = treatments.id

        GROUP BY treatments.treatment_date";

        $statment = $this->pdo->prepare($query);

        // $statment->bindParam(':month',$month);

        $statment->execute();

        return $statment->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getMonthIn($year)
    {
        $this->pdo = Database::connect();

        $query = "SELECT
        COALESCE(SUM(payments.amount),0) AS count,MONTHNAME(treatment_date) as month
        FROM treatments
        JOIN payments ON payments.treatment_id = treatments.id
        WHERE YEAR(treatment_date) = :year
        GROUP BY YEAR(treatment_date), MONTH(treatment_date)
        ORDER BY YEAR(treatment_date), MONTH(treatment_date);";

        $statment = $this->pdo->prepare($query);

        $statment->bindParam(':year', $year);

        $statment->execute();

        $db_result = $statment->fetchAll(PDO::FETCH_ASSOC);
        $months = [];
        $result = [];
        foreach(range(1,12) as $data){
            array_push($months,date('F',mktime(0,0,0,$data)));
        }
        
        foreach($months as $month){
            if(in_array($month,array_column($db_result,'month'))){
                foreach($db_result as $data){
                    if($data['month'] == $month){
                        array_push($result,["count" => $data["count"]]);
                    }
                }
            }
            else{
                array_push($result,["count" => 0]);
            }
        }

        return $result;
    }

    public function getMediRp($data)
    {

        $result = [];

        $data1 = [
            'medicine_id' => $data['medicine_id'],
            'search_date' => $data['start_date'],
        ];

        $closingStock = 0;

        if ($this->getOpeningStock($data1) != 0) {
            $closingStock = $this->getOpeningStock($data1);
        }

        $stocks = $this->stockQty($data1);
        $usedStocks = $this->usedQty($data1);

        $start = explode("-", $data['start_date'])[2];
        $end = explode("-", $data['end_date'])[2];

        $month = sprintf('%02d', explode("-", $data['start_date'])[1]);
        $year = sprintf('%02d', explode('-', $data['start_date'])[0]);

        $oneStock = [];
        foreach (range($start, $end) as $date) {

            // date for wanted stock
            $wanted_date = $year . "-" . $month . "-" . (sprintf('%02d', $date));
            $oneStock['date'] = $wanted_date;

            if ($wanted_date > $data['end_date']) {
                break;
            }

            if ($closingStock == 0) {
                $oneStock['opening'] = 0;
            } else {
                $oneStock['opening'] = $closingStock;
            }

            // inward stock
            if (in_array($wanted_date, array_column($stocks, 'date'))) {
                foreach ($stocks as $stock) {
                    if ($stock['date'] == $wanted_date) {
                        $oneStock['stock'] = $stock['stock'];
                    }
                }
            } else {
                $oneStock['stock'] = 0;
            }

            // outward stock
            if (in_array($wanted_date, array_column($usedStocks, 'date'))) {

                foreach ($usedStocks as $u_stock) {
                    if ($u_stock['date'] == $wanted_date) {
                        $oneStock['used'] = $u_stock['used'];
                    }
                }
            } else {
                $oneStock['used'] = 0;
            }
            $closingStock = $oneStock['opening'] + $oneStock['stock'] - $oneStock['used'];

            $oneStock['closing'] = $closingStock;

            // $oneStock['closing'] = $closingStock;
            array_push($result, $oneStock);
        }

        return $result;
    }

    private function getOpeningStock($data)
    {

        $this->pdo = Database::connect();

        $query = "SELECT SUM(medi_stocks.qty) as ini
        FROM medi_stocks
        WHERE medi_stocks.enter_date < :search_date AND medicine_id = :medicine_id
        ";

        $statement = $this->pdo->prepare($query);

        foreach ($data as $key => $value) {
            $statement->bindParam(":$key", $data[$key]);

        }

        if ($statement->execute()) {
            
            $ini = $statement->fetch(PDO::FETCH_ASSOC)['ini'];
            $ini_stock = (empty($ini))? 0 : $ini;

            $query1 = 'SELECT SUM(treat_medi_lists.qty) as used FROM treat_medi_lists
            JOIN treatments ON treatments.id = treat_medi_lists.treatment_id
           WHERE treat_medi_lists.medicine_id = :medicine_id AND treatments.treatment_date < :search_date';

            $statement1 = $this->pdo->prepare($query1);

            foreach ($data as $key => $value) {
                $statement1->bindParam(":$key",$data[$key]);
            }

            $statement1->execute();

            $used = $statement1->fetch(PDO::FETCH_ASSOC)['used'];
            $used_stock = (empty($used)) ? 0 : $used;

            return $ini_stock - $used_stock;
        }

        return 0;
    }

    private function stockQty($data)
    {
        $this->pdo = Database::connect();

        $query = "SELECT SUM(medi_stocks.qty) as stock,medi_stocks.enter_date as date
        FROM medi_stocks
        WHERE medi_stocks.medicine_id = :medicine_id AND MONTH(medi_stocks.enter_date) = MONTH(:search_date)
        GROUP BY medi_stocks.enter_date";

        $statement = $this->pdo->prepare($query);

        foreach ($data as $key => $value) {
            $statement->bindParam(":$key", $data[$key]);
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // private functions
    private function usedQty($data)
    {
        $this->pdo = Database::connect();

        $query = "SELECT SUM(treat_medi_lists.qty) as used,treatments.treatment_date as date
        FROM treat_medi_lists
        JOIN treatments ON treatments.id = treat_medi_lists.treatment_id
        WHERE medicine_id = :medicine_id AND MONTH(treatments.treatment_date) = MONTH(:search_date)
        GROUP BY treatments.treatment_date";

        $statement = $this->pdo->prepare($query);

        foreach ($data as $key => $value) {
            $statement->bindParam(":$key", $data[$key]);
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
