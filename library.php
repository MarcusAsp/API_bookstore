<?php
session_start();
require_once('includes/user.inc.php');
require_once('includes/convert.inc.php');

if(isset($_GET['logOut'])){
  session_destroy();
  Header('Location: index.php');
}
if(isset($_SESSION['user'])){
?>
<html>
<head>
<title>Service php</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form action="service.php" method="post" id="payment-form" enctype="multipart/form-data">
  
Upload your CSV file:<br>
<input type="file" name="books_file" accept=".csv"><br><br>
<input type="submit" value="Checkout Books ->">
</form>
<a href="service.php?logOut=true">Log out!</a>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php 
}
else{
  Header('Location: index.php');
}
?>
</body>
</html>