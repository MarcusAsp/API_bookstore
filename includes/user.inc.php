<?php
require_once('./db/db.php');
class User {
    private $db;
    public function __construct(){
        $this->db = new Dbh();
        $this->db = $this->db->connect();
    }

    public function logIn($email, $password){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        if($result === true){
            $args = $stmt->fetchColumn();
            var_dump($args);
            /*
            if(){
                $_SESSION['user'] = $email;
                Header('Location: service.php');
            }
            */
        }else {
            echo "<script>alert('User does not exists!');</script>";
         }
        
    }

    public function userExist($email, $getInfo = false){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        if($stmt->execute([':email' => $email]) && $stmt->fetchColumn()){
            if($getInfo == false){
                return true;
            }else{
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
        }
    }




    public function createAccount($userInfo){
        if($this->userExist($userInfo['email'])){
            echo "<script>alert('User aready exists!');</script>";
            die;
        }else {
        $stmt = $this->db->prepare("INSERT INTO users 
        (`email`, `pass`, `fname`, `lname`, `adress`, `state`, `zip`, `country`, `phone`)
        VALUES (:email, :pass, :fname, :lname, :adress, :state, :zip, :country, :phone)");
        
            $stmt->bindParam(":email", $userInfo['email'], PDO::PARAM_STR);
            $stmt->bindParam(":pass", $userInfo['pass'], PDO::PARAM_STR);
            $stmt->bindParam(":fname", $userInfo['fname'], PDO::PARAM_STR);
            $stmt->bindParam(":lname", $userInfo['lname'], PDO::PARAM_STR);
            $stmt->bindParam(":adress", $userInfo['adress'], PDO::PARAM_STR);
            $stmt->bindParam(":state", $userInfo['state'], PDO::PARAM_STR);
            $stmt->bindParam(":zip", $userInfo['zip'], PDO::PARAM_INT);
            $stmt->bindParam(":country", $userInfo['country'], PDO::PARAM_STR);
            $stmt->bindParam(":phone", $userInfo['phone'], PDO::PARAM_INT);

        $answer = $stmt->execute();
        if($answer === true ){
            $_SESSION['user'] = $userInfo['email'];
            Header('Location: service.php');
          }else {
            echo "<script>alert('ERROR: User was not created!');</script>";
         }
            
        }
        
    }

    public function setStripeId($stripeId){
        $user = $_SESSION['user'];
        $userData = $this->userExist($user, true);
        return $userData;
    }

}