<?php
ob_start();
include_once "layouts/header.php";
include_once "controllers/DoctorController.php";
require_once "core/Request.php";
require_once "core/Validator.php";
require_once "core/File.php";
require_once "core/libraray.php";

$doctorController=new DoctorController();

//added speciality array for Doctor
$specialities=["","General Medicine","Nephrologists","Paediatrician","Physical Medicine","Mental Health","Obsteric & Gynae"];
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

// if(isset($_POST["submit"])){
        
   
    

// }

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
        echo "File is empty";
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

        // $file = $request->file('img');

        // echo $file->getOriginalName();
        // $file->allowedExt(["jpg","jpeg"]);

        // $file_name = uniqid().$file->getOriginalName();

        // if($file->move(public_path()."/uploads/",$file_name)){
        //     echo "success";
        // }

        // else{
        //     echo $file->getErrorMessage();
        // }
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";

        $result = $doctorController->add($data);
        if ($result) {
            header("location:all_doctors.php");
        }
    }
}

?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <h4>Add New Doctor</h4>
            <a href="all_doctors.php" class="btn  btn-primary mb-3">Back</a>
        </div>
    </div>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="role_id" id="" value="2" hidden>
        <div class="card mb-3">
            <div class="card-title p-3">
                <h3>Account</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-label">Account Name</label>
                            <input type="text" name="acc_name" id=""
                                class="<?php echo (isset($error_msg["acc_name"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["acc_name"])) ? $data["acc_name"] : ''; ?>">
                            <?php
                            if(isset($error_msg["acc_name"])){
                                echo "<small class='text-danger'>".$error_msg["acc_name"]."</small>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-label">Account Password</label>
                            <input type="text" name="password" id=""
                                class="<?php echo (isset($error_msg["password"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["password"])) ? $data["password"] : ''; ?>">
                            <?php
                            if(isset($error_msg["password"])){
                                echo "<small class='text-danger'>".$error_msg["password"]."</small>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-title p-3">
                <h3>Doctor Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Doctor's Code</label>
                            <input type="text" name="dr_code" class="form-control" id=" "
                                value="<?php echo 'dr-'.$newCode;   ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="dname" id=" "
                                class="<?php echo (isset($error_msg["dname"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["dname"])) ? $data["dname"] : ''; ?>">
                        </div>
                        <?php
                            if(isset($error_msg["dname"])){
                                echo "<small class='text-danger'>".$error_msg["dname"]."</small>";
                            }
                            ?>

                        <div class="form-group mb-3">
                            Select Image file to upload
                            <input type="file" name="img" id=" "
                                class="<?php echo (isset($error_msg['img']))? "form-control border broder-danger" : "form-control";  ?>"
                                value="<?php echo (!empty($data["img"]))? $data["img"] :' ';  ?>">
                            <!-- <input type="submit" name="submit" value="upload"> -->
                            <?php
                            if(isset($error_msg["img"])){
                                echo "<small class='text-danger'>".$error_msg["img"]."</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Date of Birth</label>
                            <input type="date" name="age" id=" "
                                class="<?php echo (isset($error_msg["age"])) ? 'form-control border border-danger' : 'form-control' ; ?>"
                                value="<?php echo (!empty($data['age'])) ?$data["age"] :'' ;?>">
                            <?php
                                    if(isset($error_msg["age"])){
                                        echo "<small class='text-danger'>". $error_msg["age"] ."</small>";
                                    }
                                ?>
                        </div>

                        

                        
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Phone</label>
                            <input type="text" name="phone" id="" class="<?php echo (isset($error_msg["phone"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                             value="<?php echo (!empty($data["phone"])) ? $data["phone"] : ''; ?>">
                            <?php
                            if (isset($error_msg["phone"])) {
                                echo "<small class='text-danger'>" . $error_msg["phone"] . "</small>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Education</label>
                            <input type="text" name="education" id=" "
                                class="<?php echo (isset($error_msg["education"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["education"])) ? $data["education"] : ''; ?>">

                            <?php
                                if(isset($error_msg["education"])){
                                    echo "<small class='text-danger'>".$error_msg["education"]."</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Gender</label>
                            <select name="gender"
                                class="form-control <?php echo (isset($error_msg["gender"])) ? 'border border-danger' : ''; ?>">
                                <option value="0" hidden selected>Choose Gender</option>
                                <option value="1"
                                    <?php echo (isset($data["gender"]) && $data["gender"] == '1') ? 'selected' : ''; ?>>
                                    Female</option>
                                <option value="2"
                                    <?php echo (isset($data["gender"]) && $data["gender"] == '2') ? 'selected' : ''; ?>>
                                    Male</option>
                            </select>
                            <?php
                                if (isset($error_msg["gender"])) {
                                    echo "<small class='text-danger'>" . $error_msg["gender"] . "</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Martial Status</label>
                            <input type="text" name="status" id=" "
                                class="<?php echo (isset($error_msg["status"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["status"])) ? $data["status"] : ''; ?>">
                            <?php
                                if(isset($error_msg["status"])){
                                    echo "<small class='text-danger'>".$error_msg["status"]."</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Speciality</label>
                            <select name="speciality" id=""
                                class="<?php echo (isset($error_msg['speciality']))? 'form-control border border-danger' : 'form-control' ;?>">
                                <option value="0" selected hidden>Choose Speciality</option>
                                <?php
                                    for ($i =0; $i < count($specialities);$i++) {
                                ?>
                                <option value="<?php echo $i; ?>"
                                    <?php echo (isset($data["speciality"]) && $data["speciality"]==$i )? 'selected' : ''; ?>>
                                    <?php echo $specialities[$i]; ?>
                                </option>
                                <?php
                                    }
                                ?>
                            </select>
                            <?php
                                if(isset($error_msg["speciality"])){
                                    echo "<small class='text-danger'>".$error_msg["speciality"]  ."</small>";
                                }

                            ?>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">NRC</label>
                            <input type="text" name="nrc" id=" "
                                class="<?php echo (isset($error_msg["nrc"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["nrc"])) ? $data["nrc"] : ''; ?>">
                            <?php
                                if(isset($error_msg["nrc"])){
                                    echo "<small class='text-danger'>".$error_msg["nrc"]."</small>";
                                }
                                ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <button type="submit" class="btn btn-success w-100" name="add">Add Doctor</button>
    </form>
</div>


<?php
include_once "layouts/footer.php";
?>