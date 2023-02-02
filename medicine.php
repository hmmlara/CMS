<?php

require_once "./layouts/header.php";

require_once "./controllers/MedicineController.php";

require_once "./core/Validator.php";

require_once "./core/Request.php";

require_once "./core/libraray.php";


$medicineController = new MedicineController();

$medicine = $medicineController->getMedicine();

// add id for show
for ($i = 1; $i <= count($medicine); $i++) {
    $medicine[$i - 1] += ["dis_id" => $i];
}

if(isset($_POST["search"])){
    $medicine = search_data($medicine,$_POST["search_val"]);
}

?>

<div class="container mt-5">
<div class="row">
    <div class="col-8">
        <h5 class="mb-4">Medicines</h5>
    </div>
    <div class="col-4 mb-3">
        <form action="" method="post">
            <div class="form-group d-flex float-right mb-3">
                <span class="mt-2">Search:&nbsp;</span>
                <input type="text" name="search_val" id="" class="form-control w-50 mx-3" placeholder="Enter Medi_Name">
                <button type="submit" name="search" class="btn btn-sm btn-dark">Search</button>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-12 d-flex justify-content-start  mb-3">
            <a href="add_medicines.php" class="btn btn-sm btn-dark mx-2" id="add">Add Medicine</a>
            <a href="medi_type.php" class="btn btn-sm btn-dark mx-2" id="add">Medi_type</a>
            <a href="medi_category.php" class="btn btn-sm btn-dark mx-2" id="add">Category</a>
            <a href="stock_medicine.php" class="btn btn-sm btn-dark mx-2" id="add">Medicine Stocks</a>
        </div>
    </div>
    <hr class="hr-blurry">
</div>
    

    <table class="table">
        <thead class="table-dark">
            <th>Id</th>
            <th>Medicine_Name</th>
            <th>Category_Name</th>
            <th>Medicine_Type</th>
            <th>Company</th>
            <th>Brand</th>
            <th>Description</th>
            <!-- <th>Functions</th> -->
        </thead>

        <tbody>
        <?php
        foreach ($medicine as $allmedicine) {
            echo "<tr>";
            echo "<td>" . $allmedicine['dis_id'] . "</td>";
            echo "<td>" . $allmedicine['name'] . "</td>";
            echo "<td>" . $allmedicine['category_name'] . "</td>";
            echo "<td>" . $allmedicine['type'] . "</td>";
            echo "<td>" . $allmedicine['company'] . "</td>";
            echo "<td>" . $allmedicine['brand'] . "</td>";
            echo "<td>" . $allmedicine['description'] . "</td>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<?php

require_once __DIR__ . "/./layouts/footer.php";

?>