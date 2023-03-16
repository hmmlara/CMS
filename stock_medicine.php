<?php

require_once "./layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor'){
    header('location:_403');
}

require_once "./controllers/MedicineController.php";

require_once "./core/Validator.php";

require_once "./core/Request.php";

require_once "./core/libraray.php";

require_once "./core/Paginator.php";


$stockMedicineController = new MedicineController();

$mediStocks = $stockMedicineController->getStockMedicine();
$expMed = $stockMedicineController->getMediExpired();

// add id for show
for ($i = 1; $i <= count($mediStocks); $i++) {
    $mediStocks[$i - 1] += ["dis_id" => $i];
}

if(isset($_POST["search"])){
    $mediStocks = search_data($mediStocks,$_POST["search_val"]);
}

if(!isset($_SESSION['exp_medi_ids'])){
    $_SESSION['exp_medi_ids'] = [];

    foreach($expMed as $exp_id){
        array_push($_SESSION['exp_medi_ids'],$exp_id['id']);
    }
}

// add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;

$per_page = 7;
$num_of_pages = ceil(count($mediStocks) / $per_page);
$pagi_mediStocks = Pagination::paginator($pages, $mediStocks, $per_page);

// var_dump($expMed);
?>

<div class="container mt-5">
<div clss="row">
<div class="row">
    <div class="col-8">
        <h5 class="mb-4">Stock Medicine</h5>
    </div>
    <div class="col-4 mb-3">
        <form action="" method="post">
            <div class="form-group d-flex float-right mb-3">
                <span class="mt-2">Search:&nbsp;</span>
                <input type="text" name="search_val" id="" class="form-control w-50 mx-3" placeholder="Enter Medi_name">
                <button type="submit" name="search" class="btn btn-sm btn-success">Search</button>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-11 d-flex justify-content-between  mb-3">
            <a href="add_mediStocks.php" class="btn btn-sm btn-success mx-2" id="add">Add Medicine Stocks</a>
        </div>
        <div class="col-1 me">
           <a href="medicine.php" class="btn btn-sm btn-success">Back</a>
        </div>
    </div>

    <hr class="hr-blurry">
</div>



<div class="container">
        <table class="table text-center">
            <thead class="table-dark">
                <th>Id</th>
                <th>Medicine_Name</th>
                <th>Quantity</th>
                <th>Function</th>
                <!-- <th>Functions</th> -->
            </thead>

            <tbody>
                <?php
                    foreach($pagi_mediStocks as $stockMedicine)
                    {
                        echo "<tr>";
                        echo "<td>".$stockMedicine['dis_id']."</td>";
                        if(in_array($stockMedicine['medicine_id'],$_SESSION['exp_medi_ids'])){
                            echo "<td class='bg-secondary'>".$stockMedicine['name']."</td>";
                        }
                        else{
                            echo "<td>".$stockMedicine['name']."</td>";
                        }
                        echo "<td>".$stockMedicine['total_qty']."</td>";
                        // echo "</td>";
                        echo "<td><a class='btn btn-success' href='medi_stock_his.php?id=".$stockMedicine["medicine_id"]."'>
                                <i class='fa fa-history'></i>
                                </a></td>";
                        echo"</tr>";
                    }
                ?>
            </tbody>
        </table>

         <!-- pagination -->
         <?php 
                // pagi page
                $server_page = $_SERVER["PHP_SELF"];
                $pre_page = ($server_page . '?pages=' . ($pages - 1));
            ?>
            <nav aria-label="Page navigation example mx-auto">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($pages == 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo ($pages == 2) ? 'stock_medicine' : $pre_page; ?>"
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
                            href='<?php echo ($page - 1 < 1) ? 'stock_medicine' : $server_page . "?pages=" . $page; ?>'>
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
                            href='<?php echo ($page - 1 < 1) ? 'stock_medicine' : $server_page . "?pages=" . $page; ?>'>
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

<?php

require_once __DIR__."/./layouts/footer.php";

?>
