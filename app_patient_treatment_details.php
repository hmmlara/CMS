<?php 

    include_once './layouts/header.php';

    if(!$auth->isAuth()){
        header('location:login_form');
    }
    require_once './controllers/TreatmentController.php';

    $treatmentController = new TreatmentController();
    
    if(isset($_GET['treatment_id'])){
        $details = $treatmentController->getTreatmentDetails($_GET['treatment_id']);
    }
?>
<div class="container mt-3">
    <h3>Treatment Details</h3>
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-sm btn-success"><i class="fas fa-arrow-left"></i></a>

    <div class="row mt-3">
        <div class="col-4">
            <div class="card p-5">
                <div class="card-title">
                    <h3 class="text-center">Diagnosis</h3>
                </div>
                <div class="card-content">
                    <p><?php echo $details['note'];?></p>
                </div>
            </div>
        </div>
        <div class="col-8">
            <table class="table table-striped mt-3">
                <thead class="bg-dark text-white">
                    <th>Id</th>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </thead>
                <tbody>
                    <?php 
                        $count = 0;
                        foreach($details['medicine_list'] as $list){
                    ?>
                    <tr>
                        <td><?php echo $count+1; ?></td>
                        <td><?php echo $list['medi_name'];?></td>
                        <td><?php echo $list['qty'];?></td>
                        <td><?php echo $list['medi_per_price']* $list['qty'];?></td>
                    </tr>
                    <?php 
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
    include_once './layouts/footer.php';
?>