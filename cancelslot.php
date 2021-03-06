<?php
require "includes/header.php";
// CONNECT TO DATABASE
include 'includes/library.php';
$pdo = connectdb();
$slotid = $_GET['slotid'];
// POST SUBMISSION
if (isset($_POST['submit'])) {
    $query = "DELETE FROM `slot_info` WHERE slotid=?";
    $stmt = $pdo->prepare($query)->execute([$slotid]);
    // REDIRECT
    header("Location:mystuffview.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "Cancel slot"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body class = "reg"> 
    <form id="requestform" action="<?=htmlentities($_SERVER['PHP_SELF']).'?slotid='.$slotid;?>" method="post" novalidate>
      <p>Click the button to cancel your slot</p>
      <button id="submit" name="submit">Cancel slot</button>
      <a href="mystuffview.php">Previous page</a>
    </form>
    <?php include "includes/footer.php" ?>
  </body>
</html>