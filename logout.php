<?php
// START SESSION
session_start();
// CLEAR SESSION
$_SESSION = array();
// END SESSION
session_destroy();
if (isset($_POST["submit"])) {
  // REDIRECT
  header("Location: home.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "Logout"; ?>
    <?php include "includes/metadata.php" ?>
    <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body class="logout">
    <?php include 'includes/header.php';?>
    <section>
        <form method="POST">
            <h2>You have been logged out</h2>
            <button type="submit" name="submit">Thank you!</button>
        </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>