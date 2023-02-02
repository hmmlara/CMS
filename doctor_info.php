<?php

ob_start();

include_once "./layouts/header.php";
include_once "./controllers/DoctorController.php";

$doctorController=new DoctorController();
$doctors=$doctorController->getDoctors();

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


<div class="container mt-3">

    <div class="row">
        <div class="col-md-12 mb-3">
            <h4>Doctor Info</h4>
            <a href="all_doctors.php" class="btn btn-primary">Back</a>
        </div>

        <div class="col-md-12">
            <div class="bg-white p-4">
                <div class="row">
                    <div class="col-3">
                        <div class="w-50 mx-auto">
                            <img src="uploads/<?php echo $doctorInfos['img'] ?>" alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mt-4">
                            <small><?php echo $doctorInfos["user_code"];?></small>
                            <h3>Doctor <?php echo $doctorInfos['name'] ?></h3>

                        </div>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-white float-end" id="dropdownMenuLink" data-mdb-toggle="dropdown"
                            aria-expanded="false"><i class="fas fa-ellipsis-v fs-5"></i></button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="doctor_edit?id=<?php echo $_GET["id"]; ?>">Edit</a></li>
                            <h6 id="del_id" hidden><?php echo $_GET['id']; ?></h6>
                            <li><button class="dropdown-item" id="del_doc">Delete</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <!-- Tabs navs -->
            <ul class="nav nav-tabs nav-fill mb-3 mt-3 bg-light" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active fw-bold" id="ex2-tab-1" data-mdb-toggle="tab" href="#ex2-tabs-1"
                        role="tab" aria-controls="ex2-tabs-1" aria-selected="true">Patient</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-bold" id="ex2-tab-2" data-mdb-toggle="tab" href="#ex2-tabs-2" role="tab"
                        aria-controls="ex2-tabs-2" aria-selected="false">Biography</a>
                </li>
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="ex2-content">
                <div class="tab-pane fade show active" id="ex2-tabs-1" role="tabpanel" aria-labelledby="ex2-tab-1">
                    <table class="table  table-active table-striped mt-5 text-center text-dark">
                        <thead class="bg-gradient-dark   ">
                            <th>Id</th>
                            <th>Patient Name</th>
                            <th>Treatment</th>
                            <th>Appointment</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Cho</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade overflow-auto" id="ex2-tabs-2" role="tabpanel" aria-labelledby="ex2-tab-2"
                    style="min-height: 300px;">
                    <div class="w-100 ">
                        <div class="card" style="height: 250px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class='mb-4'>Doctor
                                            Code:&nbsp;&nbsp;<span><?php echo $doctorInfos['user_code'] ?></span></h5>
                                        <h5 class='mb-4'>Age:&nbsp;&nbsp;<span><?php echo $doctorInfos['age'] ?></span>
                                        </h5>
                                        <h5 class='mb-4'>
                                            Education:&nbsp;&nbsp;<span><?php echo $doctorInfos['education'] ?></span>
                                        </h5>
                                        <h5 class='mb-4'>
                                            Gender:&nbsp;&nbsp;<span><?php echo $gender[$doctorInfos['gender']]?></span>
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class='mb-4'>
                                            martial_status:&nbsp;&nbsp;<span><?php echo $doctorInfos['martial_status'] ?></span>
                                        </h5>
                                        <h5 class='mb-4'>Nrc:&nbsp;&nbsp;<span><?php echo $doctorInfos['nrc'] ?></span>
                                        </h5>
                                        <h5 class='mb-4'>
                                            created_at:&nbsp;&nbsp;<span><?php echo $doctorInfos['created_at'] ?></span>
                                        </h5>
                                        <h5 class='mb-4'>
                                            Updated_at:&nbsp;&nbsp;<span><?php echo $doctorInfos['updated_at'] ?></span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs content -->
        </div>
    </div>

    <div class="row mt-3">


        <div class="col-md-9">
            <!-- <div class="card " style="height: 250px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class='mb-4'>Doctor
                                Code:&nbsp;&nbsp;<span><?php echo $doctorInfos['user_code'] ?></span></h5>
                            <h5 class='mb-4'>Age:&nbsp;&nbsp;<span><?php echo $doctorInfos['age'] ?></span></h5>
                            <h5 class='mb-4'>Education:&nbsp;&nbsp;<span><?php echo $doctorInfos['education'] ?></span>
                            </h5>
                            <h5 class='mb-4'>
                                Gender:&nbsp;&nbsp;<span><?php echo $gender[$doctorInfos['gender']]?></span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class='mb-4'>
                                martial_status:&nbsp;&nbsp;<span><?php echo $doctorInfos['martial_status'] ?></span>
                            </h5>
                            <h5 class='mb-4'>Nrc:&nbsp;&nbsp;<span><?php echo $doctorInfos['nrc'] ?></span></h5>
                            <h5 class='mb-4'>
                                created_at:&nbsp;&nbsp;<span><?php echo $doctorInfos['created_at'] ?></span></h5>
                            <h5 class='mb-4'>
                                Updated_at:&nbsp;&nbsp;<span><?php echo $doctorInfos['updated_at'] ?></span></h5>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

</div>

<?php
include_once "./layouts/footer.php";
?>