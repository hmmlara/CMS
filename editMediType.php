<?php

ob_start();
require_once "./layouts/header.php";
require_once "controllers/MediTypeController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";

$editMeditypeController= new MediTypeController();
//$result=$editMeditypeController->editMedicineType($data);
if(isset($_GET["id"]))
{
    $meditype = $editMeditypeController ->getById($_GET["id"]);
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
    $meditype["type"] = $data["type"];


    //for error message

    if(!$validator->validated())
    {
        $error_msg = $validator->getErrorMessages();
    }
    else
    {
        //clear error messages if validated is true
        $error_msg = [];

        $result =$editMeditypeController->editMedicineType($meditype);

        if($result)
        {
            header("location:medi_type.php");
        }
    }
}


?>

<div class="container d-flex justify-content-between">
    <h2><b>Edit Medicine_Type</b></h2>
    <a href="medi_type.php" class="btn btn-primary">Back to Medicine_Type</a>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="" method="post">
            <div class="mt-3">
                <label for="" class="form-label">Type</label>
                <?php
                if (!isset($data["type"])) {
                ?>
                    <input type="text" name="type" id="" class="form-control" value="<?php echo $meditype["type"]; ?>">
                <?php
                } else {
                ?>
                    <input type="text" name="type" id="" class="<?php echo (isset($error_msg["type"])) ? 'form-control  border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["type"])) ? $meditype["type"] : ''; ?>">
                <?php
                }
                if (isset($error_msg["type"])) {
                    echo "<small class='text-danger'>" . $error_msg["type"] . "</small>";
                }
                ?>
            </div>
            <div class="mt-3">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
<?php
require_once "./layouts/footer.php";
?>