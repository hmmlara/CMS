<?php

require_once __DIR__."/../core/Database.php";


class Reception{
    private $pdo;

    //add 
    public function addUser($data){

        $this->pdo=Database::connect();

        $sql = "insert into users(role_id,acc_name,password,created_at,updated_at) Values (3,:acc_name,:password,:created_at,:updated_at)";

       $statement= $this->pdo->prepare($sql);
        
       //password hashing
        $data["password"]=password_hash($data["password"],PASSWORD_DEFAULT);

       $statement->bindParam(":acc_name",$data["acc_name"]);
       $statement->bindParam(":password",$data["password"]);
            
       $date_time = date('Y-m-d');
       $statement->bindParam(":created_at",$date_time);
       $statement->bindParam(":updated_at",$date_time);

       if($statement->execute()){
        $user_id=$this->getUserId();
        $data['user_id']=$user_id;
        return $this->addReceptionist($user_id,$data);

       }

    }

    //add Receptionist
    private function addReceptionist($user_id,$data){

        $this->pdo = Database::connect();

        $query ="insert into user_infos(user_id,name,user_code,age,phone,education,martial_status,nrc,gender,created_at,updated_at,img) values (:user_id,:name,:user_code,:age,:phone,:education,:martial_status,:nrc,:gender,:created_at,:updated_at,:img)";
        
        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":user_id",$user_id);
        $statement->bindParam(":name",$data["rname"]);
        $statement->bindParam(":user_code",$data["rp_code"]);
        $statement->bindParam(":age",$data["age"]);
        $statement->bindParam(":education",$data["education"]);
        $statement->bindParam(":martial_status",$data["status"]);
        $statement->bindParam(":img",$data["img"]);
        $statement->bindParam(":nrc",$data["nrc"]);
        $statement->bindParam(":gender",$data["gender"]);
        $statement->bindParam(":phone",$data["phone"]);
        // $statement->bindParam(":specialities",$data["speciality"]);


        $date_now = date('Y-m-d');

        $statement->bindParam(":created_at",$date_now);
        $statement->bindParam(":updated_at",$date_now);
        return $statement->execute();

    }

    //get UserId
    private function getUserId(){

        $this->pdo = Database::connect();

        $query = "select id from users where role_id = 3";

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result[count($result) - 1]["id"];
    }

    //provide ReceptionLists
    public function getReceptionLists(){
        $this->pdo=Database::connect();

        $sql="SELECT users.id,user_infos.name, user_infos.user_code,user_infos.age,user_infos.phone,user_infos.education,user_infos.martial_status,user_infos.nrc,user_infos.gender,user_infos.img
        FROM users
        JOIN user_infos ON users.id = user_infos.user_id
        JOIN roles ON roles.id = users.role_id
        WHERE users.role_id = 3";
        
        $statement=$this->pdo->prepare($sql);
        $statement->execute();

        $result=$statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

     // Get Reception Code rp()
     protected function getReceptionCode(){

        $this->pdo = Database::connect();

        $query = "select user_code from user_infos where user_code like '%rp-%'";

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // return the latest pr_code of an array
        return (count($result) > 0)? $result[count($result) - 1]["user_code"] : '';
    }

    //Reception Detail
     public function getReceptionDetail($id){
        $this->pdo = Database::connect();

        $query = "select * from user_infos join users where user_infos.user_id= :id and users.id= :id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":id", $id);

        try{
            if ($statement->execute()) {
                return $statement->fetch(PDO::FETCH_ASSOC);
            }
        }
        catch(PDOException $e){
            return false;
        }
    }


    //update Reception
    protected function updateReception($data){

        $this->pdo = Database::connect();

         // change updated_at data from $data
         $data["updated_at"] = date('Y-m-d');

        $sql1 = "select img from user_infos where user_id = :user_id";

        $state = $this->pdo->prepare($sql1);
        $state->bindParam(":user_id",$data["user_id"]);

        $state->execute();
        $img = $state->fetch(PDO::FETCH_ASSOC)["img"];

        $query = "UPDATE user_infos SET user_code= :user_code,name=:name,age=:age,nrc=:nrc,education=:education,martial_status=:martial_status, gender=:gender, img=:img, phone=:phone, updated_at = :updated_at 
                     WHERE user_id = :user_id" ;
 
         $statement = $this->pdo->prepare($query);
 
         $statement->bindParam(":user_id",$data["user_id"]);
         $statement->bindParam(":name",$data["rname"]);
         $statement->bindParam(":user_code",$data["rp_code"]);
         $statement->bindParam(":age",$data["age"]);
         $statement->bindParam(":education",$data["education"]);
         $statement->bindParam(":martial_status",$data["status"]);
         $statement->bindParam(":img",$data["img"]);
         $statement->bindParam(":nrc",$data["nrc"]);
         $statement->bindParam(":phone",$data["phone"]);
         $statement->bindParam(":gender",$data["gender"]);
         $statement->bindParam(":updated_at",$data["updated_at"]);
        
        if($statement->execute()){

            if($img != $data["img"] || empty($img)){
                if(file_exists('uploads/'.$img)){
                    unlink("uploads/".$img);
                }
            }

            return true;
        }
         return false;

    }


    //Delete Reception
    
    protected function deleteReception($id){
        $this->pdo=Database::connect();

        $sql="Delete from users where id=$id";

        // get id from users table
        $user_id = $this->getIdById($id);

        $statement=$this->pdo->prepare($sql);

        if($statement->execute()){
            $img = $this->getImageById($id);

            $del_sql = "Delete from user_infos where user_id = :user_id";

            $stat = $this->pdo->prepare($del_sql);

            $stat->bindParam(":user_id",$user_id);

            if($stat->execute()){

                if(file_exists("uploads/".$img)){
                    unlink("uploads/".$img);
                    
                    return true;
                }
            }
        }
        return false;
    }

    private function getIdById($id){

        $this->pdo = Database::connect();

        $sql = "select id from users where id = :id";

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":id",$id);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["id"];

        // var_dump($result);
        return $result;
    }

    private function getImageById($id){

        $this->pdo = Database::connect();

        $sql = "select img from user_infos where user_id = :user_id";

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":user_id",$id);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC)["img"];
    }

}


?>