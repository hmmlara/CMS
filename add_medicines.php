<?php

require_once "./layouts/header.php";

if(!$auth->isAuth()){
  header('location:login_form');
}

if($auth->hasRole() == 'doctor'){
  header('location:_403');
}


require_once "./controllers/MedicineController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";
require_once './controllers/MediCategoryController.php';
require_once './controllers/MediTypeController.php';

$addMedicineController = new MedicineController();
$mediCateController = new MediCategoryController();
$mediTypeController = new MediTypeController();

$medi_categories = $mediCateController->getMediCategory();
$medi_type = $mediTypeController->getMedicineType();

$error_msg = [];
if (isset($_POST["add"])) {
    //request for form data
    $request = new Request();
    $data = $request->getAll();
    unset($data["add"]);
    $validator = new Validator($data);

    //for error message

    if (!$validator->validated()) {
        $error_msg = $validator->getErrorMessages();
    } else {
        //clear error messages if validated is true
        $error_msg = [];

        echo '<pre>';
        var_dump($data);
        echo '</pre>';

        $result = $addMedicineController->addMedicine($data);
        // var_dump($result);
        if ($result) {
            header("location:medicine.php");
        }
    }
}

?>

<div class="container mt-5">
   
   <div class="row">
   <h5 class="mb-4">Add Medicine</h5>
   <div class="row">
        <div class="col-11 d-flex justify-content-between  mb-3">
        </div>
        <div class="col-1 me">
           <a href="medicine.php" class="text-dark text-decoration-underline">Back</a>
        </div>
    </div>
   <hr class="hr-blurry">

   <form method="post" class="col-12">
            <div class="form-group mb-3">
              <label for="formGroupExampleInput2">Medicine Name</label>
              <input type="text" name="name"  class="<?php echo (isset($error_msg["name"])) ? 'form-control border border-danger':'form-control';?>" value="<?php echo (!empty($data["name"])) ? $data["name"] : '';?>" id="formGroupExampleInput2" >

              <div>
                <?php
                    if(isset($error_msg["name"]))
                    {
                        echo "<small class='text-danger'>".$error_msg["name"]."</small>";
                    }
                ?>
              </div>
            </div>

            <div class="form-group mb-3">
              <label for="formGroupExampleInput">Category Name</label>
              <select name="category_id" id="" class="<?php echo (isset($error_msg["category_id"]))? 'form-control border border-danger' : 'form-control';?>">
                <option value="0" hidden selected >-</option>
                <?php
                  foreach($medi_categories as $medi_cate){
                ?>
                  <option value="<?php echo $medi_cate["id"]; ?>"><?php echo $medi_cate["category_name"] ?></option>
                <?php
                  }
                ?>  
              </select>
             
              <div>
                <?php
                    if(isset($error_msg["category_id"]))
                    {
                        echo "<small class='text-danger'>".$error_msg["category_id"]."</small>";
                    }
                ?>
              </div>
            </div>
            
            <div class="form-group mb-3">
              <label for="formGroupExampleInput">Medicine Type</label>
              <select name="type_id" id="" class="<?php echo (isset($error_msg["type_id"]))? 'form-control border border-danger' : 'form-control';?>">
                <option value="0" hidden selected>-</option>
                <?php
                  foreach($medi_type as $meditype){
                ?>
                  <option value="<?php echo $meditype["id"]; ?>"><?php echo $meditype["type"] ?></option>
                <?php
                  }
                ?>  
              </select>
             
              <div>
                <?php
                    if(isset($error_msg["type_id"]))
                    {
                        echo "<small class='text-danger'>".$error_msg["type_id"]."</small>";
                    }
                ?>
              </div>
            </div>

            <div class="form-group mb-3">
              <label for="formGroupExampleInput">Company</label>
              <input type="text"  name="company" id="formGroupExampleInput" class="<?php echo(isset($error_msg["company"])) ? 'form-control border border-danger':'form-control';?>" value="<?php echo (!empty($data["company"])) ? $data["company"] : '';?>">

              <div>
                <?php
                    if(isset($error_msg["company"]))
                    {
                        echo "<small class='text-danger'>".$error_msg["company"]."</small>";
                    }
                ?>
              </div>
            </div>

            <div class="form-group mb-3">
              <label for="formGroupExampleInput2">Brand</label>
              <input type="text"  name="brand" id="formGroupExampleInput2" class="<?php echo(isset($error_msg["brand"])) ? 'form-control border border-danger':'form-control';?>" value="<?php echo(!empty($data["brand"])) ? $data["brand"] : '';?>">

              <div>
                <?php
                    if(isset($error_msg["brand"]))
                    {
                        echo "<small class='text-danger'>".$error_msg["brand"]."</small>";
                    }
                ?>
              </div>
            </div>

            <div class="form-group mb-3">
              <label for="formGroupExampleInput2">Description</label>
              <input type="text" name="description"  class="<?php echo (isset($error_msg["description"])) ? 'form-control border border-danger':'form-control';?>" value="<?php echo (!empty($data["description"])) ? $data["description"] : '';?>" id="formGroupExampleInput2" >

              <div>
                <?php
                    if(isset($error_msg["description"]))
                    {
                        echo "<small class='text-danger'>".$error_msg["description"]."</small>";
                    }
                ?>
              </div>
            </div>
            
            <div class="row">
                <div class="col-11 d-flex justify-content-between  mb-3">
                </div>
                <div class="col-1 me">
                    <button type="submit" class="btn btn-dark " name="add">Add</button>
                </div>
            </div>

  </form>

   </div>
   

</div>

<?php
require_once "./layouts/footer.php";
?>