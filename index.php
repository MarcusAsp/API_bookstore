<?php

?>

<html>
<head>
    <title>Login php</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <form method="post" id="logIn">
        <div class="form-row">
            <h2>Log in</h2>
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
                    <input type="number" name="zip">
                </div>
                <div>
                    <label>Country:</label>
                    <input type="country" name="country">
                </div>
                <div>
                    <label>Phone:</label>
                    <input type="number" name="phone">
                </div>
            </div>
        </div>
        <input type="submit" value="Register">
    </form>

    <form method="post" id="signUp" style="display:none;">
        <div class="form-row">
            <h2>Register</h2>
            <button id="logInForm">Register Form!</button>
            <div>
                <label>E-mail:</label>
                <input type="text" name="email">
                <label>Password:</label>
                <input type="password" name="password">
            </div>
        </div>
        <input type="submit" value="Log in">
    </form>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="logInforms.js"></script>
</body>

</html>