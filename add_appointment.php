<?php

include_once './layouts/header.php';

if (!$auth->isAuth()) {
    header('location:login_form');
}

require_once './controllers/ScheduleController.php';
require_once './controllers/AppointmentController.php';
require_once './controllers/PatientController.php';
require_once './controllers/DoctorController.php';
require_once './controllers/TreatmentController.php';
require_once './core/Request.php';
require_once './core/Validator.php';
require_once './core/libraray.php';
require_once './core/Paginator.php';

$scheduleController = new ScheduleController();
$schedules = $scheduleController->getAll();

$patientController = new PatientController();
$patients = $patientController->getPatients();

$treatmentController = new TreatmentController();
$treatments = $treatmentController->getAll();

$appointmentController = new AppointmentController();
$appointments = $appointmentController->getAppointments(date('Y-m-d'));
$allAppoints = $appointmentController->getAll();

foreach(range(1,count($allAppoints)) as $index){
    $allAppoints[$index - 1] += ['display_id' => $index];
}

if($auth->hasRole() == 'admin' || $auth->hasRole() == 'reception'){
    // search appointments
    if(isset($_POST['search_app'])){

        unset($_SESSION['search_appoints']);

        // condition for searching with code or name
        if(!empty($_POST['corn']) && (empty($_POST['date_start']) && empty($_POST['date_end']))){
            $allAppoints = search_data($allAppoints,$_POST['corn']);
        }

        // condition for searching with start date and end date
        if(empty($_POST['corn']) && (!empty($_POST['date_start']) && !empty($_POST['date_end']))){
            $start = $_POST['date_start'];
            $end = $_POST['date_end'];
            $allAppoints = search_date_between($allAppoints,$start,$end);
        }
    }
    // search appointments


    // overwrite $allAppoints array for pagination
    if(!isset($_SESSION['search_appoints'])){
        $_SESSION['search_appoints'] = $allAppoints;
    }

    if(isset($_SESSION['search_appoints'])){
        $allAppoints = $_SESSION['search_appoints'];
    }
    // overwrite $allAppoints array for pagination
}
$doctorController = new DoctorController();
$doctors = $doctorController->getDoctors();

// check null of an array
if (count($appointments) > 0) {
    foreach (range(1, count($appointments)) as $key) {
        $appointments[$key - 1] += ["display_id" => $key];
    }
}

// filter for today's docs

//    $today_docs = array_values(array_filter($schedules,function($value){
//         return $value["shift_day"] == date('Y-m-d');
//    }));

//    $arr = [];

//    foreach($today_docs as $tod){
//         $data = [
//             'user_id' => $tod["user_id"],
//             'name' => $tod["name"],
//         ];
//         array_push($arr,$data);
//    }

// for remove duplicate data value
//    $result = array_unique($arr,SORT_REGULAR);

$error_msg = [];
if (isset($_POST['add'])) {

    $request = new Request();

    $data = $request->getAll();

    // get rid add array val
    unset($data["add"]);

    $validator = new Validator($data);

    // for error messages
    if (!$validator->validated()) {
        $error_msg = $validator->getErrorMessages();
    } else {
        // clear error messages if validated is true
        $error_msg = [];

        $data['appointment_date'] = date_format(date_create($data['appointment_date']), 'Y-m-d');

        // echo '<pre>';
        // var_dump($data);
        // echo '</pre>';
        $result = $appointmentController->add($data);

        if ($result) {
            header('location:' . $_SERVER["PHP_SELF"]);
        }
    }
}

if (isset($_GET['id'])) {
    $result = $appointmentController->update($_GET["id"], 1);

    if ($result) {
        header('location:' . $_SERVER["PHP_SELF"]);
    }
}

if (isset($_POST['updateStatus'])) {
    //  0 is start line, 1 is complete, status 2 is pending, 3 is cancel
    $result = $appointmentController->update($_POST["appointment_id"], 2);

    if ($result) {
        header('location:add_appointment');
    }
}

if ($auth->hasRole() == 'reception') {
    // filter for reception
    $appointments = array_values(array_filter($appointments, function ($value) {
        return $value["status"] == 0;
    }));
}

if ($auth->hasRole() == 'doctor') {
    $GLOBALS['user_id'] = $auth->getId();
    // filter for doctor
    $appointments = array_values(array_filter($appointments, function ($value) {
        if ($value["status"] == 2 && $value["user_id"] == $GLOBALS['user_id']) {
            return $value;
        }
    }));
    
    // filter for treatment
    $treatments = array_values(array_filter($treatments,function ($value){
        return $value['user_id'] == $GLOBALS['user_id'];
    }));

    foreach(range(1,count($treatments)) as $index){
        $treatments[$index - 1] += ['display_id' => $index];
    }
}

// add pagination
$pages = (isset($_GET["pages"])) ? (int) $_GET["pages"] : 1;

$per_page = 5;
$num_of_pages = ceil(count($allAppoints) / $per_page);
$pagi_appoints = Pagination::paginator($pages,$allAppoints, $per_page);

$num_of_tpages = ceil(count($treatments) / $per_page);
$pagi_treatments = Pagination::paginator($pages,$treatments,$per_page);

?>
<script>

$(document).ready(function() {
    // $('[href="rcp_today"]').tab('show');
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


<?php 
        if($auth->hasRole() == 'admin'){
    ?>
<div class="container-fluid">
    <form action="" method="post" class="w-100 mb-3 mt-3">
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
                <button type="submit" class="btn btn-success mt-4" name="search_app">Search</button>
                <button type="submit" class="btn btn-danger mt-4" name="search_app">Reset</button>
            </div>
        </div>
    </form>
    <table class="table table-striped text-center">
        <thead class="bg-dark text-white">
            <th>Appointment No</th>
            <th>Patient Name</th>
            <th>Doctor Name</th>
            <th>Appointment Date</th>
            <th>Status</th>
        </thead>
        <tbody>
            <?php 
                    foreach($pagi_appoints as $all){
                ?>
            <tr>
                <td><?php echo $all['display_id'];?></td>
                <td><?php echo $all['pr_name']." (".$all['pr_code'].")";?></td>
                <td><?php echo $all['dr_name']." (".$all['dr_code'].")";?></td>
                <td><?php echo date_format(date_create($all['appointment_date']),'d/M/Y');?></td>
                <td>
                    <?php 
                                switch($all['status']){
                                    case 0: echo "<span class='badge badge-primary p-2'>Pending</span>";break;
                                    case 1: echo '<span class="badge badge-danger p-2">Cancel</span>';break;
                                    case 2: echo '<span class="badge badge-primary p-2">Pending</span>';break;
                                    case 3: echo '<span class="badge badge-info p-2">Completed</span>';break;
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
                <a class="page-link" href="<?php echo ($pages == 2) ? 'add_appointment' : $pre_page; ?>"
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
                    href='<?php echo ($page - 1 < 1) ? 'add_appointment' : $server_page . "?pages=" . $page; ?>'>
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
                    href='<?php echo ($page - 1 < 1) ? 'add_appointment' : $server_page . "?pages=" . $page; ?>'>
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
        }
    ?>
<!-- for reception -->
<?php
                if ($auth->hasRole() == 'reception') {
            ?>
<div class="container-fluid m-2">
    <!-- Tabs navs -->
    <ul class="nav nav-tabs nav-fill mb-3 mt-3 bg-light" id="ex1" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="ex2-tab-1" data-mdb-toggle="tab" href="#rcp_today" role="tab"
                aria-controls="ex2-tabs-1" aria-selected="true">Today</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?php echo (isset($_GET['page']))? 'active' : '';?>" id="ex2-tab-2" data-mdb-toggle="tab" href="#rcp_history" role="tab"
                aria-controls="ex2-tabs-2" aria-selected="false">Appointments</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="rcp_today" role="tabpanel" aria-labelledby="ex2-tab-2"
            style="min-height: 300px;">
            <h3 class="mt-3 text-center">Today's date: <?php echo date_format(date_create(date('Y-m-d')), 'd/m/Y'); ?>
            </h3>
            <hr>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-title text-center mt-3">
                            <h5>Add New Appointment</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Patient Code</label>
                                            <select name="pr_id" id=""
                                                class="<?php echo (isset($error_msg["pr_id"])) ? 'border border-danger form-control' : 'form-control'; ?>">
                                                <option value="0">Choose Patient code</option>
                                                <?php
                                                    foreach ($patients as $patient) {
                                                ?>
                                                <option value='<?php echo $patient["id"]; ?>'
                                                    <?php echo (isset($data["pr_id"]) && $data["pr_id"] == $patient["id"]) ? 'selected' : ''; ?>>
                                                    <?php echo $patient["pr_code"]; ?></option>
                                                <?php
                                                    }
                                                ?>

                                            </select>
                                            <?php
                                                if (isset($error_msg["pr_id"])) {
                                                        echo "<small class='text-danger'>Select Patient</small>";
                                                    }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Select Doctor</label>

                                            <!-- Doctor Select -->
                                            <select name="user_id" id="chooseDoc"
                                                class="<?php echo (isset($error_msg["id"])) ? 'border border-danger form-control' : 'form-control'; ?>">
                                                <option value="0" hidden selected>Choose Doctor</option>
                                                <?php
                                                    foreach ($doctors as $doctor) {
                                                ?>
                                                <option value='<?php echo $doctor["id"]; ?>'
                                                    <?php echo (isset($data["user_id"]) && $data["user_id"] == $doctor["id"]) ? 'selected' : ''; ?>>
                                                    <?php echo $doctor["name"]; ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                            <?php
                                                if (isset($error_msg["user_id"])) {
                                                        echo "<small class='text-danger'>Select Doctor</small>";
                                                    }
                                            ?>

                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="form-label">Date</label>
                                                    <input type="text" name="appointment_date" id="sch_day"
                                                        placeholder="No time to show"
                                                        class="<?php echo (isset($error_msg["appointment_date"])) ? 'border border-danger form-control' : 'form-control'; ?>"
                                                        min="09:00"
                                                        value="<?php echo (isset($data["appointment_date"])) ? $data["appointment_date"] : ''; ?>">

                                                    <?php
                                                        if (isset($error_msg["appointment_date"])) {
                                                                echo "<small class='text-danger'>Enter Date</small>";
                                                            }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="form-label">Time</label>
                                                    <select name="appointment_time" id="dutyTime"
                                                        class="<?php echo (isset($error_msg["appointment_time"])) ? "border border-danger form-control" : 'form-control'; ?>">
                                                        <option value="0" selected hidden>No time to show</option>
                                                    </select>

                                                    <!-- select time -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100" name="add">Make Appointment</button>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-striped text-center">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Appointment No</th>
                                <th>Patient Name</th>
                                <th>Doctor Name</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($appointments as $app) {
                            ?>
                            <tr>
                                <td><?php echo $app["display_id"]; ?></td>
                                <td><?php echo $app["pr_name"] . "(" . $app['pr_code'] . ")"; ?></td>
                                <td><?php echo $app["dr_name"]; ?></td>
                                <td><?php echo $app["appointment_time"]; ?></td>
                                <td>

                                    <form action="" method="post" class="d-inline">
                                        <input type="text" name="appointment_id" hidden
                                            value="<?php echo $app["id"]; ?>">
                                        <button type='submit' name='updateStatus' class="btn btn-sm btn-info mx-2"><i
                                                class="fas fa-check"></i></button>
                                    </form>
                                    <a href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $app["id"]; ?>"
                                        class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="rcp_history" role="tabpanel" aria-labelledby="ex2-tab-2">
            <form action="" method="post" class="w-100 mb-3 mt-3">
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
                                    <input type="date" name="date_start" id="" placeholder="Date Start"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="" class="form-label">End Date</label>
                                    <input type="date" name="date_end" id="" placeholder="Date End"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 pt-2">
                        <button type="submit" class="btn btn-success mt-4" name="search_app">Search</button>
                        <button type="submit" class="btn btn-danger mt-4" name="search_app">Reset</button>
                    </div>
                </div>
            </form>
            <table class="table table-striped text-center">
                <thead class="bg-dark text-white">
                    <th>Appointment No</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <?php 
                    foreach($pagi_appoints as $all){
                ?>
                    <tr>
                        <td><?php echo $all['display_id'];?></td>
                        <td><?php echo $all['pr_name']." (".$all['pr_code'].")";?></td>
                        <td><?php echo $all['dr_name']." (".$all['dr_code'].")";?></td>
                        <td><?php echo date_format(date_create($all['appointment_date']),'d/M/Y');?></td>
                        <td>
                            <?php 
                                switch($all['status']){
                                    case 0: echo "<span class='badge badge-primary p-2'>Pending</span>";break;
                                    case 1: echo '<span class="badge badge-danger p-2">Cancel</span>';break;
                                    case 2: echo '<span class="badge badge-primary p-2">Pending</span>';break;
                                    case 3: echo '<span class="badge badge-info p-2">Completed</span>';break;
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
                        <a class="page-link" href="<?php echo ($pages == 2) ? 'add_appointment' : $pre_page; ?>"
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
                            href='<?php echo ($page - 1 < 1) ? 'add_appointment' : $server_page . "?pages=" . $page; ?>'>
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
                            href='<?php echo ($page - 1 < 1) ? 'add_appointment' : $server_page . "?pages=" . $page; ?>'>
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

    </div>

    <!-- Tabs navs -->
</div>

<?php
            }
    ?>
<!-- for reception -->

<!-- for doctor -->
<?php 
                if($auth->hasRole() == 'doctor'){
            ?>
<div class="container-fluid mt-3">
    <!-- Tabs navs -->
    <ul class="nav nav-tabs nav-fill mb-3 mt-3 bg-light" id="ex1" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="ex2-tab-1" data-mdb-toggle="tab" href="#doc_today" role="tab"
                aria-controls="ex2-tabs-1" aria-selected="true">Today</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ex2-tab-2" data-mdb-toggle="tab" href="#doc_history" role="tab"
                aria-controls="ex2-tabs-2" aria-selected="false">Treatments</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="doc_today" role="tabpanel" aria-labelledby="ex2-tab-2"
            style="min-height: 300px;">
            <h3 class="mt-3 text-center">Today's date: <?php echo date_format(date_create(date('Y-m-d')), 'd/m/Y'); ?>
            </h3>
            <hr>

            <div class="row mx-auto">
                <div class="col-md-12">
                    <table class="table table-striped text-center">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Appointment No</th>
                                <th>Patient Name</th>
                                <th>Doctor Name</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($appointments as $app) {
                            ?>
                            <tr>
                                <td><?php echo $app["display_id"]; ?></td>
                                <td><?php echo $app["pr_name"] . "(" . $app['pr_code'] . ")"; ?></td>
                                <td><?php echo $app["dr_name"]; ?></td>
                                <td><?php echo $app["appointment_time"]; ?></td>
                                <td>
                                    <form action="appointment_prescription" method="post">
                                        <input type="text" name="pr_code" id="" value="<?php echo $app["pr_code"]; ?>"
                                            hidden>
                                        <input type="text" name="pr_id" id="" value="<?php echo $app['pr_id']; ?>"
                                            hidden>
                                        <input type="text" name="pr_name" id="" value="<?php echo $app['pr_name']; ?>"
                                            hidden>
                                        <input type="text" name="dr_id" id="" value="<?php echo $app['dr_id']; ?>"
                                            hidden>
                                        <input type="text" name="dr_name" id="" value="<?php echo $app['dr_name']; ?>"
                                            hidden>
                                        <input type="text" name="appoint_id" id="" value="<?php echo $app['id']; ?>"
                                            hidden>
                                        <button type="submit" class="btn btn-sm btn-info" name='start'>Start</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="doc_history" role="tabpanel" aria-labelledby="ex2-tab-2">
            <form action="" method="post" class="w-100 mb-3 mt-3">
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
                                    <input type="date" name="date_start" id="" placeholder="Date Start"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="" class="form-label">End Date</label>
                                    <input type="date" name="date_end" id="" placeholder="Date End"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 pt-2">
                        <button type="submit" class="btn btn-success mt-4" name="search_app">Search</button>
                        <button type="submit" class="btn btn-danger mt-4" name="search_app">Reset</button>
                    </div>
                </div>
            </form>
            <table class="table table-striped text-center">
                <thead class="bg-dark text-white">
                    <th>Id</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Treatment Date</th>
                    <th>Details</th>
                </thead>
                <tbody>
                    <?php 
                    foreach($pagi_treatments as $treatment){
                ?>
                    <tr>
                        <td><?php echo $treatment['display_id'];?></td>
                        <td><?php echo $treatment['pr_name']." (".$treatment['pr_code'].")";?></td>
                        <td><?php echo $treatment['dr_name']." (".$treatment['dr_code'].")";?></td>
                        <td><?php echo date_format(date_create($treatment['treatment_date']),'d/M/Y');?></td>
                        <td>
                            <a href="app_patient_treatment_details?treatment_id=<?php echo $treatment['id'];?>" class="btn btn-primary btn-sm"><i class="fas fa-info-circle"></i></a>
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
                        <a class="page-link" href="<?php echo ($pages == 2) ? 'add_appointment' : $pre_page; ?>"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only"></span>
                        </a>
                    </li>
                    <?php
                $ellipse = false;
                $ends = 1;
                $middle = 2;
                
                for ($page = 1; $page <= $num_of_tpages; $page++) {
            ?>
                    <?php
                        if($page == $pages){
                            $ellipse = true;
                    ?>
                    <li class='page-item active'>
                        <a class='page-link'
                            href='<?php echo ($page - 1 < 1) ? 'add_appointment' : $server_page . "?pages=" . $page; ?>'>
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
                            href='<?php echo ($page - 1 < 1) ? 'add_appointment' : $server_page . "?pages=" . $page; ?>'>
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

    <!-- Tabs navs -->
</div>
<?php 
                }
            ?>
<!-- for doctor -->
<!-- for doc and recep -->
<script>
var duty_time = $.parseJSON('<?=json_encode($schedules);?>')
</script>


<?php
include_once './layouts/footer.php';
?>