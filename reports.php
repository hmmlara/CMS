<?php
include_once './layouts/header.php';
require_once './core/libraray.php';
require_once './core/Paginator.php';
require_once './core/Validator.php';

include_once './controllers/ReportController.php';
include_once './controllers/MediTypeController.php';
include_once './controllers/MedicineController.php';

$reportController = new ReportController();
$mediTypeController = new MediTypeController();
$medicineController = new MedicineController();

$mediTypes = $mediTypeController->getMedicineType();
$medicines = $medicineController->getAllMedicine();
//  var_dump($medicines);

$month = date_format(date_create(date('Y-m-d')), 'M');
// var_dump($month);
$reportDaily = $reportController->getDailyQuantity();
//  var_dump(count($reportDaily));
$curr_month_num = sprintf('%d', date('m', time()));
$curr_month = date('F', mktime(0, 0, 0, $curr_month_num, 10));

//filter for this month of this year8
//  $reportDaily = array_values(array_filter($reportDaily,function($value){
//     return $value['year'] == date('Y') && $value['month'] == date_format(date_create(date('Y-m-d')),'F');
//  }));

//  echo "<pre>";
//     var_dump();
//     var_dump($reportDaily);
//  echo "</pre>";

if (count($reportDaily) > 0) {
    foreach (range(1, count($reportDaily)) as $index) {
        $reportDaily[$index - 1] += ['dis_id' => $index];
    }
}
//  var_dump($reportDaily);

// search appointments
if (isset($_POST['search_report'])) {

    unset($_SESSION['search_data']);
    // condition for searching with Month
    if (count($reportDaily) > 0) {
        if (!empty($_POST['relatedMonth']) && (empty($_POST['date_start']) && empty($_POST['date_end']))) {
            $reportDaily = search_data($reportDaily, $_POST['relatedMonth']);
        }

        // condition for searching with start date and end date
        if (!empty($_POST['date_start']) && !empty($_POST['date_end'])) {
            $start = $_POST['date_start'];
            $end = $_POST['date_end'];
            $reportDaily = search_date_between($reportDaily, $start, $end);
        }
    }
    if (!isset($_SESSION['search_data'])) {
        $_SESSION['search_data'] = $reportDaily;
        $_SESSION['treat_year'] = explode('-',$_POST['date_start'])[0];
        $_SESSION['treat_month'] = date('F',strtotime(explode('-',$_POST['date_start'])[1]));
    }
    header('location:' . $_SERVER['PHP_SELF']);
} else {
    $reportDaily = array_values(array_filter($reportDaily, function ($value) {
        return date('Y', strtotime($value['treatment_date'])) == date('Y');
    }));
}

if (isset($_POST['reset'])) {
    unset($_SESSION['search_data']);
    unset($_SESSION['treat_year']);
    unset($_SESSION['treat_month']);
    header('location:' . $_SERVER["PHP_SELF"]);
}

if (isset($_SESSION['search_data'])) {
    $reportDaily = $_SESSION['search_data'];
}


// csv download
// if(isset($_POST['treat_download_csv'])){
//     // echo 'hello';
//     if(isset($_SESSION['search_data'])){
//         $fields = ['id','Daily','Treament quantity','Income List'];
//         $filename = 'treatment_list('.$_SESSION['treat_year'].'-'.$_SESSION['treat_month'].').csv';
//         $file = fopen('php://output','w');
//         fputcsv($file,$fields,',');

//         $data = $_SESSION['search_data'];
//         for($i = 1; $i < count($data); $i++){
//             $row_data = array($i,date('d/M/Y', strtotime($data[0]['treatment_date'],$data[0]['treatments'],$data['income'])));
//             fputcsv($file,$row_data,',');
//         }

//         fclose($file);
//         header('Content-Type: application/csv;charset=UTF-8'); 
//         header('Content-Disposition: attachment; filename="'.$filename.'";');
//         exit();
//     }
//     else{
//         echo "<script>alert('Please search data')</script>";
//     }
// }

// search appointments

// medicine

$medi_report = [];

if (isset($_POST['search_medi'])) {

    unset($_POST['search_medi']);
    unset($_SESSION['medi_name']);
    unset($_SESSION['search_medis']);

    $validator = new Validator($_POST);

    if ($validator->validated()) {
        $medi_rp = [
            'medicine_id' => $_POST['medicine_id'],
            'start_date' => $_POST['date_start'],
            'end_date' => $_POST['date_end'],
        ];

        $medi_report = $reportController->getDailyMedicine($medi_rp);

        if (!isset($_SESSION['search_medis'])) {
            $_SESSION['search_medis'] = $medi_report;
        }
        if(!isset($_SESSION['medi_year'])){
            $_SESSION['medi_year'] = explode('-',$_POST['date_start'])[0];
        }
        if(!isset($_SESSION['medi_month'])){
            $_SESSION['medi_month'] = date('F',strtotime(explode('-',$_POST['date_start'])[1]));
        }

        $_SESSION['medi_name'] = array_values(array_filter($medicines, function ($value) {
            if ($value['id'] == $_POST['medicine_id']) {
                return $value['name'];
            }
        }))[0]['name'];
    }
}

if (isset($_POST['reset_medi'])) {
    unset($_SESSION['medi_name']);
    unset($_SESSION['search_medis']);
    unset($_SESSION['medi_year']);
    unset($_SESSION['medi_month']);
}

if (isset($_SESSION['search_medis'])) {
    $medi_report = $_SESSION['search_medis'];
}
if (count($medi_report) > 0) {
    foreach (range(1, count($medi_report)) as $index) {
        $medi_report[$index - 1] += ['display_id' => $index];
    }
}

// medicine

// add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;

$per_page = 5;
$num_of_pages = ceil(count($reportDaily) / $per_page);
$pagi_reportDaily = Pagination::paginator($pages, $reportDaily, $per_page);

// medicines
$num_of_medi_pages = ceil(count($medi_report) / $per_page);
$pagi_mediRp = Pagination::paginator($pages, $medi_report, $per_page);

?>

<script>
// localStorage.removeItem('lastTab');
$(document).ready(function() {
    $('a[data-mdb-toggle="tab"]').on('shown.bs.tab', function(e) {
        localStorage.setItem('lastTab', $(this).attr('href'));
        // console.log(localStorage.getItem('lastTab'));
    });
    var lastTab = localStorage.getItem('lastTab');

    if (lastTab) {
        $('[href="' + lastTab + '"]').tab('show');
    }
    // localStorage.removeItem('lastTab');
});
</script>

<div class="container-fluid mt-3">

    <h3><em>Reports</em></h3>


    <!-- Tabs navs -->
    <ul class="nav nav-tabs nav-fill mb-3 bg-white" id="ex1" role="tablist">
        <li class="nav-item" role="presentation" style="color:orange">
            <a class="nav-link active" id="ex2-tab-1" data-mdb-toggle="tab" href="#ex2-tabs-1" role="tab"
                aria-controls="ex2-tabs-1" aria-selected="true">Treatment List</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ex2-tab-3" data-mdb-toggle="tab" href="#ex2-tabs-3" role="tab"
                aria-controls="ex2-tabs-3" aria-selected="false">Medicine </a>
        </li>
    </ul>
    <!-- Tabs navs -->

    <!-- Tabs content -->
    <div class="tab-content" id="ex2-content">
        <div class="tab-pane fade show active bg-white p-3" id="ex2-tabs-1" role="tabpanel" aria-labelledby="ex2-tab-1">
            <form action="" method="post" class="mb-3 mt-3">
                <div class="row">
                    <div class="col-6">
                        <div class="row">

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="" class="form-label">Start Date</label>
                                    <input type="date" name="date_start" id="" placeholder="Date Start"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="" class="form-label">End Date</label>
                                    <input type="date" name="date_end" id="" placeholder="Date End"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-6 pt-2">
                                <button type="submit" class="btn btn-success mt-4" name="search_report">Search</button>
                                <button type="submit" class="btn btn-danger mt-4" name='reset'>Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex align-items-end justify-content-end">
                        <a href="down_treat" class="btn btn-success">Download CSV</a>
                    </div>
                </div>
            </form>

            <table class="table table-striped text-center">
                <thead class="bg-dark text-white">
                    <th>No</th>
                    <th>Daily</th>
                    <th>Treatement Qty</th>
                    <th>Income List</th>
                </thead>
                <tbody>

                    <?php
$total = 0;
$income = 0;
$pagi_total = 0;
$pagi_income = 0;
foreach ($reportDaily as $rd) {
    $total += $rd['treatments'];
    $income += $rd['income'];
}
if (isset($_SESSION['search_data'])) {
    foreach ($_SESSION['search_data'] as $rd) {
        $pagi_total += $rd['treatments'];
        $pagi_income += $rd['income'];
    }
}
foreach ($pagi_reportDaily as $rd) {
    echo "<tr>";
    echo "<td>" . $rd['dis_id'] . "</td>";
    echo "<td>" . date('d/M/Y', strtotime($rd['treatment_date'])) . "</td>";
    echo "<td>" . $rd['treatments'] . "</td>";
    echo "<td>" . $rd['income'] . "</td>";
    echo "</tr>";
}

echo "<tr>";
echo "<td></td>";
echo "<td>Total</td>";
echo "<td>" . (isset($_SESSION['search_data']) ? $pagi_total : $total) . "</td>";
echo "<td>" . (isset($_SESSION['search_data']) ? $pagi_income : $income) . "</td>";
echo "</tr>";

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
                        <a class="page-link" href="<?php echo ($pages == 2) ? 'reports' : $pre_page; ?>"
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
if ($page == $pages) {
        $ellipse = true;
        ?>
                    <li class='page-item active'>
                        <a class='page-link'
                            href='<?php echo ($page - 1 < 1) ? 'reports' : $server_page . "?pages=" . $page; ?>'>
                            <?php echo $page; ?>
                        </a>
                    </li>
                    <?php
} else {
        // condition for ... in pagination
        if ($page <= $ends || ($pages && $page >= $pages - $middle && $page <= $pages + $middle) || $page > $num_of_pages - $ends) {
            ?>
                    <li class='page-item'>
                        <a class='page-link'
                            href='<?php echo ($page - 1 < 1) ? 'reports' : $server_page . "?pages=" . $page; ?>'>
                            <?php echo $page; ?>
                        </a>
                    </li>
                    <?php
$ellipse = true;
        } elseif ($ellipse) {
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



        <div class="tab-pane fade bg-white p-3" id="ex2-tabs-3" role="tabpanel" aria-labelledby="ex2-tab-2">

            <form action="" method="post" class="mb-3 mt-3">
                <div class="row">
                    <div class="col-9">
                        <div class="row w-100">
                            <div class="col-2">
                                <!-- Select Medicine type -->
                                <div class="form-group" id='medicines'>
                                    <label for="" class="form-label">Medicine Type</label>
                                    <select name="medicine" id="medi_type" class="form-control">
                                        <option value="0" selected hidden>Select Medicine</option>
                                        <?php
foreach ($mediTypes as $mediType) {
    ?>
                                        <option value="<?php echo $mediType["id"] ?>"><?php echo $mediType["type"]; ?>
                                        </option>

                                        <?php

}
?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="" class="form-label">Medicine Name</label>
                                    <select name="medicine_id" class="form-control" id="medi_list">
                                        <option value="0">Choose Medicine</option>
                                    </select>
                                </div>
                            </div>


                            <!-- <div class="col-3">
                                <div class="form-group">
                                    <label for="" class="form-label">Select Year</label>
                                    <select name="report_year" id="" class="form-control">
                                        <option value="0" selected hidden>YYYY</option>
                                        <?php
for ($year = 2015; $year <= date('Y'); $year++) {
    echo "<option value='" . $year . "'>" . $year . "</option>";
}
?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="" class="form-label">Select Month</label>
                                    <select name="relatedMonth" id="" class="form-control">
                                        <?php

foreach (range(1, 12) as $month) {
    $monthname = date('F', mktime(0, 0, 0, $month, 10));
    // $monthval =  substr(date('F',mktime(0,0,0,$month,10)),0,3);
    ?>
                                        <option value='<?php echo $monthname; ?>'
                                            <?php echo ((isset($_POST['relatedMonth']) && $_POST['relatedMonth'] == $monthname) || $curr_month == $monthname) ? 'selected' : ''; ?>>
                                            <?php echo $monthname; ?></option>
                                        <?php
}
?>
                                    </select>
                                </div>
                            </div> -->

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="" class="form-label">Start Date</label>
                                    <input type="date" name="date_start" id="" placeholder="Date Start"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="" class="form-label">End Date</label>
                                    <input type="date" name="date_end" id="" placeholder="Date End"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-4 d-flex align-items-center pt-2">
                                <button type="submit" class="btn btn-success mt-4 me-2"
                                    name="search_medi">Search</button>
                                <button type="submit" class="btn btn-danger mt-4" name="reset_medi">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-end justify-content-end">
                        <a href="down_medi" class="btn btn-success">Download CSV</a>
                    </div>

                </div>
            </form>

            <h4>Medicine - <?php echo (isset($_SESSION['medi_name'])) ? $_SESSION['medi_name'] : ''; ?></h4>
            <table class="table table-striped text-center">
                <thead class="bg-dark text-white">
                    <th>No</th>
                    <th>Date</th>
                    <th>Opening</th>
                    <th>Stock</th>
                    <th>Usage</th>
                    <th>Remaining</th>
                </thead>
                <tbody>
                    <?php
foreach ($pagi_mediRp as $m_rp) {
    ?>
                    <tr>
                        <td><?php echo $m_rp['display_id']; ?></td>
                        <td><?php echo date('d/M/Y', strtotime($m_rp['date'])); ?></td>
                        <td><?php echo $m_rp['opening']; ?></td>
                        <td><?php echo $m_rp['stock']; ?></td>
                        <td><?php echo $m_rp['used']; ?></td>
                        <td><?php echo $m_rp['closing']; ?></td>
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
                        <a class="page-link" href="<?php echo ($pages == 2) ? 'reports' : $pre_page; ?>"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <?php
$ellipse = false;
$ends = 1;
$middle = 2;

for ($page = 1; $page <= $num_of_medi_pages; $page++) {
    ?>
                    <?php
if ($page == $pages) {
        $ellipse = true;
        ?>
                    <li class='page-item active'>
                        <a class='page-link'
                            href='<?php echo ($page - 1 < 1) ? 'reports' : $server_page . "?pages=" . $page; ?>'>
                            <?php echo $page; ?>
                        </a>
                    </li>
                    <?php
} else {
        // condition for ... in pagination
        if ($page <= $ends || ($pages && $page >= $pages - $middle && $page <= $pages + $middle) || $page > $num_of_pages - $ends) {
            ?>
                    <li class='page-item'>
                        <a class='page-link'
                            href='<?php echo ($page - 1 < 1) ? 'reports' : $server_page . "?pages=" . $page; ?>'>
                            <?php echo $page; ?>
                        </a>
                    </li>
                    <?php
$ellipse = true;
        } elseif ($ellipse) {
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

    </div>

</div>
<!-- Tabs content -->
</div>

<script>
var medicines = $.parseJSON('<?php echo json_encode($medicines); ?>');
</script>



<?php
include_once './layouts/footer.php';
?>