<?php

require_once dirname(dirname(__DIR__)).'/core/Database.php';

class Auth
{
    private $pdo;


    // get current user
    protected function getCurrUser($id){

        $this->pdo = Database::connect();

        $query = 'select token,id,role_id,acc_name from users where id = :id';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(':id',$id);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    // check for auth
    protected function checkUserExit($acc_name){
        
        $this->pdo = Database::connect();

        $query = 'select id,acc_name,password,role_id from users where acc_name = :acc_name';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":acc_name",$acc_name);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // save token
    protected function saveToken($data){

        $this->pdo = Database::connect();

        $query = 'update users set token = :token where id = :id';

        $statement = $this->pdo->prepare($query);

        foreach($data as $key => $value){
            $statement->bindParam(":$key",$data[$key]);
        }

        $statement->execute();
    }
    public function getToken($id){
        $this->pdo = Database::connect();

        $query = 'select token from users where id = :id';

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":id",$id);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC)['token'];
    }
}
