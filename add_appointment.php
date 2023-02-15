<?php
   include_once './layouts/header.php';
   require_once './controllers/DoctorController.php';
   require_once './controllers/ScheduleController.php';
   require_once './controllers/AppointmentController.php';
   require_once './core/Request.php';
   require_once './core/Validator.php';
   require_once './core/libraray.php';

   $doctorController = new DoctorController();
   $doctors  = $doctorController->getDoctors();

   $scheduleController = new ScheduleController();
   $schedules = $scheduleController->getAll();

   $appointmentController=new AppointmentController();
   $appointments=$appointmentController->times();
   

    $spec_sch = array_values(array_filter($schedules,function($value){
        if($value['user_id']==3){
            return $value;
        }
    }));

  $error_msg = [];
  if(isset($_POST['add'])){

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
    }
  }
 
?>


<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-4">
            <!-- Tabs navs -->
            <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="ex1-tab-1" data-mdb-toggle="tab" href="#ex1-tabs-1" role="tab"
                        aria-controls="ex1-tabs-1" aria-selected="true">Today's</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="ex1-tab-2" data-mdb-toggle="tab" href="#ex1-tabs-2" role="tab"
                        aria-controls="ex1-tabs-2" aria-selected="false">Other's Appointment</a>
                </li>
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="ex1-content">
                <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
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
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="form-label">Select Doctor</label>

                                                    <!-- Doctor Select -->
                                                    <select name="user_id" id=""
                                                        class="<?php echo (isset($error_msg["user_id"]))? 'border border-danger form-control': 'form-control'; ?>">
                                                        <option value="0" hidden selected>Choose Doctor</option>
                                                        <?php 
                                                    foreach($doctors as $doctor){
                                                    ?>
                                                        <option value='<?php echo $doctor["id"];?>'
                                                            <?php echo (isset($data["user_id"]) && $data["user_id"] == $doctor["id"])? 'selected': ''; ?>>
                                                            <?php echo $doctor["name"];?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    </select>
                                                    <?php 
                                                    if(isset($error_msg["user_id"])){
                                                        echo "<small class='text-danger'>Select Doctor</small>";
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="form-label">Time</label>
                                                    <select name="" id="chooseTime" class="<?php echo (isset($error_msg["appointment_date"])) ? "border border-danger form-control" : 'form-control';   ?>">
                                                        <option value="0" hidden selected>Choose Time</option>
                                                        <?php
                                                        foreach($schedules as $schedule ){
                                                        ?>

                                                        <option value="<?php echo $schedule['id']; ?>">
                                                            <?php echo format_12hrs($schedule["shift_start"])." - ".format_12hrs($schedule["shift_end"]);?>
                                                        </option>

                                                        <?php
                                                            }

                                                        ?>
                                                        
                                                    </select>

                                                    <!-- select time -->

                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Make Appointment</button>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
                    <!-- Tab 2 content -->
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
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="form-label">Select Doctor</label>

                                                    <!-- Doctor Select -->
                                                    <select name="user_id" id=""
                                                        class="<?php echo (isset($error_msg["user_id"]))? 'border border-danger form-control': 'form-control'; ?>">
                                                        <option value="0" hidden selected>Choose Doctor</option>
                                                        <?php 
                                                    foreach($doctors as $doctor){
                                                    ?>
                                                        <option value='<?php echo $doctor["id"];?>'
                                                            <?php echo (isset($data["user_id"]) && $data["user_id"] == $doctor["id"])? 'selected': ''; ?>>
                                                            <?php echo $doctor["name"];?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    </select>
                                                    <?php 
                                                    if(isset($error_msg["user_id"])){
                                                        echo "<small class='text-danger'>Select Doctor</small>";
                                                    }
                                                ?>

                                                    <!-- Select Schedule Time  -->

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="form-label">Duty time</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Make Appointment</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs content -->

        </div>


        <div class="col-md-8">

            <table class="table table-border-striped">
                <thead>
                    <tr>
                        <th>Appointment No</th>
                        <th>Patient Name</th>
                        <th>Doctor Name</th>
                        <th>Appointment Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
    </div>
</div>
<script>

    var duty_time = $.parseJSON('<?= json_encode($schedules); ?>')
</script>


<?php
    include_once './layouts/footer.php';
?>