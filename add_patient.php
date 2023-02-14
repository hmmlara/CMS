<?php

include_once "./layouts/header.php";

if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor'){
    header('location:_403');
}

require_once "./controllers/PatientController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";

// get patient code 
$patientController = new PatientController();

// add blooded type array
$bloodTypes = ["A+", "A-", "O+", "O-", "B+", "B-", "AB+", "AB-"];

$dataCode = $patientController->getCode();

if (!empty($dataCode)) {
    // get rid pr from pr-xxxx
    $patientCode = explode("pr-", $dataCode)[1];
    // add for new patient code
    $newCode = sprintf("%04d", $patientCode + 1);
} else {
    $newCode = "0001";
}

$error_msg = [];

if (isset($_POST["add"])) {

    // request for form data
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

        $result = $patientController->save($data);

        if ($result) {
            echo "<script>window.location.href='all_patients'</script>";
        }
    }
}

?>

<div class="container mt-3">
    <h5>Add New Patient</h5>
    <a href="all_patients" class="btn btn-sm btn-dark mb-3"><i class="fas fa-arrow-left"></i></a>

    <div class="card shadow-3">
        <div class="card-body">
            <form action="" method="post">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Patient's Code</label>
                            <input type="text" name="pr_code" id="" class="form-control" value="<?php echo "pr-" . $newCode; ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" id="" class="<?php echo (isset($error_msg["name"])) ? 'form-control  border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["name"])) ? $data["name"] : ''; ?>">
                            <?php
                            if (isset($error_msg["name"])) {
                                echo "<small class='text-danger'>" . $error_msg["name"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Phone</label>
                            <input type="text" name="phone" id="" class="<?php echo (isset($error_msg["phone"])) ? 'form-control  border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["phone"])) ? $data["phone"] : ''; ?>">
                            <?php
                            if (isset($error_msg["phone"])) {
                                echo "<small class='text-danger'>" . $error_msg["phone"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Age</label>
                            <input type="text" name="age" id="" class="form-control <?php echo (isset($error_msg["age"])) ? 'border border-danger' : ''; ?>" value="<?php (!empty($data["age"])) ? $data["age"] : ''; ?>">
                            <?php
                            if (isset($error_msg["age"])) {
                                echo "<small class='text-danger'>" . $error_msg["age"] . "</small>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Weight(kg)</label>
                            <input type="text" name="weight" id="" class="form-control <?php echo (isset($error_msg["weight"])) ? 'border border-danger' : ''; ?>" value="<?php (!empty($data["age"])) ? $data["age"] : ''; ?>">
                            <?php
                            if (isset($error_msg["weight"])) {
                                echo "<small class='text-danger'>" . $error_msg["weight"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Height(ft,in)</label>
                            <input type="text" name="height" id="" class="form-control <?php echo (isset($error_msg["height"])) ? 'border border-danger' : ''; ?>" value="<?php (!empty($data["height"])) ? $data["height"] : ''; ?>">
                            <?php
                            if (isset($error_msg["height"])) {
                                echo "<small class='text-danger'>" . $error_msg["height"] . "</small>";
                            }

                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Gender</label>
                            <select name="gender" class="form-control <?php echo (isset($error_msg["gender"])) ? 'border border-danger' : ''; ?>">
                                <option value="0" hidden selected>Choose Gender</option>
                                <option value="1" <?php echo (isset($data["gender"]) && $data["gender"] == '1') ? 'selected' : ''; ?>>Female</option>
                                <option value="2" <?php echo (isset($data["gender"]) && $data["gender"] == '2') ? 'selected' : ''; ?>>Male</option>
                            </select>
                            <?php
                            if (isset($error_msg["gender"])) {
                                echo "<small class='text-danger'>" . $error_msg["gender"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Blood Type</label>
                            <select name="blood_type" class="form-control <?php echo (isset($error_msg["blood_type"])) ? 'border border-danger' : ''; ?>">
                                <option value="0" hidden selected>Choose Blood Type</option>
                                <?php
                                foreach ($bloodTypes as $bt) {
                                ?>
                                    <option value="<?php echo $bt; ?>" <?php echo (isset($data["blood_type"]) && $data["blood_type"] == $bt) ? 'selected' : ''; ?>><?php echo $bt; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <?php
                            if (isset($error_msg["blood_type"])) {
                                echo "<small class='text-danger'>" . $error_msg["blood_type"] . "</small>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark w-100" name="add">Add Patient</button>
            </form>
        </div>
    </div>
</div>

<?php
include_once "./layouts/footer.php";
?>