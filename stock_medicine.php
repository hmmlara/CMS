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


$stockMedicineController = new MedicineController();

$mediStocks = $stockMedicineController->getStockMedicine();

// add id for show
for ($i = 1; $i <= count($mediStocks); $i++) {
    $mediStocks[$i - 1] += ["dis_id" => $i];
}

if(isset($_POST["search"])){
    $mediStocks = search_data($mediStocks,$_POST["search_val"]);
}


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
                <button type="submit" name="search" class="btn btn-sm btn-dark">Search</button>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-11 d-flex justify-content-between  mb-3">
            <a href="add_mediStocks.php" class="btn btn-sm btn-dark mx-2" id="add">Add Medicine Stocks</a>
        </div>
        <div class="col-1 me">
           <a href="medicine.php" class="text-dark text-decoration-underline">Back</a>
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
                    foreach($mediStocks as $stockMedicine)
                    {
                        echo "<tr>";
                        echo "<td>".$stockMedicine['dis_id']."</td>";
                        echo "<td>".$stockMedicine['name']."</td>";
                        echo "<td>".$stockMedicine['total_qty']."</td>";
                        // echo "</td>";
                        echo "<td><a class='btn btn-black' href='medi_stock_his.php?id=".$stockMedicine["medicine_id"]."'>
                                <i class='fa fa-history'></i>
                                </a></td>";
                        echo"</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

<?php

require_once __DIR__."/./layouts/footer.php";

?>
