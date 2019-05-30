<?php
// Includes the database class
require_once('./db/db.php');
class User {
    private $db;
    // Sets up a connection to the database
    public function __construct(){
        $this->db = new Dbh();
        $this->db = $this->db->connect();
    }
        // logIn function takes an email and an password anc checks if the user exists in the database,
        // fetches the password from the column and uses the password_verify function to see if the passwords matches.
        // If this is true, then the user will be logged in with a session and linked to the library.php page
        // else the user will be told "wrong password" or "user does not exist"
    public function logIn($email, $password){
         if (!empty($email) || !empty($password))
        { 
            $stmt = $this->db->prepare("SELECT pass FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $hash = $stmt->fetchColumn();
            if(password_verify($password, $hash)) 
            {
                $_SESSION['user'] = $email;
                header('location: library.php');
            }
            else 
            {
                echo "wrong password";
            }
        }
        else 
        {
            echo "User does not exists!";
        }
    }
    /*
        The userExist function takes an email and a bool.
        The function asks the database if the user exists and if the bool i false it returns true,
        if the bool is something but false then it will return the information about the user
    */
    public function userExist($email, $getInfo = false){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        if($stmt->execute([':email' => $email]) === true){
            if($getInfo == false){
                return true;
            }else{
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            
        }
    }
    /*
        The createAccount function takes an array of userInfo
        if the userExist function returns true, then return "User already exists"
        else create an account with the info from the userInfo variable and link to "library.php" page
    */
    public function createAccount($userInfo){
        if($this->userExist($userInfo['email'], "lala")){
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
            Header('Location: library.php');
          }else {
            echo "<script>alert('ERROR: User was not created!');</script>";
         }
            
        }
        
    }
    /*
        The setStripeId function takes an id from stripe in a variable called stripeId
        Gets the name of the user from the session and updates the database for that user
        on the column "stripe_id" with the value sent to the function.
        returns a true or false depending on the execute statement.
    */
    public function setStripeId($stripeId){
        $user = $_SESSION['user'];
        $stmt = $this->db->prepare("UPDATE users SET stripe_id = :stripe_id WHERE email = '$user'");
        $stmt->bindParam(":stripe_id", $stripeId, PDO::PARAM_STR);
        $answer = $stmt->execute();
        return $answer;
    }

}