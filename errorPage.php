<?php
session_start();
if(!isset($_SESSION['user'])){
    session_destroy();
    header('Location: index.php');
}
?>
<html>
<head>
    <title>Login php</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
    <body>
        <h1>Error 404!</h1>
        <div>
        <p>Your card was declined. Please try again!</p>
        <p><a href="library.php"><-Back</a></p>
        </div>
    </body>
</html>