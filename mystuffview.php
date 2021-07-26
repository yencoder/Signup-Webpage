<?php
/*session_start(); //start session
//check session for whatever user info was stored
if(!isset($_SESSION['username'])){
  //no user info, redirect
header("Location:login.php");
exit();
}*/
//----------------------------------------ADDED THIS HERE TO MAKE SOME THINGS WORK, PLEASE DOUBLE CHECK-------------------------------
require "includes/header.php";
$userid = $_SESSION['userid'];

include 'includes/library.php';
$pdo = connectdb();
$query = "SELECT * FROM signin_info where creatorid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$userid]);
$sheets = $stmt->fetchAll();

$query = "SELECT * FROM slot_info where userid = ?"; 
$stmt=$pdo->prepare($query);                        
$results = $stmt->execute([$userid]);                
$slots = $stmt->fetchAll(); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "My Stuff View"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body class="mystuff">
  <!------------------------------------REMOVED THIS TO MAKE THINGS WORK ABOVE, PLEASE DOUBLE CHECK-------------------------------------------->
  <!-- <?php include 'includes/header.php';?>    -->
  <!-- For each of the users sign up sheets, display Title, number of slots and number of people signed up.
   Add icon based links for View, edit, delete and copy. -->
    <section>
      <h2>My Sign-Up Sheets</h2>
      <?php  foreach($sheets as $r): ?>     <!--add to account for people with no sheets or slots -->
    <section>
      <div>
        <div class="sbox">
          <div>
            <?php  ?>
            <p>Title: <?php echo "$r[title]"; ?></p>
            <p>Total slots: <?php echo "$r[numofslots]"; ?></p>
            <p>Number of people signed up: <?php echo "$r[numofpeoplesignedup]"; ?></p>
          </div>
        <div>
        <a href="viewsheet.php"><img src="images/view.png" alt="view sheet" title="view"></a>
        <a href="editsheet.php"><img src="images/edit.png" alt="edit sheet" title="edit sheet"></a>    
        <a href="deletesheet.php"><img src="images/delete.png" alt="delete sheet" title="delete sheet"></a>
        <div>
        </div>  
      </div> 
    </section>    
   <?php endforeach ?>  
   <!-- Display only if not expired. For each slot user signed up for, display the Title, date and the time.
   Add icon based links for View details and cancel slot. -->
   <h2>My Signed-Up Slots</h2>
   <?php  foreach($slots as $r): ?>
    <section>
    <div>
    <div class="sbox">
      <div>
     <p>Title: <?php echo "$r[title]"; ?></p>
     <p>Date and time: <?php echo "$r[timeslot]"; ?></p>
   </div>
   <div>
     <a href=""><img src="images/view.png" alt="view details" title="view details"></a>
     <a href=""><img src="images/delete.png" alt="cancel slot" title="cancel slot"></a>
     <div>
     </div>  
   </div> 
   </section> 
   <?php endforeach ?>
   </section>  
    <?php include "includes/footer.php" ?>  
    </body>
</html>
