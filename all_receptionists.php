<?php
include_once "./layouts/header.php";
include_once "./controllers/ReceptionController.php";
require_once "./core/libraray.php";

$receptionController=new ReceptionController();
$receptionists=$receptionController->getReceptionists();




// reverse array
// $receptionists = array_reverse($receptionists);

// add id for show
for ($i = 1; $i <= count($receptionists); $i++) {
    $receptionists[$i - 1] += ["display_id" => $i];
}

if(isset($_POST["search"])){
    if(!empty($_POST["search_val"])){
        $receptionists = search_data($receptionists,$_POST["search_val"]);
    }
}

?>
<!-- Event Details Modal -->
<div class="modal fade" id="rep_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0">
                <h5 class="modal-title">Reception Details</h5>
                <small id="img" ><img></img></small>
            </div>
            <div class="modal-body rounded-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <small id="code" class='mb-3 d-block'></small>
                            <small id="name" class='mb-3 d-block'></small>
                            <small id="dob" class='mb-3 d-block'></small>
                            <small id="education" class='mb-3 d-block'></small>
                            <small id="start_date" class='mb-3 d-block'></small>
                        </div>
                        <div class="col-6">
                            <small id="gender" class='mb-3 d-block'></small>
                            <small id="m_status" class='mb-3 d-block'></small>
                            <small id="phone" class='mb-3 d-block'></small>
                            <small id="nrc" class='mb-3 d-block'></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer rounded-0">
                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm rounded-0" id="close">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container mt-3">
    <div class="row">

        <div class="col-8">
            <h4>Reception Lists</h4>
            <a href="add_receptionist.php" class="btn btn-sm btn-dark" id="add">Add</a>
        </div>
        <div class="col-4 mb-3">
            <form action="" method="post">
                <div class="form-group d-flex float-right mb-3">
                    <span class="mt-2">Search:&nbsp;</span>
                    <input type="text" name="search_val" id="" class="form-control w-50 mx-3"
                        placeholder="Enter Patient Code">
                    <button type="submit" name="search" class="btn btn-sm btn-dark">Search</button>
                </div>
            </form>

            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <button class="btn btn-light btn-sm mx-2" id="grid1"><i class="fas fa-th fs-6"></i></button>
                    <button class="btn btn-light btn-sm" id="grid2"><i class="fas fa-list fs-6"></i></button>
                </div>
            </div>
        </div>
    </div>

    <hr class="hr-blurry">


    <table class="table table-light table-collapse text-center">
        <thead>
            <th>Id</th>
            <th>Img</th>
            <th>Receptionist Code</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Action</th>
        </thead>
        <tbody id="rep_table">
            <?php
                foreach ($receptionists as $receptionist) {
            ?>
            <div class="<?php  echo (isset($_COOKIE["class"]))? $_COOKIE["class"] : 'col-3 mb-4'; ?>" id="cms_card">
                <tr>
                    <td><?php echo $receptionist["display_id"]; ?></td>
                    <td> <img class='img-fluid' src='uploads/<?php echo $receptionist['img']; ?>' alt=''
                            style='height: 50px;'></td>
                    <td><?php echo $receptionist["user_code"];?></td>
                    <td><?php echo $receptionist["name"]; ?></td>
                    <td><?php echo $receptionist["phone"]; ?></td>
                    <td id="<?php echo $receptionist["id"];?>">
                        <a class="btn btn-sm btn-primary info" data-id="<?php echo $receptionist["id"]; ?>"><i
                                class="fas fa-info-circle"></i></a>
                        <a href="reception_edit?id=<?php echo $receptionist["id"]; ?>" class="btn btn-sm btn-info"><i
                                class="fas fa-pen"></i></a>
                        <button class="btn btn-sm btn-danger delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>

                <?php
                }
            ?>
        </tbody>
    </table>

</div>
<?php
    include_once "./layouts/footer.php";
?>