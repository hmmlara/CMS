<?php

require_once __DIR__."./layouts/header.php";

require_once "./controllers/MedicineController.php";

require_once "./core/Validator.php";

require_once "./core/Request.php";

$stockMedicineController = new MedicineController();

$mediStocks = $stockMedicineController->getStockHistory($_GET["id"]);

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
                    <a href="stock_medicine.php" class="text-dark text-decoration-underline">Back</a>
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
                        echo "<td>".$stockMedicine['name']."</td>";
                        echo "<td>".$stockMedicine['qty']."</td>";
                        echo "<td>".$stockMedicine["price"]."</td>";
                        echo "<td>".$stockMedicine["man_date"]."</td>";
                        echo "<td>".$stockMedicine["exp_date"]."</td>";
                        echo "<td>".$stockMedicine["enter_date"]."</td>";
                        echo "<td class='pe-3'>";
                        echo "<a href='edit_medistock_his?id=".$stockMedicine["id"]."' class='btn btn-warning mx-2'><i class='fas fa-edit'></i></a>";
                        // echo "<a href='' class='btn btn-dark mx-2'><i class='fa fa-trash'></i></a>";
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