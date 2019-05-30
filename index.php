<?php
session_start();
require_once('includes/user.inc.php');

// Checks if the user clicks on the registration button or the login button and handles the information from it.
if (isset($_POST['register'])) {
    //Filters the user inputs
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $adress = filter_input(INPUT_POST, 'adress', FILTER_SANITIZE_STRING);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_NUMBER_INT);
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $userInfo = ["email" => $email, "pass" => $pass, "fname" => $fname, "lname" => $lname, "adress" => $adress, "state" => $state, "zip" => (int)$zip, "country" => $country, "phone" => (int)$phone];

    //Creates a "User class" and creates an account with the information that the user gave us.
    $user = new User();
    $user->createAccount($userInfo);
} else if (isset($_POST['logIn'])) {
    //Filters the user inputs
    $email = filter_input(INPUT_POST, 'email1', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);

    //Creates a "User class" and checks if the user exist with the hashed password
    $user = new User();
    $user->logIn($email, $pass);
}
?>
<html>

<head>
    <title>Login php</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <form method="post" id="logIn">
        <div class="form-row">
            <h2>Register</h2>
            <button id="registerForm">Login form!</button>
            <div>
                <div>
                    <label>E-mail:</label>
                    <input type="text" name="email">
                </div>
                <div>
                    <label>Password:</label>
                    <input type="password" name="password">
                </div>
                <div>
                    <label>First Name:</label>
                    <input type="text" name="fname">
                </div>
                <div>
                    <label>Last Name:</label>
                    <input type="text" name="lname">
                </div>
                <div>
                    <label>Adress:</label>
                    <input type="text" name="adress">
                </div>
                <div>
                    <label>State:</label>
                    <input type="text" name="state">
                </div>
                <div>
                    <label>Zip:</label>
                    <input type="text" name="zip">
                </div>
                <div>
                    <label>Country:</label>
                    <input type="country" name="country">
                </div>
                <div>
                    <label>Phone:</label>
                    <input type="text" name="phone">
                </div>
            </div>
        </div>
        <input type="submit" name="register" value="Register">
    </form>

    <form method="post" id="signUp" style="display:none;">
        <div class="form-row">
            <h2>Log in</h2>
            <button id="logInForm">Register Form!</button>
            <div>
                <label>E-mail:</label>
                <input type="text" name="email1">
                <label>Password:</label>
                <input type="password" name="password1">
            </div>
        </div>
        <input type="submit" name="logIn" value="Log in">
    </form>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="logInforms.js"></script>
</body>

</html>