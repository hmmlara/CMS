<?php
require_once "./layouts/header.php";
require_once "./controllers/MediCategoryController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";

$medicategoryController= new MediCategoryController();
$medicat=$medicategoryController->getMedicineCategory();

// add id for show
for ($i = 1; $i <= count($medicat); $i++) {
    $medicat[$i - 1] += ["display_id" => $i];
}

?>

<div class="container d-flex justify-content-between">
  <h2><b>Medicine Category</b></h2>
  <a href="addMedicineCategory.php" class="btn btn-primary">Add new Category</a>
</div>
<div class="col-6">
    <div class="col-12">
    <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Category_Name</th>
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
        echo "<a href='' class='btn btn-primary ml-2'><i class='fas fa-info-circle'></i></a>";
        echo "<a href='' class='btn btn-warning ml-2'><i class='fas fa-edit'></i></a>";
        echo "<a href='' class='btn btn-danger ml-2'><i class='fa fa-trash'></i></a>";
        echo "</td>";
        echo"</tr>";
    }
    ?>
  </tbody>
</table>
    </div>
</div>

<div>
<?php
require_once "./layouts/footer.php";
?>
</div>
