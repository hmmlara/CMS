<?php

    include_once './layouts/header.php';

    if(!$auth->isAuth()){
        header('location:login_form');
      }
      

    require_once './controllers/DoctorController.php';
    require_once './controllers/ScheduleController.php';
    require_once './core/Request.php';
    require_once './core/Validator.php';
    
    $doctorController = new DoctorController();
    $scheduleController = new ScheduleController();

    $doctors  = $doctorController->getDoctors();

    $schedules = $scheduleController->getAll();

    $error_msg = [];
    if(isset($_POST["add"])){

        $request = new Request();

        $data = $request->getAll();

        unset($data["id"]);
        unset($data["add"]);
        // validate input
        $validator = new Validator($data);

        if($validator->validated()){

            $result = $scheduleController->save($data);

            if($result){
                header('location:'.$_SERVER["PHP_SELF"]);
            }
        }
        else{
            $error_msg = $validator->getErrorMessages();
        }
    }

    if(isset($_POST["addMain"])){

        $request = new Request();

        $data = $request->getAll();

        unset($data["id"]);
        unset($data["addMain"]);
        // validate input
        $validator = new Validator($data);

        if($validator->validated()){

            $result = $scheduleController->saveMain($data);

            if($result){
                header('location:'.$_SERVER["PHP_SELF"]);
            }
        }
        else{
            $error_msg = $validator->getErrorMessages();
        }
    }

    if(isset($_POST["update"])){

        $request = new Request();

        $data = $request->getAll();

        unset($data["update"]);
        unset($data["cancel"]);
        // validate input
        $validator = new Validator($data);

        if($validator->validated()){

            $result = $scheduleController->update($data);

            if($result){
                unset($_SESSION["btns"]);
                echo "<script>alert('success');
                    window.location.href='".$_SERVER["PHP_SELF"]."'        
                </script>";
            }
        }
        else{
            $error_msg = $validator->getErrorMessages();
        }
    }
    if(isset($_POST["cancel"])){
        unset($_SESSION["btns"]);
    }
?>

<div class="<?php echo ($auth->hasRole() == 'doctor')? 'container': 'container-fluid';?> mt-5">
    <div class="row">
        <div class="<?php echo ($auth->hasRole() == 'doctor')? 'col-12' : 'col-8';?>">
            <div id="calendar"></div>
        </div>
        <?php 
            if($auth->hasRole() != 'doctor'){
        ?>
        <div class="col-4">
            <!-- Tabs navs -->
            <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="ex1-tab-1" data-mdb-toggle="tab" href="#ex1-tabs-1" role="tab"
                        aria-controls="ex1-tabs-1" aria-selected="true">Main</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="ex1-tab-2" data-mdb-toggle="tab" href="#ex1-tabs-2" role="tab"
                        aria-controls="ex1-tabs-2" aria-selected="false">OPT</a>
                </li>
            </ul>
            <!-- Tabs navs -->
            <!-- Tabs content -->
            <div class="tab-content" id="ex1-content">
                <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
                    <!-- Tabs content -->
                    <div class="card p-3">
                        <div class="card-title text-center">
                            <h6>Add Schedule</h6>
                        </div>
                        <div class="card-content">
                            <form action="" method="post" id="schedule_form">
                                <input type="text" name="id" id="" hidden>
                                <div class="row">
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Select Doctor</label>
                                        <select name="user_id" id=""
                                            class="<?php echo ($error_msg["user_id"])? 'border border-danger form-control': 'form-control'; ?>">
                                            <option value="0" hidden selected>-----</option>
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
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Select Day</label>
                                        <select name="w_day" id=""
                                            class="<?php echo ($error_msg["w_day"])? 'border border-danger form-control': 'form-control'; ?>">
                                            <option value="0" hidden selected>-----</option>
                                            <?php 
                                            
                                            $weekDay = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                                            foreach($weekDay as $wd){
                                            ?>
                                            <option value='<?php echo $wd;?>'
                                                <?php echo (isset($data["w_day"]) && $data["w_day"] == $wd["id"])? 'selected': ''; ?>>
                                                <?php echo $wd;?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                        <?php 
                                            if(isset($error_msg["w_day"])){
                                                echo "<small class='text-danger'>Select Day</small>";
                                            }
                                        ?>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Start Date</label>
                                            <input type="date" name="start_day" id=""
                                                class="<?php echo (isset($error_msg["start_day"]))? 'border border-danger form-control' : 'form-control';?>"
                                                min="09:00"
                                                value="<?php echo (isset($data["start_day"]))? $data["start_day"]: '';?>">

                                            <?php
                                                if(isset($error_msg["start_day"])){
                                                    echo "<small class='text-danger'>Enter Date</small>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">End Date</label>
                                            <input type="date" name="end_day" id=""
                                                class="<?php echo (isset($error_msg["end_day"]))? 'border border-danger form-control' : 'form-control';?>"
                                                min="09:00"
                                                value="<?php echo (isset($data["end_day"]))? $data["end_day"]: '';?>">

                                            <?php
                                                if(isset($error_msg["end_day"])){
                                                    echo "<small class='text-danger'>Enter Date</small>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Duty Start</label>
                                            <input type="time" name="shift_start" id=""
                                                class="<?php echo (isset($error_msg["shift_start"]))? 'border border-danger form-control' : 'form-control';?>"
                                                value="<?php echo (isset($data["shift_start"]))? $data["shift_start"] : '';?>">

                                            <?php
                                                if(isset($error_msg["shift_start"])){
                                                    echo "<small class='text-danger'>Enter duty start time</small>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Duty End</label>
                                            <input type="time" name="shift_end" id=""
                                                class="<?php echo (isset($error_msg["shift_end"]))? 'border border-danger form-control' : 'form-control';?>"
                                                value="<?php echo (isset($data["shift_end"]))? $data["shift_end"] : '';?>">

                                            <?php
                                                if(isset($error_msg["shift_end"])){
                                                    echo "<small class='text-danger'>Enter duty end time</small>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="btn-group">
                                    <?php 
                                
                                        if(isset($_SESSION["btns"])){
                                            echo $_SESSION["btns"];
                                        }
                                        else{
                                            echo '<button type="submit" class="btn btn-success w-100" name="addMain">Add</button>';
                                        }
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
                    <!-- Tab 2 content -->
                    <!-- Tabs content -->
                    <div class="card p-3">
                        <div class="card-title text-center">
                            <h6>Add Schedule</h6>
                        </div>
                        <div class="card-content">
                            <form action="" method="post" id="schedule_form">
                                <input type="text" name="id" id="" hidden>
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Select Doctor</label>
                                    <select name="user_id" id=""
                                        class="<?php echo ($error_msg["user_id"])? 'border border-danger form-control': 'form-control'; ?>">
                                        <option value="0" hidden selected>-----</option>
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
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Date</label>
                                    <input type="date" name="shift_day" id=""
                                        class="<?php echo (isset($error_msg["shift_day"]))? 'border border-danger form-control' : 'form-control';?>"
                                        min="09:00"
                                        value="<?php echo (isset($data["shift_day"]))? $data["shift_day"]: '';?>">

                                    <?php
                                        if(isset($error_msg["shift_day"])){
                                            echo "<small class='text-danger'>Enter Date</small>";
                                        }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Duty Start</label>
                                            <input type="time" name="shift_start" id=""
                                                class="<?php echo (isset($error_msg["shift_start"]))? 'border border-danger form-control' : 'form-control';?>"
                                                value="<?php echo (isset($data["shift_start"]))? $data["shift_start"] : '';?>">

                                            <?php
                                                if(isset($error_msg["shift_start"])){
                                                    echo "<small class='text-danger'>Enter duty start time</small>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Duty End</label>
                                            <input type="time" name="shift_end" id=""
                                                class="<?php echo (isset($error_msg["shift_end"]))? 'border border-danger form-control' : 'form-control';?>"
                                                value="<?php echo (isset($data["shift_end"]))? $data["shift_end"] : '';?>">

                                            <?php
                                                if(isset($error_msg["shift_end"])){
                                                    echo "<small class='text-danger'>Enter duty end time</small>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="btn-group">
                                    <?php 
                                        
                                        if(isset($_SESSION["btns"])){
                                            echo $_SESSION["btns"];
                                        }
                                        else{
                                            echo '<button type="submit" class="btn btn-success w-100" name="add">Add</button>';
                                        }
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php 
            }
        ?>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="event_details_modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title">Schedule Details</h5>
                </div>
                <div class="modal-body rounded-0">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-muted">Name</dt>
                            <dd id="name" class="fw-bold fs-4"></dd>
                            <dt class="text-muted">Duty Day</dt>
                            <dd id="date" class=""></dd>
                            <dt class="text-muted">Start</dt>
                            <dd id="start" class=""></dd>
                            <dt class="text-muted">End</dt>
                            <dd id="end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <?php 
                            if($auth->hasRole() != 'doctor'){ 
                        ?>
                        <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit"
                            data-id="">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete"
                            data-id="">Delete</button>
                        <?php 
                            }
                        ?>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" id="close">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
    var schedules = $.parseJSON('<?= json_encode($schedules)?>')
    </script>
    <?php
    include_once './layouts/footer.php';
?>