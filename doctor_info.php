<?php
include_once "./layouts/header.php";
include_once "./controllers/DoctorController.php";

$doctorController=new DoctorController();

$gender=["","Female","male"];

if(isset($_GET["id"])){
    $doctorInfos =$doctorController->getDetail($_GET["id"]);

    if(!$doctorInfos){
        echo "<script>
            window.location.href='http://localhost/PMS/_404.php'    
        </script>";
    }
}
?>


<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-4" style="height: 250px;">
                <div class="w-50 mx-auto">
                    <img src="uploads/<?php echo $doctorInfos['img'] ?>" alt="" class="img-fluid  rounded-circle " >
                </div>
                <div class="card-title text-center mt-4">
                    <h3><?php echo $doctorInfos['name'] ?></h3>
                </div>

            </div>
        </div>

        <div class="col-md-9">
            <div class="card " style="height: 250px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class='mb-4'>Doctor Code:&nbsp;&nbsp;<span><?php echo $doctorInfos['user_code'] ?></span></h5>
                            <h5 class='mb-4'>Age:&nbsp;&nbsp;<span><?php echo $doctorInfos['age'] ?></span></h5>
                            <h5 class='mb-4'>Education:&nbsp;&nbsp;<span><?php echo $doctorInfos['education'] ?></span></h5>
                            <h5 class='mb-4'>Gender:&nbsp;&nbsp;<span><?php echo $gender[$doctorInfos['gender']]?></span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class='mb-4'>martial_status:&nbsp;&nbsp;<span><?php echo $doctorInfos['martial_status'] ?></span></h5>
                            <h5 class='mb-4'>Nrc:&nbsp;&nbsp;<span><?php echo $doctorInfos['nrc'] ?></span></h5>
                            <h5 class='mb-4'>created_at:&nbsp;&nbsp;<span><?php echo $doctorInfos['created_at'] ?></span></h5>
                            <h5 class='mb-4'>Updated_at:&nbsp;&nbsp;<span><?php echo $doctorInfos['updated_at'] ?></span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-collapse mt-3 text-center">
        <thead class="bg-gradient-dark  text-white ">
            <th>Id</th>
            <th>Patient</th>
            <th>Treatment</th>
            <th>Appointment</th>
        </thead>
    </table>
</div>

<?php
include_once "./layouts/footer.php";
?>