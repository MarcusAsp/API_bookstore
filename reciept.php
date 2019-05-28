<?php
session_start();
require_once('includes/user.inc.php');
require_once('includes/convert.inc.php');
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
        <tr>
          <th>ISBN</th>
          <th>Book title</th>
          <th>Author</th>
          <th>Publisher</th>
        </tr>
        <?php
        $theCsvBooks = $_SESSION['order'];
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