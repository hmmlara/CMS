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


$medicineController = new MedicineController();

$medicine = $medicineController->getMedicine();
$medicineExpire=$medicineController->getMediExpired();

// add id for show
for ($i = 1; $i <= count($medicine); $i++) {
    $medicine[$i - 1] += ["dis_id" => $i];
}

if(isset($_POST["search"])){
    if(!empty($_POST["search_val"])){
        $medicine = search_data($medicine,$_POST["search_val"]);
    }
}

// add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;

$per_page = 7;
$num_of_pages = ceil(count($medicine) / $per_page);
$pagi_medicines = Pagination::paginator($pages, $medicine, $per_page);

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
                    <input type="text" name="search_val" id="" class="form-control w-50 mx-3"
                        placeholder="Enter Medi_Name">
                    <button type="submit" name="search" class="btn btn-sm btn-success">Search</button>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-start  mb-3">
                <a href="add_medicines.php" class="btn btn-sm btn-success mx-2" id="add">Add Medicine</a>
                <a href="medi_type.php" class="btn btn-sm btn-success mx-2" id="add">Medi_type</a>
                <a href="medi_category.php" class="btn btn-sm btn-success mx-2" id="add">Category</a>
                <a href="stock_medicine.php" class="btn btn-sm btn-success mx-2" id="add">Medicine Stocks</a>
            </div>
        </div>
        <hr class="hr-blurry">
    </div>

    <table class="table text-center">
        <thead class="table-dark">
            <th>Id</th>
            <th>Medicine_Name</th>
            <th>Category_Name</th>
            <th>Medicine_Type</th>
            <th>Details</th>
            <!-- <th>Company</th>
            <th>Brand</th>
            <th>Description</th> -->
            <!-- <th>Functions</th> -->
        </thead>

        <tbody>
            <?php
        foreach ($pagi_medicines as $allmedicine) {
            echo "<tr>";
            echo "<td>" . $allmedicine['dis_id'] . "</td>";
            echo "<td>" . $allmedicine['name'] . "</td>";
            echo "<td>" . $allmedicine['category_name'] . "</td>";
            echo "<td>" . $allmedicine['type'] . "</td>";
            echo "<td><a href='medi_details?id=".$allmedicine['id']."' class='btn btn-info'><i class='fas fa-info'></i></a></td>";
            echo "</tr>";
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
                    <a class="page-link" href="<?php echo ($pages == 2) ? 'medicine' : $pre_page; ?>"
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
                        href='<?php echo ($page - 1 < 1) ? 'medicine' : $server_page . "?pages=" . $page; ?>'>
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
                        href='<?php echo ($page - 1 < 1) ? 'medicine' : $server_page . "?pages=" . $page; ?>'>
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

require_once __DIR__ . "/./layouts/footer.php";

?>