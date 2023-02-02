<?php 

function public_path(){
    return dirname(__DIR__);
}

function search_data($array, $search_val){

    $body = [];
    $keys = [];
    // get key from array
    foreach($array[0] as $key => $value){
        array_push($keys,$key);
    }

    foreach($array as $key => $value){
        // get array when data is search
        foreach($keys as $key){
            if(strcasecmp($value[$key],$search_val) == 0){
                echo strcasecmp($value[$key],$search_val);
                array_push($body,$value);
            }
        }
    }
    return $body;
}