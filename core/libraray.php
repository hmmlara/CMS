<?php

function public_path()
{
    return dirname(__DIR__);
}

function search_data($array, $search_val)
{

    $body = [];
    $keys = [];
    // get key from array
    foreach ($array[0] as $key => $value) {
        array_push($keys, $key);
    }

    foreach ($array as $key => $value) {
        // get array when data is search
        foreach ($keys as $key) {
            if (strcasecmp($value[$key],$search_val) == 0) {
                array_push($body, $value);
                continue;
            }
        }
    }
    return $body;
}
// search date between
function search_date_between($arr,$start,$end){
    $result = [];

    $start_date = date('Y-m-d',strtotime($start));
    $end_date = date('Y-m-d', strtotime($end));
    foreach($arr as $value){
        $search_date = date('Y-m-d',strtotime(isset($value['appointment_date'])? $value['appointment_date'] : $value['treatment_date']));

        if($search_date > $start_date && $search_date < $end_date){
            array_push($result,$value);
            continue;
        }
    }

    return $result;
}

// change 12 hours format to 24 hours format
function format_24($time){
    $datetime = new DateTime($time);

    return $datetime->format("H:i");

}

// change 24 hours to 12 hours format
function format_12hrs($time){
    $datetime = new DateTime($time);

    return $datetime->format('h:i a');
}

// reduce function for only number associative array
function reduce_for_assoc_num_arr($array){
    $result = 0;
    foreach($array as $arr){
        foreach($arr as $val){
            $result += $val;
        }
    }
    
    return $result;
}