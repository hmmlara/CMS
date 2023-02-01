<?php

require_once 'File.php';

class Request
{
    public function getAll(){
        $body = [];

        $request = $_SERVER["REQUEST_METHOD"];

        // /get all data from $_GET
        if($request === "GET"){
            foreach($_GET as $key => $value){
                $body[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        // get all data from $_POST
        if($request === "POST"){
            foreach($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        
        return $body;
    }

    // getting file data from form
    public function file($name){
        return new File($_FILES[$name]);
    }
}
