<?php

include_once "./layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor'){
    header('location:_403');
}

// after auth
require_once "./controllers/PatientController.php";
require_once "./core/libraray.php";
require_once "./core/Paginator.php";

$patientController = new PatientController();

$patients = $patientController->getPatients();

// reverse array
$patients = array_reverse($patients);

// add id for show
for ($i = 1; $i <= count($patients); $i++) {
    $patients[$i - 1] += ["display_id" => $i];
}

if (isset($_POST["search"])) {
    if (!empty($_POST["search_val"])) {
        $patients = search_data($patients, $_POST["search_val"]);
    }
}
// add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;

$per_page = 4;
$num_of_pages = ceil(count($patients) / $per_page);
$pagi_patients = Pagination::paginator($pages, $patients, $per_page);

?>

<!-- modal -->
<div class="container mt-3">
    <div class="row">
        <div class="col-8">
            <h5 class="mb-4">Patients</h5>
            <a href="add_patient" class="btn btn-sm btn-success" id="add">Add</a>
        </div>
        <div class="col-4 mb-3">
            <form action="" method="post">
                <div class="form-group d-flex float-right mb-3">
                    <span class="mt-2">Search:&nbsp;</span>
                    <input type="text" name="search_val" id="" class="form-control w-50 mx-3"
                        placeholder="Enter Patient Code">
                    <button type="submit" name="search" class="btn btn-sm btn-success">Search</button>
                </div>
            </form>

            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <button class="btn btn-light btn-sm mx-2" id="grid1"><i class="fas fa-th fs-6"></i></button>
                    <button class="btn btn-light btn-sm" id="grid2"><i class="fas fa-list fs-6"></i></button>
                </div>
            </div>
        </div>
    </div>

    <hr class="hr-blurry">

    <table class="table table-light table-collapse text-center">
        <thead>
            <th>Id</th>
            <th>Patient Code</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Function</th>
        </thead>
        <tbody id="patient_table">
            <?php
                foreach ($pagi_patients as $patient) {
            ?>
            <tr>
                <td><?php echo $patient["display_id"]; ?></td>
                <td><?php echo $patient["pr_code"];?></td>
                <td><?php echo $patient["name"]; ?></td>
                <td><?php echo $patient["phone"]; ?></td>
                <td id="<?php echo $patient["id"];?>">
                    <a href="patient_info?id=<?php echo $patient["id"]; ?>" class="btn btn-sm btn-primary"><i class="fas fa-info-circle"></i></a>
                    <a href="patient_edit?id=<?php echo $patient["id"]; ?>" class="btn btn-sm btn-info"><i
                            class="fas fa-pen"></i></a>
                    <button class="btn btn-sm btn-danger delete"><i class="fas fa-trash"></i></button>
                </td>
            </tr>

            <?php
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
                <a class="page-link"
                    href="<?php echo ($pages == 2) ? 'all_patients' : $pre_page; ?>"
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
                                    href='<?php echo ($page - 1 < 1) ? 'all_patients' : $server_page . "?pages=" . $page; ?>'>
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
                                        href='<?php echo ($page - 1 < 1) ? 'all_patients' : $server_page . "?pages=" . $page; ?>'>
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
include_once "./layouts/footer.php";
?>