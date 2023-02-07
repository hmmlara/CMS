<?php

ob_start();
require_once "./layouts/header.php";
require_once "./controllers/MedicineController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";

$editMediStockController= new MedicineController();
// var_dump($editMediStockController);
if(isset($_GET["id"]))
{
    $medistock = $editMediStockController ->getById($_GET["id"]);
}
$error_msg=[];
if(isset($_POST["update"]))
{
    //request for form data
    $request = new Request();
    $data = $request->getAll();
    unset($data["update"]);    
    $validator = new Validator($data);

    // replace type
    $medistock["name"] = $data["name"];


    //for error message

    if(!$validator->validated())
    {
        $error_msg = $validator->getErrorMessages();
    }
    else
    {
        //clear error messages if validated is true
        $error_msg = [];

        $result =$editMediStockController->editMedicineStock($medistock);

        if($result)
        {
            header("location:stock_medicine.php");
        }
    }
}

?>

<div class="container mt-5">
    <div class="row">
        <div class="row">
            <div class="col-8">
                <h5 class="mb-4">Edit Medicine Stock </h5>
            </div>
            <div class="col-4" mb-3>
            </div>
            <div class="row">
                <div class="col-11 d-flex justify-content-between mb-3">
                </div>
                <div class="col-1 me">
                    <a href="stock_his.php" class="text-dark text-decoration-underline">Back</a>
                </div>
            </div>

            <hr class="hr-blurry">
        </div>
        <div class="row">
    <div class="col-md-6">
        <form action="" method="post">
            <div class="mt-3">
                <label for="" class="form-label">Medicine Name</label>
                <?php
                if (!isset($data["name"])) {
                ?>
                    <input type="text" name="name" id="" class="form-control" value="<?php echo $medistock["name"]; ?>">
                <?php
                } else {
                ?>
                    <input type="text" name="name" id="" class="<?php echo (isset($error_msg["name"])) ? 'form-control  border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["name"])) ? $medistock["name"] : ''; ?>">
                <?php
                }
                if (isset($error_msg["name"])) {
                    echo "<small class='text-danger'>" . $error_msg["name"] . "</small>";
                }
                ?>
            </div>
            <div class="mt-3">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
    </div>
</div>