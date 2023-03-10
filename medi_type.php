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
require_once "./core/Paginator.php";


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

// add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;

$per_page = 7;
$num_of_pages = ceil(count($meditype) / $per_page);
$pagi_meditypes = Pagination::paginator($pages, $meditype, $per_page);

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
                        <button type="submit" name="search" class="btn btn-sm btn-success">Search</button>
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
                            <button type="submit" name="add" class="btn w-50 mx-3 btn-sm btn-success">Add</button>
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
                    <a href="medicine.php" class="btn btn-sm btn-success mb-3">Back</a>
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
                        foreach($pagi_meditypes as $medi_type)
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
         <!-- pagination -->
         <?php 
                // pagi page
                $server_page = $_SERVER["PHP_SELF"];
                $pre_page = ($server_page . '?pages=' . ($pages - 1));
            ?>
            <nav aria-label="Page navigation example mx-auto">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($pages == 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo ($pages == 2) ? 'medi_type' : $pre_page; ?>"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <?php
                        $ellipse = false;
                        $ends = 1;
                        $middle = 2;
                        
                        for ($page = 1; $page <= $num_of_pages; $page++) {
                    ?>
                    <?php
                        if($page == $pages){
                            $ellipse = true;
                    ?>
                    <li class='page-item active'>
                        <a class='page-link'
                            href='<?php echo ($page - 1 < 1) ? 'medi_type' : $server_page . "?pages=" . $page; ?>'>
                            <?php echo $page; ?>
                        </a>
                    </li>
                    <?php
                                    }
                                    else{
                                // condition for ... in pagination
                                    if ($page <= $ends || ($pages && $page >= $pages - $middle && $page <= $pages + $middle) || $page > $num_of_pages - $ends) { 
                    ?>
                    <li class='page-item'>
                        <a class='page-link'
                            href='<?php echo ($page - 1 < 1) ? 'medi_type' : $server_page . "?pages=" . $page; ?>'>
                            <?php echo $page; ?>
                        </a>
                    </li>
                    <?php
                                    $ellipse = true;
                                }
                                    elseif($ellipse){
                    ?>
                    <li class='page-item'>
                        <a class='page-link'>&hellip;</a>
                    </li>
                    <?php
                                    $ellipse = false;
                                    }
                                }
                    ?>
                    <?php
                                }
                    ?>
                    <li class="page-item <?php echo ($pages == $num_of_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $server_page; ?>?pages=<?php echo $pages + 1; ?>"
                            aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- pagination -->
    </div>
</div>

    <?php
require_once './layouts/footer.php';
?>