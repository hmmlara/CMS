<?php 

session_start();

if(isset($_SESSION['search_medis'])){
    if(isset($_SESSION['search_data'])){
        unset($_SESSION['search_data']);
    }
    $fields = ['No','Date','Opening','Stock','Usage','Remaining'];
    $filename = $_SESSION['medi_name'].'('.$_SESSION['medi_year'].'-'.$_SESSION['medi_month'].').csv';
    $file = fopen('php://output','w');
    fputcsv($file,$fields,',');

    $data = $_SESSION['search_medis'];
    for($i = 0; $i < count($data); $i++){
        $row_data = array($i+1,date('d/M/Y', strtotime($data[$i]['date'])),$data[$i]['opening'],$data[$i]['stock'],$data[$i]['used'],$data[$i]['closing']);
        fputcsv($file,$row_data,',');
    }

    fclose($file);
    header('Content-Type: application/csv;charset=UTF-8'); 
    header('Content-Disposition: attachment; filename="'.$filename.'";');

    exit();
}