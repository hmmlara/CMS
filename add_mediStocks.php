<?php
ob_start();
require_once __DIR__ . "./layouts/header.php";

require_once "./controllers/MedicineController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";


$stockController = new MedicineController();
$medicines = $stockController->getNameAndId();

$error_msg = [];
if (isset($_POST["add"])) {
    //request for form data
    $request = new Request();
    $data = $request->getAll();
    unset($data["add"]);
    $validator = new Validator($data);

    //for error message

    if (!$validator->validated()) {
        $error_msg = $validator->getErrorMessages();
    } else {
        //clear error messages if validated is true
        $error_msg = [];

        $result = $stockController->addStock($data);
        // var_dump($result);
        if ($result) {
            header("location:medicine.php");
        }
    }
}



?>

<div class="">
    <div class="col-md-12">
        <h3 class="col-md-12"><b>Add new Medicines</b></h3>
    </div>
</div>

<div class="">
    <div class="row">

        <form method="post" class="col-12">
            <div class="form-group">
                <label for="formGroupExampleInput2">Medicine Name</label>

                <select name="medicine_id" id="" class="<?php echo (isset($error_msg["medicine_id"])) ? 'form-control border border-danger' : 'form-control'; ?>">
                    <option value="0" hidden selected>Select Category</option>
                    <?php
                    foreach ($medicines as $medi) {
                    ?>
                        <option value="<?php echo $medi["id"]; ?>"><?php echo $medi["name"] ?></option>
                    <?php
                    }
                    ?>
                </select>

                <div>
                    <?php
                    if (isset($error_msg["medicine_id"])) {
                        echo "<small class='text-danger'>Please Select Medicine Name</small>";
                    }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="formGroupExampleInput2">Quantity</label>
                <input type="text" name="qty" id="formGroupExampleInput2" class="<?php echo (isset($error_msg["qty"])) ? 'form-control border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["qty"])) ? $data["qty"] : ''; ?>">

                <div>
                    <?php
                    if (isset($error_msg["qty"])) {
                        echo "<small class='text-danger'>Please Enter Quantity</small>";
                    }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="formGroupExampleInput2">Price</label>
                <input type="text" name="price" class="<?php echo (isset($error_msg["price"])) ? 'form-control border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["price"])) ? $data["price"] : ''; ?>" id="formGroupExampleInput2">

                <div>
                    <?php
                    if (isset($error_msg["price"])) {
                        echo "<small class='text-danger'>" . $error_msg["price"] . "</small>";
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Manufacture Date</label>
                <input type="date" name="man_date" class="<?php echo (isset($error_msg["man_date"])) ? 'form-control border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["man_date"])) ? $data["man_date"] : ''; ?>" id="formGroupExampleInput2">

                <div>
                    <?php
                    if (isset($error_msg["man_date"])) {
                        echo "<small class='text-danger'>" . $error_msg["man_date"] . "</small>";
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Expire Date</label>
                <input type="date" name="exp_date" class="<?php echo (isset($error_msg["exp_date"])) ? 'form-control border border-danger' : 'form-control'; ?>" value="<?php echo (!empty($data["exp_date"])) ? $data["exp_date"] : ''; ?>" id="formGroupExampleInput2">

                <div>
                    <?php
                    if (isset($error_msg["exp_date"])) {
                        echo "<small class='text-danger'>" . $error_msg["exp_date"] . "</small>";
                    }
                    ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary float-right" name="add">Add new medicine</button>

        </form>
    </div>
</div>

<?php
require_once "./layouts/footer.php";
?>