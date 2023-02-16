<?php

require_once "layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor' || $auth->hasRole() == 'reception'){
    header('location:_403');
}


require_once "controllers/MediTypeController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";
require_once "./core/libraray.php";


$meditypeController= new MediTypeController();
$meditype = $meditypeController->getMedicineType();

if(count($meditype) > 0){
    foreach(range(1,count($meditype))as $index)
{
    $meditype[$index-1]+=["display_id"=>$index];
}
}

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

if(isset($_POST["search"])){
    $meditype = search_data($meditype,$_POST["search_val"]);
}
?>

<div class="container mt-5">
    <div clss="row">
        <div class="row">
            <div class="col-8">
                <h5 class="mb-4">Medicine Type</h5>
            </div>
            <div class="col-4 mb-3">
                <form action="" method="post">
                    <div class="form-group d-flex float-right mb-3">
                        <span class="mt-2">Search:&nbsp;</span>
                        <input type="text" name="search_val" id="" class="form-control w-50 mx-3"
                            placeholder="Enter Type_name">
                        <button type="submit" name="search" class="btn btn-sm btn-dark">Search</button>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-11 d-flex justify-content-between  mb-3">
                    <form action="" method="post">
                        <div class="form-group d-flex">
                            <input type="text" placeholder="Add medicine type" name="type" id=""
                                class="<?php echo(isset($error_msg["type"]))? 'form-control border border-danger':'form-control';?>"
                                value="<?php echo(!empty($data['type'])) ? $data["type"] : '';?>">
                            <button type="submit" name="add" class="btn w-50 mx-3 btn-sm btn-dark">Add</button>
                        </div>
                        <div>
                            <?php
                           if(isset($error_msg["type"]))
                           {
                               echo "<small class='text-danger'>".$error_msg["type"]."</small>";
                           }
                           ?>
                        </div>
                    </form>
                </div>
                <div class="col-1 me">
                    <a href="medicine.php" class="text-dark text-decoration-underline">Back</a>
                </div>
            </div>

            <hr class="hr-blurry">
        </div>

        <div class="col-12 d-flex">

            <table class="table text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Type</th>
                        <th>Functions</th>
                    </tr>
                </thead>
                <tbody id="type_table">
                    <?php
            foreach($meditype as $medi_type)
            {
                echo "<tr>";
                echo "<td>".$medi_type["display_id"]."</td>";
                echo "<td>".$medi_type["type"]."</td>";            
                echo "<td class='pe-3'>";
                echo "<button class='btn btn-danger ml-2 delete' id='".$medi_type["id"]."'><i class='fa fa-trash'></i></button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
require_once './layouts/footer.php';
?>