<?php

require_once "./layouts/header.php";

require_once "./controllers/MedicineController.php";

require_once "./core/Validator.php";

require_once "./core/Request.php";

$medicineController = new MedicineController();

$medicine = $medicineController->getMedicine();

// add id for show
for ($i = 1; $i <= count($medicine); $i++) {
    $medicine[$i - 1] += ["dis_id" => $i];
}

?>

<div class="container mt-5">
    <h2><b>Medicine</b></h2>
    <a href="add_medicines.php" class="btn btn-primary mb-3">Add new Medicine</a>

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
    // echo "<td class='pe-3'>";
    // echo "<a href='' class='btn btn-primary ml-2'><i class='fas fa-info-circle'></i></a>";
    // echo "<a href='' class='btn btn-warning ml-2'><i class='fas fa-edit'></i></a>";
    // echo "<a href='' class='btn btn-danger ml-2'><i class='fa fa-trash'></i></a>";
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