<?php 
    include_once './layouts/header.php';


    if(!$auth->isAuth()){
        header('location:login_form');
    }
    
    if($auth->hasRole() == 'reception'){
        header('location:_403');
    }   
    

    require_once './core/Request.php';
    require_once './controllers/MediTypeController.php';
    require_once './controllers/MedicineController.php';
    require_once './controllers/TreatmentController.php';

    $request = new Request();

    $meditypeController = new MediTypeController();
    $medi_types = $meditypeController->getMedicineType();

    $medicineController = new MedicineController();
    $medicines = $medicineController->getAllMedicine();

    $treatmentController = new TreatmentController();

    if(isset($_POST["start"])){
        $_SESSION['info'] = $request->getAll();
    }
    if(isset($_POST["add_pre"])){
        // echo '<pre>';
        // var_dump($_POST);
        // echo '</pre>';
        $result = $treatmentController->create($_POST);

        if($result){
            header('location:add_appointment');
        }
    }
?>
<div class="container-fluid w-75">

    <form action="" method="post">
        <div class="row mt-3">
            <div class="col-12">
                <div class="card p-3">
                    <div class="card-tite">
                        <h2>Patient Information</h2>
                        <hr>
                        <small><?php echo $_SESSION['info']["pr_code"];?></small>
                        <h3><?php echo $_SESSION['info']["pr_name"]; ?></h3>
                        <input type="text" name="pr_id" id="" value="<?php echo $_SESSION["info"]['pr_id'];?>" hidden>
                        <input type="text" name="dr_id" id="" value="<?php echo $_SESSION["info"]['dr_id'];?>" hidden>
                        <input type="text" name="appoint_id" id="" value="<?php echo $_SESSION['info']['appoint_id']; ?>" hidden>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3 ">
                <div class="card p-3">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-10">
                                <h2>Medicines</h2>
                            </div>
                            <div class="col-2">
                                <a class="btn btn-success" id="add_new">Add</a>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="card-body" id="medicines">
                        <div class="row" id="medicine">
                            <div class="col-4">
                                <label for="" class="form-label">Medicine Type</label>
                                <select name="" class="form-control" id="medi_type">
                                    <option value="0" selected hidden>Choose Medicine Type</option>
                                    <?php 
                                    foreach($medi_types as $medi_type){
                                        echo "<option value='".$medi_type['id']."'>".$medi_type["type"]."</option>";
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Medicine Name</label>
                                    <select name="medicine_id[]" class="form-control" id="medi_list">
                                        <option value="0" selected hidden>Choose Medicine</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <label for="" class="form-label">Dose</label>
                                <input type="text" name="medi_qty[]" class="form-control" placeholder="enter qty" id='qty'>
                            </div>
                            <div class="col-2 pt-2">
                                <label for="" class="form-label"></label>
                                <button class="btn btn-danger mt-4" id="remove">X</button>
                            </div>
                            <hr class="mt-4" style="position:relative;width: 95%;left: 1%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="" class="form-label">Duration</label>
                            <input type="text" name="duration" id="" class="form-control mb-3" placeholder="Enter duration" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="" class="form-label">Notes</label>
                            <textarea type="text" name="note" id="" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3 mb-5">
                <div class="card">
                    <div class="card-body">
                        <button type="submit" name="add_pre" class="btn btn-success float-end">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
var medicines = $.parseJSON('<?php echo json_encode($medicines); ?>');
</script>
<?php 
    include_once './layouts/footer.php';
?>