<?php

ob_start();
require_once "layouts/header.php";

require_once "controllers/MediTypeController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";

$meditypeController= new MediTypeController();
$meditype = $meditypeController->getMedicineType();

foreach(range(1,count($meditype))as $index)
{
    $meditype[$index-1]+=["display_id"=>$index];
}


?>
<div class="container d-flex justify-content-between">
    <h2><b>Medicine_Type</b></h2>
    <a href="addMediType.php" class="btn btn-primary">Add new MedicineType</a>
</div>
        
<div class="col-6">
    <div class="col-12">
        <table class="table text-center">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Type</th>
            <th scope="col">Functions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($meditype as $medi_type)
        {
            echo "<tr>";
            echo "<td>".$medi_type["display_id"]."</td>";
            echo "<td>".$medi_type["type"]."</td>";            
            echo "<td class='pe-3'>";
            echo "<a href='' class='btn btn-primary ml-2'><i class='fas fa-info-circle'></i></a>";
            echo "<a href='editMeditype.php?id=".$medi_type["id"]."' class='btn btn-warning ml-2'><i class='fas fa-edit'></i></a>";
            echo "<a href='' class='btn btn-danger ml-2'><i class='fa fa-trash'></i></a>";
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