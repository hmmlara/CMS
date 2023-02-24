<?php

require_once dirname(dirname(__DIR__)) . '/models/auth/Auth.php';

class AuthController extends Auth
{
    private $message = '';
    private $logined = false;

    public function __construct()
    {
        if (!isset($_SESSION['token'])) {
            $_SESSION["token"] = '';
        }
    }
    // check for authentication
    public function isAuth()
    {
        if (!isset($_SESSION["user"])) {
            return false;
        }
        else{
            
            $user_token = $this->getToken($_SESSION["user"]["id"]);
            
            if($_SESSION['token'] == $user_token){
                return true;
            }
            else{
                $this->logout();
            }

            return true;
        }
    }

    // check for user login for only one device
    public function isLogined()
    {
        return $this->logined;
    }

    // login funciton
    public function login($data)
    {
        $user = $this->checkUserExit($data["email"]);

        if (empty($user)) {
            $this->message = "User with " . $data['email'] . " not found!";
            return false;
        } else {
            $db_password = $user["password"];

            if (password_verify($data['password'], $db_password)) {

                session_regenerate_id();

                $token = session_id();

                $data = [
                    'token' => $token,
                    'id' => $user["id"],
                ];
                // save token in database
                $this->saveToken($data);

                // save user id
                $_SESSION["token"] = $token;

                $currUser = $this->getCurrUser($user['id']);

                $_SESSION['user'] = $currUser;

                return true;
            } else {
                $this->message = "Invalid User";
                return false;
            }
        }

        return false;
    }

    public function logout()
    {
        session_destroy();
        header('location:login_form');
    }
    public function hasRole()
    {
        if (isset($_SESSION['user'])) {
            $role = $this->getCurrUser($_SESSION['user']['id'])['role_id'];
            $check = '';
            switch ($role) {
                case 1:$check = 'admin';
                    break;
                case 2:$check = 'doctor';
                    break;
                case 3:$check = 'reception';
                    break;
            }
            return $check;
        }
    }

    public function getName()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user']['acc_name'];
        }
    }
    public function getId(){
        if(isset($_SESSION['user'])){
            return $_SESSION['user']['id'];
        }
    }

    // get message
    public function getMessage()
    {
        return $this->message;
    }
}
