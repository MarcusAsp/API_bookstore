<?php
require_once('./db/db.php');
class User {
    private $db;
    public function __construct(){
        $this->db = new Dbh();
        $this->db = $this->db->connect();
    }

    public function logIn($email, $password){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND pass = :pass");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":pass", $password, PDO::PARAM_STR);
        if($stmt->execute() && $stmt->fetchColumn()){
            $_SESSION['user'] = $email;
            Header('Location: service.php');
        }else {
            echo "<script>alert('User does not exists!');</script>";
         }
        
    }

    public function userExist($email){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        if($stmt->execute([':email' => $email]) && $stmt->fetchColumn()){
            return true;
        }
    }

    public function userInfo($email){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        if($stmt->execute([':email' => $email]) && $stmt->fetchColumn()){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }



    public function createAccount($userInfo){
        if($this->userExist($userInfo['email'])){
            echo "<script>alert('User aready exists!');</script>";
            echo "WTF";
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
        $userData = $this->userInfo($user);
        return $userData;
    }

}