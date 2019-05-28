<?php
session_start();
require_once('includes/user.inc.php');
require_once('includes/convert.inc.php');
if (isset($_GET['logOut'])) {
  session_destroy();
  Header('Location: index.php');
}
if (isset($_FILES)) {
  $check = true;
  $extension = pathinfo($_FILES['books_file']['name'], PATHINFO_EXTENSION);
  if ($extension !== 'csv') {
    $check = false;
    Header('Location: library.php');
    die;
  }
  if ($check) {
    $file_id = uniqid();
    $path = realpath('./') . '/csv_uploads/' . $file_id . ".csv";
    move_uploaded_file($_FILES['books_file']['tmp_name'], "$path");
  }
}

if (isset($_GET['logOut'])) {
  session_destroy();
  header('Location :index.php');
}
?>
<html>

<head>
  <title>Service php</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <form action="charge.php" method="post" id="payment-form" enctype="multipart/form-data">

    <?php
    $csvBooks = new Convert();
    $theCsvBooks = $csvBooks->getBooks($path);
    $_SESSION['order'] = $theCsvBooks;
    ?>
    <?php
    foreach ($csvBooks->booksNotFound as $bookNotFound) {
      ?>
      <p><?php echo ($bookNotFound); ?> was not found</p>
    <?php
  }
  ?>
    <br>
    <hr>
    <br>
    <div class="form-row">
      <label for="card-element">Credit or debit card</label>
      <div id="card-element">
        <!-- a Stripe Element will be inserted here. -->
      </div>
      <!-- Used to display form errors -->
      <div id="card-errors"></div>
    </div>
    <br>
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

  ?>
</body>

</html>