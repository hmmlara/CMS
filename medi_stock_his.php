<?php

require_once "./layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor' || $auth->hasRole() == 'reception'){
    header('location:_403');
}


require_once "./controllers/MedicineController.php";

require_once "./core/Validator.php";

require_once "./core/Request.php";

$stockMedicineController = new MedicineController();

$mediStocks = $stockMedicineController->getStockHistory($_GET["id"]);
// var_dump($mediStocks);

// add id for show
for ($i = 1; $i <= count($mediStocks); $i++) {
    $mediStocks[$i - 1] += ["dis_id" => $i];
}


?>

<div class="container mt-5">
<div clss="row">
<div class="row">
    <div class="col-8">
        <h5 class="mb-4">Stock Medicine History</h5>
    </div>
    <div class="col-4 mb-3">
    </div>

    <div class="row">
        <div class="col-11 d-flex justify-content-between  mb-3">
        </div>
        <div class="col-1 me">
           <a href="stock_medicine.php" class="btn btn-sm btn-success mb-3">Back</a>
        </div>
    </div>

    <hr class="hr-blurry">
</div>  

<div class="col-12 d-flex">

    <table class="table text-center">
        <thead class="table-dark">
            <tr>
                <th>Id</th>
                <th>Medicine_Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Man_Date</th>
                <th>Exp Date</th>
                <th>Enter Date</th>
                <th>Functions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    foreach($mediStocks as $stockMedicine)
                    {
                        
                        echo "<tr>";
                        echo "<td>".$stockMedicine['dis_id']."</td>";
                        echo "<td class='text-truncate'  style='max-width: 150px;'>".$stockMedicine['name']."</td>";
                        echo "<td>".$stockMedicine['qty']."</td>";
                        echo "<td>".$stockMedicine["price"]."</td>";
                        echo "<td>".$stockMedicine["man_date"]."</td>";

                        //Expired date
                        $expiry_date=strtotime($stockMedicine['exp_date']);
                        
                        $current_date=strtotime(date('Y-m-d'));

                        $difference=($expiry_date - $current_date)/(60 * 60 *24 );

                    
                        if($difference<=30 && $difference>=0){
                            echo "<td class='bg-warning'>".$stockMedicine["exp_date"]."</td>";
                            

                        }else{
                            echo "<td>".$stockMedicine["exp_date"]."</td>";
                        }

                        
                        
                        echo "<td>".$stockMedicine["enter_date"]."</td>";
                        echo "<td class='pe-3'>";
                        echo "<a href='edit_medistock_his?id=".$stockMedicine["id"]."&medi_id=".$stockMedicine["medicine_id"]."' class='btn btn-success mx-2'><i class='fas fa-edit'></i></a>";
                        echo "<a href='' class='btn btn-success mx-2'><i class='fa fa-trash'></i></a>";
                        echo "</td>";
                        echo"</tr>";
                    }
            ?>
        </tbody>
    </table>
    </div>
</div>

<?php

require_once __DIR__."/./layouts/footer.php";

?>
