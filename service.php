<?php
session_start();
// Includes required classes
require_once('includes/user.inc.php');
require_once('includes/convert.inc.php');

// Checks if the logOut button is pressed
if (isset($_GET['logOut'])) {
  session_destroy();
  Header('Location: index.php');
}

// Checks if something is put to $_FILES from the page that had a action to this page.
/*
  Sets check variable to true and sets the variable extention to the pathinformation for the file.
  if the file does not end with csv. Then the check variable will be set to false and the site will link back to the Library.php file.

  If the file is a csv and the checked variable is true.
  Set file_id to a unique id.
  Set up a path and name for the file.
  move the uploaded file and move it to a folder called "csv_uploads" with the unique id as the name of the file.


 */

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
?>
<html>

<head>
  <title>Service php</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <form action="charge.php" method="post" id="payment-form" enctype="multipart/form-data">

    <?php
    // creates a new object and set it to a csvBooks variable. And use a function (getBooks) that returns an array of all the books from the file that was moved/uploaded.
    // And the books that was not found gets set to a variable in the object called "booksNotFound"
    $csvBooks = new Convert();
    $theCsvBooks = $csvBooks->getBooks($path);
    // Sets the array of books to a session variable.
    $_SESSION['order'] = $theCsvBooks;
    ?>
    <?php
    // Foreach book that was not found. Print out the ISBN nubmer and then "was not found" in a p tag
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