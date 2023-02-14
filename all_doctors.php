<?php
include_once "./layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor' || $auth->hasRole() == 'reception'){
    header('location:_403');
}

include_once "./controllers/DoctorController.php";
require_once "./core/libraray.php";

$doctorController=new DoctorController();
$doctors=$doctorController->getDoctors();

if(isset($_POST["search"])){
    if(!empty($_POST["search_val"])){
        $doctors = search_data($doctors,$_POST["search_val"]);
    }
}

?>

<div class="container mt-3">
    <div class="row">
        <div class="col-8">
            <h4 class="mb-4">Doctor Lists</h4>
            <a href="add_doctor.php" class="btn btn-sm btn-success" id="add">Add</a>
        </div>
        <div class="col-4 mb-3">
            <form action="" method="post">
                <div class="form-group d-flex float-right mb-3">
                    <span class="mt-2">Search:&nbsp;</span>
                    <input type="text" name="search_val" id="" class="form-control w-50 mx-3" placeholder="Enter Doctor Code">
                    <button type="submit" name="search" class="btn btn-sm btn-success">Search</button>
                </div>
            </form>

            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <button class="btn btn-light btn-sm mx-2" id="grid1"><i class="fas fa-th fs-6"></i></button>
                    <button class="btn btn-light btn-sm" id="grid2"><i class="fas fa-list fs-6"></i></button>
                </div>
            </div>
        </div>
    </div>

    <hr class="hr-blurry">

    <div class="row w-100">
        <?php 
            foreach($doctors as $doctor){
               
        ?>

        <div class="<?php  echo (isset($_COOKIE["class"]))? $_COOKIE["class"] : 'col-3 mb-4'; ?>" id="cms_card">
            <a href="doctor_info.php?id=<?php echo $doctor["id"]; ?>" class="text-dark">
                <div class="card shadow-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-4">

                                <img class='img-fluid' src='uploads/<?php echo $doctor['img']; ?>' alt=''
                                    style='height: 100px;'>
                            </div>
                            <div class="col-8">
                                <small><?php echo $doctor["user_code"]; ?></small>
                                <h5 class="text-truncate">DR.<?php echo $doctor["name"]; ?></h5>
                                
                            </div>


                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php
            }
        ?>
    </div>
</div>


<?php
    include_once "./layouts/footer.php";
?>