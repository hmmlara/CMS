<?php

include_once "layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor' || $auth->hasRole() == 'reception'){
    header('location:_403');
}

include_once "controllers/ReceptionController.php";
require_once "core/Request.php";
require_once "core/Validator.php";
require_once "core/File.php";
require_once "core/libraray.php";

$receptionController=new ReceptionController();

$dataCode = $receptionController->getCode();

if(isset($_GET["id"])){
    $receptionists=$receptionController->getReceptionDetail($_GET["id"]);
   
    // var_dump($receptionists["created_at"]);
}



$dataCode = $receptionController->getCode();

if (!empty($dataCode)) {
    // get rid pr from pr-xxxx
    $receptionCode = explode("rp-", $dataCode)[1];
    // add for new patient code
    $newCode = sprintf("%04d", $receptionCode + 1);
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

        $data["img"] = $receptionists["img"];
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

        $result = $receptionController->update($data);
        if ($result) {
            header("location:all_receptionists.php");
        }
    }


}

?>

<div class="container mt-3">
    <h5>Reception Edit</h5>
    <a href="all_receptionists.php" class="btn btn-success btn-sm"><i class="fas fa-arrow-left"></i></a>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="user_id" value="<?php echo $_GET["id"]; ?>" hidden>
        <div class="card mt-4">
            <div class="card-title p-3">
                <h3>Reception Information</h3>
            </div>
            <?php 
                if($receptionists['img']== 'user-default.png'){
            ?>
                <img src="assets/<?php echo $receptionists["img"] ?>" alt="" srcset="" style="width: 300px;height: 300px;" id="img" class="img-thumbnail mx-auto">
            <?php 
                }
                else{
            ?>
                <img src="uploads/<?php echo $receptionists["img"] ?>" alt="" srcset="" style="width: 300px;height: 300px;" id="img" class="img-thumbnail mx-auto">
            <?php }?>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="" class="form-label">Select Image file to upload</label>
                            <?php
                                    if(!isset($data["img"])){
                                ?>
                            <input type="file" name="img" class="form-control" id="input" onchange="file_changed()">

                            <?php
                                    }else{
                                ?>
                            <input type="file" name="img"
                                class="<?php echo (isset($error_msg['img']))? "form-control border broder-danger" : "form-control";  ?>"
                                value="<?php echo (!empty($data["img"]))? $data["img"] :' ';  ?>" id="input">

                            <?php
                                    }
                                if(isset($error_msg["img"])){
                                    echo "<small class='text-danger'>".$error_msg["img"]."</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group mb-2">
                            <label for="" class="form-label">Reception's Code</label>
                            <input type="text" name="rp_code" class="form-control" id=" "
                                value="<?php echo $receptionists["user_code"];   ?>" readonly>
                        </div>

                        <div class="form-group mb-2">
                            <label for="" class="form-label">Name</label>
                            <?php
                                    if(!isset($data["rname"])){
                                ?>
                            <input type="text" name="rname" id=" " class="form-control"
                                value="<?php echo $receptionists["name"]; ?>">
                            <?php
                                    }else{
                                ?>
                            <input type="text" name="rname" id=" "
                                class="<?php echo (isset($error_msg['rname']))? "form-control border broder-danger" : "form-control";  ?>"
                                value="<?php echo (!empty($data["rname"]))? $data["rname"] :' ';  ?>">
                            <?php
                                    }
                                        if(isset($error_msg["rname"])){
                                            echo "<small class='text-danger'>".$error_msg["rname"]."</small>";
                                        }
                            ?>
                        </div>


                       

                        <div class="form-group mb-2">
                            <label for="" class="form-label">Date of Birth</label>
                            <?php
                                    if(!isset($data["age"])){
                               ?>
                            <input type="text" name="age" class="form-control" value="<?php echo $receptionists["age"];  ?>">
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

                        <div class="form-group mb-2">
                            <label for="" class="form-label">Phone</label>
                            <?php
                                    if(!isset($data["phone"])){
                               ?>
                            <input type="text" name="phone" class="form-control"
                                value="<?php echo $receptionists["phone"];  ?>">
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
                        <div class="form-group mb-2">
                            <label for="" class="form-label">Education</label>
                            <?php
                                        if(!isset($data["education"])){
                                    ?>
                            <input type="text" name="education" class="form-control"
                                value="<?php echo $receptionists["education"]; ?>">
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

                        <div class="form-group mb-2">
                            <label for="" class="form-label">Gender</label>
                            <select name="gender"
                                class="form-control <?php echo (isset($error_msg["gender"])) ? 'border border-danger' : ''; ?>">
                                <option value="0" hidden selected>Choose Gender</option>
                                <option value="1" <?php echo ($receptionists["gender"] == '1') ? 'selected' : ''; ?>>Female
                                </option>
                                <option value="2" <?php echo ($receptionists["gender"] == '2') ? 'selected' : ''; ?>>Male
                                </option>
                            </select>
                            <?php
                                if (isset($error_msg["gender"])) {
                                    echo "<small class='text-danger'>" . $error_msg["gender"] . "</small>";
                                }
                                ?>
                        </div>

                        <div class="form-group mb-2">
                            <label for="" class="form-label">Martial Status</label>
                            <?php
                                    if(!isset($data["status"])){
                                        
                                ?>
                            <input type="text" name="status" class="form-control"
                                value="<?php echo $receptionists['martial_status'];  ?>">
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

                        <div class="form-group mb-2">
                            <label for="" class="form-label">NRC</label>
                            <?php
                                 if(!isset($data['nrc'])){
                                ?>
                            <input type="text" name="nrc" class="form-control" value="<?php echo $receptionists['nrc'] ; ?> ">
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

                        <div class="form-group mb-2">
                            <label for="" class="form-label">Start Date</label>
                            <?php
                                 if(!isset($data['created_at'])){
                                ?>
                            <input type="text" name="created_at" class="form-control" value="<?php echo $receptionists['created_at'] ;?>" readonly>
                            <?php
                                 }else{
                                ?>
                            <input type="text" name="created_at" id=" "
                                class="<?php echo (isset($error_msg["created_at"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                                value="<?php echo (!empty($data["created_at"])) ? $data["created_at"] : ''; ?>" readonly>
                            <?php
                                 }
                                if(isset($error_msg["created_at"])){
                                    echo "<small class='text-danger'>".$error_msg["created_at"]."</small>";
                                }
                                ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <button type="submit" class="btn btn-success w-100 mt-4" name="add">Update Receptionist</button>
    </form>
</div>


<?php
include_once "layouts/footer.php";
?>