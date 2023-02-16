<?php

require_once "./layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor'){
    header('location:_403');
}

require_once "./controllers/MedicineController.php";

$medicineController = new MedicineController();

if(isset($_GET["id"])){
    $medicine = $medicineController->getDetails($_GET["id"]);
}



?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-4">Medicine Details</h5>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <a href="medicine" class="btn btn-sm btn-dark" id="add">Back</a>
        </div>

    </div>
    <hr class="hr-blurry">


    <div class="card p-4 w-50 mx-auto">
        <div class="card-title">
            <h3 class="text-center"><?php echo $medicine["name"]; ?></h3>
        </div>
        <div class="card-content text-center">
            <div class="row">
                <div class="col-4">
                    <h5 class="mb-3">Category</h5>
                    <h5 class="mb-3">Type</h5>
                    <h5 class="mb-3">Company</h5>
                    <h5 class="mb-3">Brand</h5>
                    <h5 class="mb-3">Description</h5>
                </div>
                <div class="col-4">
                    <h5 class="mb-3">:</h5>
                    <h5 class="mb-3">:</h5>
                    <h5 class="mb-3">:</h5>
                    <h5 class="mb-3">:</h5>
                    <h5 class="mb-3">:</h5>
                </div>
                <div class="col-4">
                    <h5 class="mb-3"><?php echo $medicine["category_name"];?></h5>
                    <h5 class="mb-3"><?php echo $medicine["type"];?></h5>
                    <h5 class="mb-3"><?php echo $medicine["company"]; ?></h5>
                    <h5 class="mb-3"><?php echo $medicine["brand"]; ?></h5>
                    <h5 class="mb-3"><?php echo $medicine["description"]; ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

require_once __DIR__ . "/./layouts/footer.php";

?>