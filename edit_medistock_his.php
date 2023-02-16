<?php

include_once "./layouts/header.php";
if(!$auth->isAuth()){
    header('location:login_form');
}

if($auth->hasRole() == 'doctor'){
    header('location:_403');
}


require_once "./controllers/MedicineController.php";
require_once "./core/Request.php";
require_once "./core/Validator.php";


// get patient code 
$editStockController = new MedicineController();

if (isset($_GET["id"])) {
    $stock = $editStockController->getById($_GET["id"]);
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

        $result = $editStockController->editMedicineStock($data);

        if ($result) {
            header("location:medi_stock_his.php");
            
            // echo "<script>window.location.href='stock_his?id=".$_GET["id"]."'</script>";
        }
    }
}

?>

<div class="container mt-3">
    <h5>Edit Medicine</h5>
    <!-- <a href="<?php echo $_SERVER["HTTP_REFERER"];?>" class="btn btn-dark btn-sm mb-3"><i class="fas fa-arrow-left"></i></a> -->
    <a href="medi_stock_his" class="d-flex justify-content-end">Back</a>
    <form method="post" class="col-12">
        <h3 class="text-center">Medicine: <?php echo $stock["name"];?></h3>
        <input type="text" name="id" id="" value="<?php echo $_GET["id"];?>" hidden>
        <div class="form-group mb-3">
            <label for="" class="form-label">Quantity</label>
            <?php
                if (!isset($data["qty"])) {
                ?>
            <input type="text" name="qty" id="" class="form-control" value="<?php echo $stock["qty"]; ?>">
            <?php
                } else {
                ?>
            <input type="text" name="qty" id=""
                class="<?php echo (isset($error_msg["qty"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                value="<?php echo (!empty($data["qty"])) ? $stock["qty"] : ''; ?>">
            <?php
                }
                if (isset($error_msg["qty"])) {
                    echo "<small class='text-danger'>" . $error_msg["qty"] . "</small>";
                }
                ?>
        </div>
        <div class="form-group mb-3">
            <label for="" class="form-label">Price</label>
            <?php
                if (!isset($data["price"])) {
                ?>
            <input type="text" name="price" id="" class="form-control" value="<?php echo $stock["price"]; ?>">
            <?php
                } else {
                ?>
            <input type="text" name="price" id=""
                class="<?php echo (isset($error_msg["price"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                value="<?php echo (!empty($data["name"])) ? $stock["name"] : ''; ?>">
            <?php
                }
                if (isset($error_msg["price"])) {
                    echo "<small class='text-danger'>" . $error_msg["price"] . "</small>";
                }
                ?>
        </div>

        <div class="form-group mb-3">
            <label for="" class="form-label">Manufacture Date</label>
            <?php
                if (!isset($data["man_date"])) {
                ?>
            <input type="date" name="man_date" id="" class="form-control" value="<?php echo $stock["man_date"]; ?>">
            <?php
                } else {
                ?>
            <input type="date" name="man_date" id=""
                class="<?php echo (isset($error_msg["man_date"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                value="<?php echo (!empty($data["name"])) ? $stock["name"] : ''; ?>">
            <?php
                }
                if (isset($error_msg["man_date"])) {
                    echo "<small class='text-danger'>" . $error_msg["man_date"] . "</small>";
                }
                ?>
        </div>

        <div class="form-group mb-3">
            <label for="" class="form-label">Expire Date</label>
            <?php
                if (!isset($data["exp_date"])) {
                ?>
            <input type="date" name="exp_date" id="" class="form-control" value="<?php echo $stock["exp_date"]; ?>">
            <?php
                } else {
                ?>
            <input type="date" name="exp_date" id=""
                class="<?php echo (isset($error_msg["exp_date"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                value="<?php echo (!empty($data["exp_date"])) ? $stock["exp_date"] : ''; ?>">
            <?php
                }
                if (isset($error_msg["exp_date"])) {
                    echo "<small class='text-danger'>" . $error_msg["exp_date"] . "</small>";
                }
                ?>
        </div>

        <div class="form-group mb-3">
            <label for="" class="form-label">Enter Date</label>
            <?php
                if (!isset($data["enter_date"])) {
                ?>
            <input type="date" name="enter_date" id="" class="form-control" value="<?php echo $stock["enter_date"]; ?>">
            <?php
                } else {
                ?>
            <input type="date" name="enter_date" id=""
                class="<?php echo (isset($error_msg["exp_date"])) ? 'form-control  border border-danger' : 'form-control'; ?>"
                value="<?php echo (!empty($data["exp_date"])) ? $stock["enter_date"] : ''; ?>">
            <?php
                }
                if (isset($error_msg["enter_date"])) {
                    echo "<small class='text-danger'>" . $error_msg["enter_date"] . "</small>";
                }
                ?>
        </div>

        <div class="row">
            <div class="col-11 d-flex justify-content-between  mb-3">
            </div>
            <div class="col-1 me">
                <button type="submit" class="btn btn-dark " name="add">Add</button>
            </div>
        </div>

    </form>
</div>

<?php
include_once "./layouts/footer.php";
?>