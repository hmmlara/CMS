<?php
ob_start();
require_once __DIR__."./layouts/header.php";
require_once "./controllers/MediTypeController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";

$addMediType = new MediTypeController();

$error_msg=[];
if(isset($_POST["add"]))
{
    //request for form data
    $request = new Request();
    $data = $request->getAll();
    unset($data["add"]);    
    $validator = new Validator($data);

    //for error message

    if(!$validator->validated())
    {
        $error_msg = $validator->getErrorMessages();
    }
    else
    {
        //clear error messages if validated is true
        $error_msg = [];

        $result = $addMediType->addMediType($data);

        if($result)
        {
            header("location:medi_type.php");
        }
    }
}
?>

<div class="rowcontainer">
    <div class="row">
    
        <div class="col-md-6">
            <a href="medi_type.php" class="btn btn-primary">Back to MediType</a>
        </div>
    
        <div class="card-title">
            <h3 class="text-center"><b>Add new Medicine_type</b></h3>
        </div>

            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Medicine Type</label>
                        <input type="text" name="type" id="" class="<?php echo(isset($error_msg["type"]))? 'form-control border border-danger':'form-control';?>" value="<?php echo(!empty($data['type'])) ? $data["type"] : '';?>">

                        <div>
                            <?php
                            if(isset($error_msg["type"]))
                            {
                                echo "<small class='text-danger'>".$error_msg["type"]."</small>";
                            }
                            ?>
                        </div>
                    </div>
                    <button type="submit" name="add" class="btn btn-success w-100">Add to Medicine_Type</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div>
    
<?php
require_once "layouts/footer.php";
?>
</div>