<?php 
    include_once './layouts/header.php';

    require_once './controllers/PaymentController.php';
    require_once './controllers/TreatmentController.php';
    require_once './core/Paginator.php';

    $paymentController = new PaymentController();
    $treatmentController = new TreatmentController();

    $uncheckPayments = $paymentController->getUncheckTreat();
    $treatments = $treatmentController->getAll();
    $treatment_id = $paymentController->getAll();
    
    if(count($treatments) > 0){
        foreach(range(1,count($treatments)) as $index){
            $treatments[$index - 1] += ['display_id' => $index];
        }
    }

    // search appointments
    if(isset($_POST['search_invoice'])){

        unset($_SESSION['search_invoices']);

        // condition for searching with code or name
        if(!empty($_POST['corn']) && (empty($_POST['date_start']) && empty($_POST['date_end']))){
            $treatments = search_data($treatments,$_POST['corn']);
        }

        // condition for searching with start date and end date
        if(empty($_POST['corn']) && (!empty($_POST['date_start']) && !empty($_POST['date_end']))){
            $start = $_POST['date_start'];
            $end = $_POST['date_end'];
            $treatments = search_date_between($treatments,$start,$end);
        }
    }
    // search appointments


    // overwrite $allAppoints array for pagination
    if(!isset($_SESSION['search_invoices'])){
        $_SESSION['search_invoices'] = $treatments;
    }

    if(isset($_SESSION['search_invoices'])){
        $treatments = $_SESSION['search_invoices'];
    }
    // overwrite $allAppoints array for pagination
    // add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;

$per_page = 5;
$num_of_pages = ceil(count($treatments) / $per_page);
$pagi_treats = Pagination::paginator($pages,$treatments, $per_page);


?>
<script>
// localStorage.removeItem('lastTab');
$(document).ready(function() {
    $('a[data-mdb-toggle="tab"]').on('shown.bs.tab', function(e) {
        localStorage.setItem('lastTab', $(this).attr('href'));
        console.log(localStorage.getItem('lastTab'));
    });
    var lastTab = localStorage.getItem('lastTab');

    if (lastTab) {
        $('[href="' + lastTab + '"]').tab('show');
    }
    // localStorage.removeItem('lastTab');
});
</script>
<div class="container mt-3">
    <h3>Check Invoice</h3>
    <hr>

    <form action="" method="post" class="w-100 mb-3">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label for="" class="form-label">Search with Name or Code</label>
                    <input type="text" name="corn" placeholder="enter code or name" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-label">Start Date</label>
                            <input type="date" name="date_start" id="" placeholder="Date Start" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-label">End Date</label>
                            <input type="date" name="date_end" id="" placeholder="Date End" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 pt-2">
                <button type="submit" class="btn btn-success mt-4" name="search_invoice">Search</button>
                <button type="submit" class="btn btn-danger mt-4" name="search_invoice">Reset</button>
            </div>
        </div>
    </form>
    <!-- Tabs navs -->
    <ul class="nav nav-tabs nav-fill mb-3 mt-3 bg-light" id="ex1" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="ex2-tab-1" data-mdb-toggle="tab" href="#current" role="tab"
                aria-controls="ex2-tabs-1" aria-selected="true">Check</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ex2-tab-2" data-mdb-toggle="tab" href="#previous" role="tab"
                aria-controls="ex2-tabs-2" aria-selected="false">Payment</a>
        </li>
    </ul>
    <!-- Tabs navs -->

    <!-- Tabs content -->
    <div class="tab-content" id="ex2-content">
        <div class="tab-pane fade show active" id="current" role="tabpanel" aria-labelledby="ex2-tab-2"
            style="min-height: 300px;">
            <table class="table table-striped text-center mt-3">
                <thead class="bg-dark text-white">
                    <th>Id</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Treatment Date</th>
                    <th>Function</th>
                </thead>
                <tbody>
                    <?php 
                        $count = 0;
                        foreach($uncheckPayments as $uncheck){
                    ?>
                    <tr>
                        <td><?php echo $count+1; ?></td>
                        <td><?php echo $uncheck['pr_name']." (".$uncheck['pr_code'].")";?></td>
                        <td><?php echo $uncheck['dr_name']."(".$uncheck['dr_code'].")";?></td>
                        <td><?php echo date_format(date_create($uncheck['treatment_date']),'d/M/Y');?></td>
                        <td>
                            <form action="invoice" method="post">
                                <input type="text" name="treatment_id" id="" value="<?php echo $uncheck['id'];?>"
                                    hidden>
                                <button type="submit" class="btn btn-sm btn-primary" name="create">Create</button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="previous" role="tabpanel" aria-labelledby="ex2-tab-2" style="min-height: 300px;">
            <table class="table table-striped">
                <thead class="bg-dark text-white">
                    <th>Id</th>
                    <th>Invoice Id</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Treatment Date</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <?php 
                            foreach($pagi_treats as $treatment){
                        ?>
                    <tr>
                        <td><?php echo $treatment['display_id'];?></td>
                        <td><?php echo $treatment['invoice_code'];?></td>
                        <td><?php echo $treatment['pr_name'];?></td>
                        <td><?php echo $treatment['dr_name'];?></td>
                        <td><?php echo date_format(date_create($treatment['treatment_date']),'d/M/Y');?></td>
                        <td>
                            <?php 
                                        if(in_array($treatment['id'],array_column($treatment_id,'treatment_id'))){
                                    ?>
                            <span class="badge badge-primary p-2">Paid</span>
                            <?php 
                                        }
                                        else{
                                    ?>
                            <span class="badge badge-warning">Unpaid</span>
                            <?php 
                                        }
                                    ?>
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
                        <a class="page-link" href="<?php echo ($pages == 2) ? 'invoices' : $pre_page; ?>"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only"></span>
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
                            href='<?php echo ($page - 1 < 1) ? 'invoices' : $server_page . "?pages=" . $page; ?>'>
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
                            href='<?php echo ($page - 1 < 1) ? 'invoices' : $server_page . "?pages=" . $page; ?>'>
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
                            <span class="sr-only"></span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- pagination -->
        </div>
    </div>
    <!-- Tabs content -->
</div>
<?php 
    include_once './layouts/footer.php';
?>