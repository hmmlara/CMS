<?php 
    include_once './layouts/header.php';

    if(!$auth->isAuth()){
        header('location:login_form');
    }
    
    if($auth->hasRole() == 'doctor'){
        header('location:_403');
    }
    
    require_once './controllers/TreatmentController.php';
    require_once './controllers/PaymentController.php';

    $treatmentController = new TreatmentController();
    $paymentController = new PaymentController();
    
    $payments = $paymentController->getAll();

    if(isset($_POST['create'])){
        $treatment_id = $_POST['treatment_id'];
        $treatment = $treatmentController->get($treatment_id);
    }
?>
<h3 id="treatment_id" hidden class="mt-3"><?php echo $treatment_id;?></h3>
<h3 id="service_id" hidden><?php echo $paymentController->getService($treatment['user_id']);?></h3>
<div class="card mt-3">
    <div class="card-body">
        <div class="container mb-5 mt-3">
            <div class="container">
                <div class="col-md-12 mb-3">
                    <div class="text-center">
                        <h4 class="pt-0">Clinic Management System</h4>
                    </div>
                </div>

                <div class="row mt-5" id="#fRow">
                    <div class="col-8 col-xl-8">
                        <ul class="list-unstyled">
                            <li class="text-muted">Patient Name:
                                <span style="color:#5d9fc5;"><?php echo $treatment['pr_name'];?></span>
                            </li>
                            <li class="text-muted">Date:
                                <?php echo date_format(date_create($treatment['treatment_date']),'d/M/Y');?></li>

                            <li class="text-muted">
                                <span class="fw-bold">Invoice Code:
                                    <span
                                        id="invoice_code"><?php echo "#CMS".date('Ymd',strtotime($treatment['treatment_date'])).$treatment_id;?></span>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-4 col-xl-4">
                        <ul class="list-unstyled">
                            <li class="text-muted"><span class="fw-bold">Doctor:
                                </span><?php echo $treatment['dr_name'];?>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row my-2 mx-1 justify-content-center">
                    <table class="table table-striped table-borderless text-center">
                        <thead style="background-color:#84B0CA ;" class="text-white printHead">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Medcine</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                              $subtotal = 0;
                              foreach(range(1,count($treatment['medi_lists'])) as $list){
                                $amount = $treatment['medi_lists'][$list - 1]['qty'] * $treatment['medi_lists'][$list - 1]['medi_per_price'];
                                $subtotal += $amount;
                            ?>
                            <tr>
                                <td><?php echo $list; ?></td>
                                <td><?php echo $treatment['medi_lists'][$list - 1]['medi_name']; ?></td>
                                <td><?php echo $treatment['medi_lists'][$list - 1]['qty']; ?></td>
                                <td><?php echo $amount; ?></td>
                            </tr>
                            <?php 
                              }
                            ?>
                        </tbody>

                    </table>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6 col-xl-8">
                        <p class="ms-3">notes and payment information</p>

                    </div>
                    <div class="col-6 col-xl-3">
                        <ul class="list-unstyled">
                            <li class="text-muted ms-3"><span class="text-black">SubTotal:
                                </span><?php echo $subtotal;?> MMK </li>
                            <li class="text-muted ms-3 mt-2"><span class="text-black">Conservation Fee:
                                </span><?php echo $treatment['service_price'];?> MMK</li>
                        </ul>
                        <p class="text-black"><span class="text-black"> Total Amount: </span><span
                                style="font-size: 20px;"
                                id='total'><?php echo ($subtotal + $treatment['service_price']);?></span> MMK
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xl-12">
                        <p>Notes </p>
                        <p><?php echo $treatment['note']; ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xl-10">
                        <p>Thank you for your treatment</p>
                    </div>
                    <div class="col-xl-2">
                        <button type="button" class="btn btn-primary text-capitalize" style="background-color:#60bdf3;"
                            id="check">Check</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="js/print.js"></script>
<?php 
    include_once './layouts/footer.php';
?>