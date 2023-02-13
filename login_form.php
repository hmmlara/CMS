<?php   
    require_once './layouts/header.php';
    require_once './core/Request.php';


    if(isset($_POST["login"])){
        $request = new Request();

        $data = $request->getAll();

        unset($data['login']);

        if($auth->login($data)){
            if($auth->hasRole() == 'admin'){
                header('location:index.php');
            }
            else if($auth->hasRole() == 'doctor'){
                header('location:all_patients.php');
            }
        }
    }
?>

<div class="container w-50">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="p-5">
                        <div class="text-center">
                            <i class="fa fa-hospital display-4"></i>
                            <h1 class="h4 text-gray-900 mb-4">Login User</h1>
                        </div>
                        <form class="user" method="post" action="">
                            <div class="form-group mb-3">
                                <label for="">Account Name</label>
                                <input type="text" name="email" class="form-control" id="exampleInputEmail"
                                    aria-describedby="emailHelp" placeholder="Enter Email Address..." required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control" id="exampleInputPassword"
                                    placeholder="Password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary btn-block">
                                Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
<?php
    require_once './layouts/footer.php';
?>