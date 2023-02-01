<?php
ob_start();
require_once __DIR__."./layouts/header.php";
require_once "./controllers/MediCategoryController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";

$addMediCategory = new MediCategoryController();

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

        $result = $addMediCategory->addCategory($data);

        if($result)
        {
            header("location:medi_category.php");
        }
    }
}
?>

<div class="container">
    <div class="row">
    
        <div class="col-md-6">
            <a href="medi_category.php" class="btn btn-primary">Back to Category</a>
        </div>
    
        <div class="card-title">
            <h3 class="text-center"><b>Add new Category_name</b></h3>
        </div>

            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Medicine Category</label>
                        <input type="text" name="category_name" id="" class="<?php echo(isset($error_msg["category_name"]))? 'form-control border border-danger':'form-control';?>" value="<?php echo(!empty($data['category_name'])) ? $data["category_name"] : '';?>">

                        <div>
                            <?php
                            if(isset($error_msg["category_name"]))
                            {
                                echo "<small class='text-danger'>".$error_msg["category_name"]."</small>";
                            }
                            ?>
                        </div>
                    </div>
                    <button type="submit" name="add" class="btn btn-success w-100">Add to Category</button>
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
