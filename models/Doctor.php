<?php
require_once __DIR__."/../core/Database.php";

class Doctor{
    private $pdo;

    protected function getDoctorLists(){
        $this->pdo=Database::connect();

        $sql="SELECT users.id,user_infos.name, user_infos.user_code,user_infos.age,user_infos.education,user_infos.martial_status,user_infos.nrc,user_infos.gender,user_infos.img
        FROM users
        JOIN user_infos ON users.id = user_infos.user_id
        JOIN roles ON roles.id = users.role_id
        WHERE roles.id = 2";
        
        $statement=$this->pdo->prepare($sql);
        $statement->execute();

        $result=$statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

     // Get Doctor Code dr()
     protected function getDoctorCode(){

        $this->pdo = Database::connect();

        $query = "select user_code from user_infos where user_code like '%dr-%'";

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // return the latest pr_code of an array
        return (count($result) > 0)? $result[count($result) - 1]["user_code"] : '';
    }


    //add user
    public function addUser($data){
        $this->pdo = Database::connect();

        $sql = "insert into users(role_id,acc_name,password,created_at,updated_at) Values (2,:acc_name,:password,:created_at,:updated_at)";

        $statement =$this->pdo ->prepare($sql);


            $statement->bindParam(":acc_name",$data["acc_name"]);
            $statement->bindParam(":password",$data["password"]);
            // var_dump($data["password"]);
            $date_time = date('Y-m-d');
            $statement->bindParam(":created_at",$date_time);
            $statement->bindParam(":updated_at",$date_time);

        if($statement->execute()){
        //    $user= $this->addDoctor($data);
        //    echo $return;
            $user_id=$this->getUserId();
            $data['user_id'] = $user_id;
            return $this->addDoctor($user_id,$data);
        }
    }

    //add Doctor
    private function addDoctor($user_id,$data){

        $this->pdo = Database::connect();

        $query ="insert into user_infos(user_id,name,user_code,age,education,martial_status,nrc,gender,created_at,updated_at,img) values (:user_id,:name,:user_code,:age,:education,:martial_status,:nrc,:gender,:created_at,:updated_at,:img)";
        
        $statement = $this->pdo->prepare($query);

        $statement->bindParam(":user_id",$user_id);
        $statement->bindParam(":name",$data["dname"]);
        $statement->bindParam(":user_code",$data["dr_code"]);
        $statement->bindParam(":age",$data["age"]);
        $statement->bindParam(":education",$data["education"]);
        $statement->bindParam(":martial_status",$data["status"]);
        $statement->bindParam(":img",$data["img"]);
        $statement->bindParam(":nrc",$data["nrc"]);
        $statement->bindParam(":gender",$data["gender"]);

        // var_dump($user_id);
        // var_dump($data);

        $date_now = date('Y-m-d');

        $statement->bindParam(":created_at",$date_now);
        $statement->bindParam(":updated_at",$date_now);
        return $statement->execute();

    }

    //get UserId
    public function getUserId(){

        $this->pdo = Database::connect();

        $query = "select id from users";

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result[count($result) - 1]["id"];
    }

    //get DoctorDetail
    public function getDoctorDetail($id){
        $this->pdo = Database::connect();

        $query = "select * from user_infos where user_id = :id";

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
}
?>