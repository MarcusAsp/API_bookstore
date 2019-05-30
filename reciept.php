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
?>
<html>

<head>
  <title>Service php</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <div id="payment-form">

    Your Books:<br>
    <table class="Books-table">
      <tbody>
      
        <?php

        // Gets the array with the valid information wich the user bought for.
        $theCsvBooks = $_SESSION['order'];
        // foreach item in the array. Print the array values in td elements
        foreach ($theCsvBooks as $book) {
          ?>
          <tr>
            <td><?php echo ($book[0]); ?></td>
            <td><?php echo ($book[1]); ?></td>
            <td><?php echo ($book[2]); ?></td>
            <td><?php echo ($book[3]); ?></td>
          </tr>
        <?php
      }
      ?>
      </tbody>
    </table>
    <br>
    <hr>
    <br>
    <?php
    /* 
      Checks if the order session is set.
      --true(
        sets filename to the general downloadfile which is "downloadFile.csv"
        Opens the file for writing and sets the everything_is_awesome variable to true if everything was a success.
        foreach item in the array. Print it to the file and then close it.
        if the everything_is_awesome variable is true, then print out a link to download the file.
        else, Print "everything is NOT awesome.
      )

    */
    if (isset($_SESSION['order'])) {
      $filename = 'downloadFile.csv';
      $file_to_write = fopen($filename, 'w');
      $everything_is_awesome = true;
      foreach ($_SESSION['order'] as $book) {
        $everything_is_awesome = $everything_is_awesome && fputcsv($file_to_write, $book);
      }
      fclose($file_to_write);
      if ($everything_is_awesome) {
        echo '<a href="' . $filename . '">Download Your CSV</a>';
      } else {
        echo 'Everything is NOT awesome';
      }
    }
    ?>
  </div>
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