<?php
include_once "./layouts/header.php";
include_once "./controllers/DoctorController.php";

$doctorController=new DoctorController();
$doctors=$doctorController->getDoctors();

?>

<div class="container">
    <div class="row">

        <div class="col-md-6">
            <h3>Doctor Data</h3>
        </div>
    </div>


    <div class="row">
        
            <?php
                for($i=0;$i<count($doctors);$i++){
                            echo "<div class='col-md-4'><a href='doctor_info.php?id=". $doctors[$i]["id"] ."' class='text-decoration-none text-dark '><div class='card w-75 '>
                            <img class='card-img-top' src='uploads/".$doctors[$i]['img']."' alt='' style='height: 250px;'>
                                <div class='card-title'><h5 class='text-center'>". $doctors[$i]['name']."</h5></div>
                                <div class='card-body d-flex justify-content-center'>
                                
                                <a href='#' class='btn btn-warning mr-2'><i class='far fa-edit'></i></a>
                                <a href='#' class='btn btn-danger'><i class='fa fa-trash'></i></a></div>
                                </div>
                                </a>
                                </div>";
                    } 
            ?>
        
    </div>
</div>


<?php
    include_once "./layouts/footer.php";
?>