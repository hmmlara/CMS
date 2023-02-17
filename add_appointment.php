<?php

   include_once './layouts/header.php';
   require_once './controllers/ScheduleController.php';
   require_once './controllers/AppointmentController.php';
   require_once './controllers/PatientController.php';
   require_once './core/Request.php';
   require_once './core/Validator.php';
   require_once './core/libraray.php';

   $scheduleController = new ScheduleController();
   $schedules = $scheduleController->getAll();

   $patientController = new PatientController();
   $patients = $patientController->getPatients();

   $appointmentController=new AppointmentController();
   $appointments = $appointmentController->getAppointments(date('Y-m-d'));

   // check null of an array
   if(count($appointments) > 0){
        foreach(range(1,count($appointments)) as $key){
            $appointments[$key - 1] += ["display_id" => $key];
        }
   }


   $today_docs = array_values(array_filter($schedules,function($value){
        return $value["shift_day"] == date('Y-m-d');
   }));

   $arr = [];

   foreach($today_docs as $tod){
        $data = [
            'user_id' => $tod["user_id"],
            'name' => $tod["name"],
        ];
        array_push($arr,$data);
   }

   // for remove duplicate data value
   $result = array_unique($arr,SORT_REGULAR);


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

        $data['appointment_date'] = date('Y-m-d');
        $result = $appointmentController->add($data);

        if($result){
            header('location:'.$_SERVER["PHP_SELF"]);
        }
    }
  }
 
  if(isset($_GET['id'])){
        $result = $appointmentController->delete($_GET["id"]);

        if($result){
            header('location:'.$_SERVER["PHP_SELF"]);
        }
  }

  if(isset($_POST['updateStatus'])){
    //  0 is start line, 1 is complete, status 2 is pending, 3 is cancel
    $result = $appointmentController->update($_POST["appointment_id"],2);

    if($result){
        header('location:add_appointment');
    }
}

if($auth->hasRole() == 'reception'){
    // filter for reception
    $appointments = array_values(array_filter($appointments,function($value){
        return $value["status"] == 0;
    }));
}


if($auth->hasRole() == 'doctor'){
    // filter for reception
    $appointments = array_values(array_filter($appointments,function($value){
        if($value["status"] == 2 && $value["user_id"] == $_SESSION["user"]["id"])
            return $value;
    }));

    $_SESSION['patient_info'] = [
      ''
    ];
}
?>


<div class="container-fluid">
    <h3 class="mt-3 text-center">Today's date: <?php echo date_format(date_create(date('Y-m-d')),'d/m/Y');?></h3>
    <hr>
    <div class="row">
        <?php 
            if($auth->hasRole() != 'doctor'){
        ?>
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
                                        class="<?php echo (isset($error_msg["pr_id"]))? 'border border-danger form-control': 'form-control'; ?>">
                                        <option value="0">Choose Patient code</option>
                                        <?php 
                                            foreach($patients as $patient){
                                        ?>
                                        <option value='<?php echo $patient["id"];?>'
                                            <?php echo (isset($data["pr_id"]) && $data["pr_id"] == $patient["id"])? 'selected': ''; ?>>
                                            <?php echo $patient["pr_code"];?></option>
                                        <?php
                                            }
                                        ?>

                                    </select>
                                    <?php 
                                        if(isset($error_msg["pr_id"])){
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
                                        class="<?php echo (isset($error_msg["user_id"]))? 'border border-danger form-control': 'form-control'; ?>">
                                        <option value="0" hidden selected>Choose Doctor</option>
                                        <?php 
                                            foreach($result as $doctor){
                                        ?>
                                        <option value='<?php echo $doctor["user_id"];?>'
                                            <?php echo (!isset($data["user_id"]) && $data["user_id"] == $doctor["user_id"])? 'selected': ''; ?>>
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
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Date</label>
                                            <input type="date" name="start_day" id=""
                                                class="<?php echo (isset($error_msg["sch_day"]))? 'border border-danger form-control' : 'form-control';?>"
                                                min="09:00"
                                                value="<?php echo (isset($data["sch_day"]))? $data["sch_day"]: '';?>">

                                            <?php
                                                if(isset($error_msg["sch_day"])){
                                                    echo "<small class='text-danger'>Enter Date</small>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Time</label>
                                            <select name="appointment_time" id="dutyTime"
                                                class="<?php echo (isset($error_msg["appointment_time"])) ? "border border-danger form-control" : 'form-control';   ?>">
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

        <?php
            }
        ?>

        <div class="<?php echo ($auth->hasRole() == 'doctor')? 'col-md-12' : 'col-md-8';?>">

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
                        foreach($appointments as $app){
                    ?>
                    <tr>
                        <td><?php echo $app["display_id"];?></td>
                        <td><?php echo $app["pr_name"];?></td>
                        <td><?php echo $app["dr_name"];?></td>
                        <td><?php echo $app["appointment_time"];?></td>
                        <?php
                            if($auth->hasRole() == 'reception' || $auth->hasRole() == 'admin'){
                        ?>
                        <td>

                            <form action="" method="post" class="d-inline">
                                <input type="text" name="appointment_id" hidden value="<?php echo $app["id"];?>">
                                <button type='submit' name='updateStatus' class="btn btn-sm btn-info mx-2"><i
                                        class="fas fa-check"></i></button>
                            </form>
                            <a href="<?php echo $_SERVER["PHP_SELF"]."?id=".$app["id"];?>"
                                class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                        </td>
                        <?php
                            }
                        ?>
                        <!-- for doctor -->
                        <?php 
                            if($auth->hasRole() == 'doctor')
                            {
                        ?>
                        <td>
                            <a href="appointment_prescription" class="btn btn-sm btn-info">Start</a>
                        </td>
                        <?php
                            } 
                        ?>

                        <!-- for doctor -->
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<script>
var duty_time = $.parseJSON('<?= json_encode($today_docs); ?>')
</script>


<?php
    include_once './layouts/footer.php';
?>