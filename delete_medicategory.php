<?php

require_once "./controllers/MediCategoryController.php";

if(isset($_POST["id"])){
    $medicategoryController = new MediCategoryController();

    $result = $medicategoryController->delete($_POST['id']);

    if($result){
        echo "success";
    }
    else{
        echo "fail";
    }
}
