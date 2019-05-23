<?php
session_start();
//session_destroy();
require_once('includes/user.inc.php');
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
<form action="charge.php" method="post" id="payment-form" enctype="multipart/form-data">
  
Upload your CSV file:<br>
<input type="file" name="books_file"><br>
  <!--div class="form-row">
    <label for="card-element">Credit or debit card</label>
    <div id="card-element">
      
    </div>
    
    <div id="card-errors"></div-->
  <input type="submit" value="Submit Payment">
</form>
<a href="service.php?logOut=true">Log out!</a>
<!-- The needed JS files -->
<!-- JQUERY File -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Your JS File -->
<script src="charge.js"></script>
<?php 
}
else{
  Header('Location: index.php');
}




$userClass = new User();
$userInfo = $userClass->setStripeId(4234234);
?>
</body>
</html>