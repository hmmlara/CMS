<?php

include_once "./layouts/header.php";

require_once "./controllers/PatientController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";

// get patient code 
$patientController = new PatientController();

// add blooded type array
$bloodTpyes = ["A+", "A-", "O+", "O-", "B+", "B-", "AB+", "AB-"];

if (isset($_GET["id"])) {
    $patient = $patientController->getById($_GET["id"]);
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

        $result = $patientController->update($data);

        if ($result) {
            echo "<script>window.location.href='all_patient.php'</script>";
        }
    }
}

?>

<div class="container mt-3">
    <h5>Edit Patient</h5>
    <a href="<?php echo $_SERVER["HTTP_REFERER"];?>" class="btn btn-dark btn-sm mb-3"><i class="fas fa-arrow-left"></i></a>

    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <input type="text" name="id" id="" value="<?php echo $_GET["id"]; ?>" hidden>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Patient's Code</label>
                            <input type="text" name="pr_code" id="" class="form-control" value="<?php echo $patient["pr_code"]; ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Name</label>
                            <?php
                            if (!isset($data["name"])) {
                            ?>
                                <input type="text" name="name" id="" class="form-control" value="<?php echo $patient["name"]; ?>">
                            <?php
                            } else {
                            ?>
                                <input type="text" name="name" id="" class="<?php echo (isset($error_msg["name"])) ? 'form-control  border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["name"])) ? $patient["name"] : ''; ?>">
                            <?php
                            }
                            if (isset($error_msg["name"])) {
                                echo "<small class='text-danger'>" . $error_msg["name"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Phone</label>
                            <?php
                            if (!isset($data["phone"])) {
                            ?>
                                <input type="text" name="phone" id="" class="form-control" value="<?php echo $patient["phone"]; ?>">
                            <?php
                            } else {
                            ?>
                                <input type="text" name="phone" id="" class="<?php echo (isset($error_msg["phone"])) ? 'form-control  border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["phone"])) ? $data["phone"] : ''; ?>">
                            <?php
                            }
                            if (isset($error_msg["phone"])) {
                                echo "<small class='text-danger'>" . $error_msg["phone"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">Age</label>
                            <?php
                            if (!isset($data["age"])) {
                            ?>
                                <input type="text" name="age" id="" class="form-control" value="<?php echo $patient["age"]; ?>">
                            <?php
                            } else {
                            ?>
                                <input type="text" name="age" id="" class="<?php echo (isset($error_msg["age"])) ? 'form-control  border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["age"])) ? $data["age"] : ''; ?>">
                            <?php
                            }
                            if (isset($error_msg["age"])) {
                                echo "<small class='text-danger'>" . $error_msg["age"] . "</small>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Weight(kg)</label>
                            <?php
                            if (!isset($data["weight"])) {
                            ?>
                                <input type="text" name="weight" id="" class="form-control" value="<?php echo $patient["weight"]; ?>">
                            <?php
                            } else {
                            ?>
                                <input type="text" name="weight" id="" class="form-control <?php echo (isset($error_msg["weight"])) ? 'border border-danger' : ''; ?>" value="<?php echo (!empty($data["weight"])) ? $data["weight"] : ''; ?>">
                            <?php
                            }
                            if (isset($error_msg["weight"])) {
                                echo "<small class='text-danger'>" . $error_msg["weight"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Height(ft,in)</label>
                            <?php
                            if (!isset($data["height"])) {
                            ?>
                                <input type="text" name="height" id="" class="form-control" value="<?php echo $patient["height"]; ?>">
                            <?php
                            } else {
                            ?>
                                <input type="text" name="height" id="" class="form-control <?php echo (isset($error_msg["height"])) ? 'border border-danger' : ''; ?>" value="<?php echo (!empty($data["height"])) ? $data["height"] : ''; ?>">
                            <?php
                            }
                            if (isset($error_msg["height"])) {
                                echo "<small class='text-danger'>" . $error_msg["height"] . "</small>";
                            }
                            ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Gender</label>
                            <select name="gender" class="form-control <?php echo (isset($error_msg["gender"])) ? 'border border-danger' : ''; ?>">
                                <option value="0" hidden selected>Choose Gender</option>
                                <option value="1" <?php echo ($patient["gender"] == '1') ? 'selected' : ''; ?>>Female</option>
                                <option value="2" <?php echo ($patient["gender"] == '2') ? 'selected' : ''; ?>>Male</option>
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
                                foreach ($bloodTpyes as $bt) {
                                ?>
                                    <option value="<?php echo $bt; ?>" <?php echo ($patient["blood_type"] == $bt) ? 'selected' : ''; ?>><?php echo $bt; ?></option>
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
                        <input type="date" name="created_at" id="" value="<?php echo $patient["created_at"]; ?>" hidden>
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