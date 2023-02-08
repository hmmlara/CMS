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
            }
        }
    }
    return $body;
}

// change 12 hours format to 24 hours format
function format_24($time){
    $datetime = new DateTime($time);

    return $datetime->format("H:i");

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