<?php
    require_once "/controllers/ReceptionController.php";

    if(isset($_POST['id'])){

        $receptionController=new ReceptionController;

        $result=$receptionController->delete($_POST['id']);
        if($result){
            echo "success";
        }
        else{
            echo "fail";
        }
    }
?>

