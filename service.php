<?php
session_start();
//session_destroy();
require_once('includes/user.inc.php');

?>
<html>
<head>
<title>Service php</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form method="post" id="payment-form" enctype="multipart/form-data">
  
Upload your CSV file:<br>
<?php
if(isset($_POST[''])){
?>
  <table>
    <tr>
      <th>ISBN</th>
      <th>Book title</th>
      <th>Author</th>
  </tr>
  <?php

foreach(){

}

  ?>
  </table>
<?php
}
?>
  <input type="submit" value="Submit Payment">
</form>
<a href="?logOut=true">Log out!</a>
<!-- The needed JS files -->
<!-- JQUERY File -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Your JS File -->
<script src="charge.js"></script>
<?php

$userClass = new User();
$userInfo = $userClass->setStripeId(4234234);
?>
</body>
</html>