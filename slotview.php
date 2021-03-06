<?php
require "includes/header.php";
$userid = $_SESSION['userid'];
// CONNECT TO DATABASE
include 'includes/library.php';
$pdo = connectdb();

$slotid = $_GET['slotid'];
// QUERY FOR SLOTINFO
$query = "SELECT * FROM slot_info where slotid = ?"; 
$stmt=$pdo->prepare($query);                        
$results = $stmt->execute([$slotid]);                
$slotinfo = $stmt->fetch(); 
// QUERY FOR SHEETINFO
$sheetid = $slotinfo['sheetid'];
$query = "SELECT * FROM signin_info where sheetid = ?"; 
$stmt=$pdo->prepare($query);                        
$results = $stmt->execute([$sheetid]);                
$sheetinfo = $stmt->fetch(); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "View your timeslot details"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body> 
    <div class="vslot">
      <p>Title: <?php echo "$slotinfo[title]"; ?></p>
      <p>The timeslot you registered for is: <?php echo "$slotinfo[timeslot]"; ?></p>
      <p>Description:</p>
      <p><?php echo "$sheetinfo[description]"; ?></p>
    </div>
    <?php include "includes/footer.php" ?>  
  </body>
</html>