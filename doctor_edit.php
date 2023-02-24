<?php

include_once "layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor' || $auth->hasRole() == 'reception'){
    header('location:_403');
}

include_once "controllers/DoctorController.php";
require_once "core/Request.php";
require_once "core/Validator.php";
require_once "core/File.php";
require_once "core/libraray.php";

$doctorController=new DoctorController();

//added speciality array for Doctor
$specialities=["General Medicine","Nephrologists","Paediatrician","Physical Medicine","Mental Health","Obsteric & Gynae"];
$dataCode = $doctorController->getCode();

if(isset($_GET["id"])){
    $doctors=$doctorController->getDoctorDetail($_GET["id"]);
    // $user=$doctorController->add($_GET["id"]);
    // var_dump($doctors["specialities"]);
    var_dump($doctors);
}



$dataCode = $doctorController->getCode();

if (!empty($dataCode)) {
    // get rid pr from pr-xxxx
    $doctorCode = explode("dr-", $dataCode)[1];
    // add for new patient code
    $newCode = sprintf("%04d", $doctorCode + 1);
}
else{
    $newCode = "0001";
}

$error_msg = [];

if (isset($_POST["add"])) {

     // request for form data
     $request = new Request();

     $data = $request->getAll();

    //image upload
    $filename=$_FILES['img']['name'];
    // var_dump($filename);
    $filesize=$_FILES['img']['size'];
    $tempfile=$_FILES['img']['tmp_name'];
    if($_FILES['img']['error']!=0){
        //echo "File is empty";

        $data["img"] = $doctors["img"];
    }else{

        $fileInfo=explode('.',$filename);
        // var_dump($fileInfo);
        $actual_extension=end($fileInfo);
        $allowed_extension=['jpg','jpeg','png','svg','pdf'];
        if(in_array($actual_extension,$allowed_extension)){
            if($filesize<=2000000){
                $filename=time().$filename;
                $data['img'] = $filename;
               
                move_uploaded_file($tempfile,'uploads/'.$filename);
            }else{
                echo "Your file is too big!";
            }
        }else{
            echo "file type is not allowed";
        }
    }
    
   
    // get rid add array val
    unset($data["add"]);

    $validator = new Validator($data);

    // for error messages
    if (!$validator->validated()) {
        $error_msg = $validator->getErrorMessages();
    } else {
        // clear error messages if validated is true
        $error_msg = [];

        if($data['service_price'] == $doctors['service_price']){
            unset($data['service_price']);
        }

        $result = $doctorController->update($data);
        if ($result) {
            header("location:all_doctors.php");
        }
    }


}

?>

<div class="container">
    <h5>Doctor Edit</h5>
    <a href="all_doctors.php" class="btn btn-success"><i class="fas fa-arrow-left"></i></a>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="user_id" value="<?php echo $_GET["id"]; ?>" hidden>
        <div class="card mb-3 mt-3">
            <div class="card-title p-3">
                <h3>Doctor Information</h3>
            </div>
            <img src="uploads/<?php echo $doctors["img"] ?>" alt="" srcset="" style="width: 300px;height: 300px;" id="img" class="img-thumbnail mx-auto">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="form-label">Doctor's Code</label>
                            <input type="text" name="dr_code" class="form-control" id=" "
                                value="<?php echo $doctors["user_code"];   ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">Name</label>
                            <?php
                                    if(!isset($data["dname"])){
                                ?>
                            <input type="text" name="dname" id=" " class="form-control"
                                value="<?php echo $doctors["name"]; ?>">
                            <?php
                                    }else{
                                ?>
                            <input type="text" name="dname" id=" "
                                class="<?php echo (isset($error_msg['dname']))? "form-control border broder-danger" : "form-control";  ?>"
                                value="<?php echo (!empty($data["dname"]))? $data["dname"] :' ';  ?>">
                            <?php
                                    }
                                        if(isset($error_msg["dname"])){
                                            echo "<small class='text-danger'>".$error_msg["dname"]."</small>";
                                        }
                            ?>
                        </div>


                        <div class="form-group">
                            Select Image file to upload
                            <?php
                                    if(!isset($data["img"])){
                                ?>
                            <input type="file" name="img" id="input" class="form-control"
                                value=" <?php echo $doctors['img'];  ?>" onchange="file_changed()">

                            <?php
                                    }else{
                                ?>
                            <input type="file" name="img" id="input"
                                class="<?php echo (isset($error_msg['img']))? "form-control border broder-danger" : "form-control";  ?>"
                                value="<?php echo (!empty($data["img"]))? $data["img"] :' ';  ?>">

                            <?php
                                    }
                                if(isset($error_msg["img"])){
                                    echo "<small class='text-danger'>".$error_msg["img"]."</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">Date of Birth</label>
                            <?php
                                    if(!isset($data["age"])){
                               ?>
                            <input type="text" name="age" class="form-control" value="<?php echo $doctors["age"];  ?>">
                            <?php
                                    }else{
                               ?>
                            <input type="text" name="age" id=" "
                                class="<?php echo (isset($error_msg["age"])) ? 'form-control border border-danger' : 'form-control' ; ?>"
                                value="<?php echo (!empty($data['age'])) ?$data["age"] :'' ;?>">
                            <?php
                                }
                                    if(isset($error_msg["age"])){
                                        echo "<small class='text-danger'>". $error_msg["age"] ."</small>";
                                    }
                                ?>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">Phone</label>
                            <?php
                                    if(!isset($data["phone"])){
                               ?>
                            <input type="text" name="phone" class="form-control"
                                value="<?php echo $doctors["phone"];  ?>">
                            <?php
                                    }else{
                               ?>
                            <input type="text" name="phone" id=" "
                                class="<?php echo (isset($error_msg["phone"])) ? 'form-control border border-danger' : 'form-control' ; ?>"
                                value="<?php echo (!empty($data['phone'])) ?$data["phone"] :'' ;?>">
                            <?php
                                }
                                    if(isset($error_msg["phone"])){
                                        echo "<small class='text-danger'>". $error_msg["phone"] ."</small>";
                                    }
                                ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="form-label">Education</label>
                            <?php
                                        if(!isset($data["education"])){
                                    ?>
                            <input type="text" name="education" class="form-control"
                                value="<?php echo $doctors["education"]; ?>">
                            <?php
                                        }else{
                                    ?>
                            <input type="text" name="education" id=" "
                                class="<?php echo (isset($error_msg["education"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["education"])) ? $data["education"] : ''; ?>">

                            <?php
                                        }
                                if(isset($error_msg["education"])){
                                    echo "<small class='text-danger'>".$error_msg["education"]."</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">Gender</label>
                            <select name="gender"
                                class="form-control <?php echo (isset($error_msg["gender"])) ? 'border border-danger' : ''; ?>">
                                <option value="0" hidden selected>Choose Gender</option>
                                <option value="1" <?php echo ($doctors["gender"] == '1') ? 'selected' : ''; ?>>Female
                                </option>
                                <option value="2" <?php echo ($doctors["gender"] == '2') ? 'selected' : ''; ?>>Male
                                </option>
                            </select>
                            <?php
                                if (isset($error_msg["gender"])) {
                                    echo "<small class='text-danger'>" . $error_msg["gender"] . "</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">Martial Status</label>
                            <?php
                                    if(!isset($data["status"])){
                                        
                                ?>
                            <input type="text" name="status" class="form-control"
                                value="<?php echo $doctors['martial_status'];  ?>">
                            <?php
                                    }else{
                                ?>
                            <input type="text" name="status" id=" "
                                class="<?php echo (isset($error_msg["status"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["status"])) ? $data["status"] : ''; ?>">
                            <?php
                                    }
                                if(isset($error_msg["status"])){
                                    echo "<small class='text-danger'>".$error_msg["status"]."</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Speciality</label>
                            <select name="speciality" class="form-control <?php echo (isset($error_msg["speciality"])) ? 'border border-danger' : ''; ?>">
                                <option value="0" hidden selected>Choose Speciality</option>
                                <?php
                                for ($i = 0 ; $i < count($specialities);$i++) {
                                ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($doctors["specialities"] == $i) ? 'selected' : ''; ?>><?php echo $specialities[$i]; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <?php
                            if (isset($error_msg["speciality"])) {
                                echo "<small class='text-danger'>" . $error_msg["speciality"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">NRC</label>
                            <?php
                                 if(!isset($data['nrc'])){
                                ?>
                            <input type="text" name="nrc" class="form-control" value="<?php echo $doctors['nrc'] ; ?> ">
                            <?php
                                 }else{
                                ?>
                            <input type="text" name="nrc" id=" "
                                class="<?php echo (isset($error_msg["nrc"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["nrc"])) ? $data["nrc"] : ''; ?>">
                            <?php
                                 }
                                if(isset($error_msg["nrc"])){
                                    echo "<small class='text-danger'>".$error_msg["nrc"]."</small>";
                                }
                                ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                            <label for="" class="form-label">service_price</label>
                            <?php
                                 if(!isset($data['service_price'])){
                                ?>
                            <input type="text" name="service_price" class="form-control" value="<?php echo $doctors['service_price'] ; ?> ">
                            <?php
                                 }else{
                                ?>
                            <input type="text" name="nrc" id=" "
                                class="<?php echo (isset($error_msg["service_price"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["service_price"])) ? $data["service_price"] : ''; ?>">
                            <?php
                                 }
                                if(isset($error_msg["service_price"])){
                                    echo "<small class='text-danger'>".$error_msg["service_price"]."</small>";
                                }
                                ?>
                        </div>

            </div>
        </div>
        <button type="submit" class="btn btn-success w-100" name="add">Update Doctor</button>
    </form>
</div>


<?php
include_once "layouts/footer.php";
?>