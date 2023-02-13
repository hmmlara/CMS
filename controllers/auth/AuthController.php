<?php


require_once dirname(dirname(__DIR__)).'/models/auth/Auth.php';


class AuthController extends Auth
{
    private $message = '';

    
    public function __construct()
    {
        if(!isset($_SESSION['token'])){
            $_SESSION["token"] = '';
        }
    }
    // check for authentication
    public function isAuth(){
        if(isset($_SESSION["user"])){
            return true;
        }
        return false;
    }

    // login funciton
    public function login($data){
        $user = $this->checkUserExit($data["email"]);
        
        if(empty($user)){
            $this->message = "User with ".$data['email']." not found!";
            return false;
        }
        else{
            $db_password = $user["password"];

            if(password_verify($data['password'],$db_password)){
                
                $token = $_SESSION["token"];

                $data = [
                    'token' => $token,
                    'id' => $user["id"],
                ];
                // save token in database
                $this->saveToken($data);

                // save user id
                $_SESSION["token"] = $token;

                $currUser = $this->getCurrUser($user['id']);

                if($currUser['token'] == $_SESSION['token']){
                    $_SESSION['user'] = $currUser;

                    return true;
                }
            }
            else{
                $this->message = "Invalid User";
                return false;
            }
        }

        return false;
    }

    public function hasRole(){
        if(isset($_SESSION['user'])){
            $role = $this->getCurrUser($_SESSION['user']['id'])['role_id'];
            $check ='';
            switch($role){
                case 1 : $check='admin';break;
                case 2 : $check= 'doctor';break;
                case 3 : $check= 'recep'; break;
            }
            return $check;
        }
    }

    public function getName(){
        if($_SESSION['user']){
            return $_SESSION['user']['acc_name'];
        }
    }

    // get message
    public function getMessage(){
        return $this->message;
    }
}
