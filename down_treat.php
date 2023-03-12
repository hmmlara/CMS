<?php 

session_start();

if(isset($_SESSION['search_data'])){
    if(isset($_SESSION['search_medis'])){
        unset($_SESSION['search_medis']);
    }
    $fields = ['id','Daily','Treament quantity','Income List'];
    $filename = 'treatment_list('.$_SESSION['treat_year'].'-'.$_SESSION['treat_month'].').csv';
    $file = fopen('php://output','w');
    fputcsv($file,$fields,',');

    $data = $_SESSION['search_data'];
    for($i = 0; $i < count($data); $i++){
        $row_data = array($i+1,$data[$i]['treatment_date'],date('d/M/Y', strtotime($data[$i]['treatment_date'])),$data[$i]['income']);
        fputcsv($file,$row_data,',');
    }

    fclose($file);
    header('Content-Type: application/csv;charset=UTF-8'); 
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    unset($_SESSION['search_data']);
    exit();
}
