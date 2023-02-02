<?php
require_once "./layouts/header.php";
require_once "./controllers/MediCategoryController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";
require_once "./core/libraray.php";

$medicategoryController= new MediCategoryController();
$medicat=$medicategoryController->getMedicineCategory();

// add id for show
for ($i = 1; $i <= count($medicat); $i++) {
    $medicat[$i - 1] += ["display_id" => $i];
}


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
if(isset($_POST["search"])){
    $medicat = search_data($medicat,$_POST["search_val"]);
}
?>


<div class="container mt-5">
<div clss="row">
<div class="row">
    <div class="col-8">
        <h5 class="mb-4">Medicine Category</h5>
    </div>
    <div class="col-4 mb-3">
        <form action="" method="post">
            <div class="form-group d-flex float-right mb-3">
                <span class="mt-2">Search:&nbsp;</span>
                <input type="text" name="search_val" id="" class="form-control w-50 mx-3" placeholder="Enter CategoryName">
                <button type="submit" name="search" class="btn btn-sm btn-dark">Search</button>
            </div>
        </form>
    </div>

<div class="row">
        <div class="col-11 d-flex justify-content-between  mb-3">
            <form action="" method="post">
                <div class="form-group d-flex">                                         
                  <input type="text" placeholder="Add medicine type" name="category_name" id="" class="<?php echo(isset($error_msg["type"]))? 'form-control border border-danger':'form-control';?>" value="<?php echo(!empty($data['type'])) ? $data["type"] : '';?>">
                  <button type="submit" name="add" class="btn w-50 mx-3 btn-sm btn-dark">Add</button>
                </div>
            <div>
               <?php
               if(isset($error_msg["category_name"]))
               {
                   echo "<small class='text-danger'>".$error_msg["category_name"]."</small>";
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
                <th scope="col">Id</th>
                <th scope="col">Category_name</th>
                <th scope="col">Functions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($medicat as $medi_category)
                {
                    echo "<tr>";
                    echo "<td>".$medi_category["display_id"]."</td>";
                    echo "<td>".$medi_category["category_name"]."</td>";
                    echo "<td class='pe-3'>";
                    echo "<a href='' class='btn btn-black ml-2'><i class='fa fa-trash'></i></a>";
                    echo "</td>";
                    echo"</tr>";
                }
            ?>
        </tbody>
    </table>
    </div>
</div>

<?php
require_once './layouts/footer.php';
?>

