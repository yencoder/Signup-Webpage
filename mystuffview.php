<?php
require "includes/header.php";
$userid = $_SESSION['userid'];
include 'includes/library.php';
$pdo = connectdb();

$query = "SELECT * FROM signin_info where userid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$userid]);
$sheets = $stmt->fetchAll();

$username = $_SESSION['username'];
$query = "SELECT * FROM slot_info where user = ?"; 
$stmt=$pdo->prepare($query);                        
$results = $stmt->execute([$username]);                
$slots = $stmt->fetchAll(); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $page_title = "My stuff view"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body class="mystuff">  
   <section>
   <h2>My Sign-Up Sheets</h2>
   <?php  if($sheets==null):?>
   <p>You have not made any signup sheets</p>
   <?php endif ?>
   <?php foreach($sheets as $r): ?>     
  <div class="inforows">
    <div class="sbox">
    <div>
     <p>Title: <?php echo "$r[title]"; ?></p>
     <p>Total slots: <?php echo "$r[numofslots]"; ?></p>
     <p>Number of people signed up: <?php echo "$r[numofpeoplesignedup]"; ?></p>
     </div>
     <div>
     <a href="viewsheet.php?sheetid=<?php echo $r['sheetid']; ?>"><img src="images/view.png" alt="view sheet" title="view"></a>
     <a href="editsheet.php"><img src="images/edit.png" alt="edit sheet" title="edit sheet"></a>    
     <a href="deletesheet.php"><img src="images/delete.png" alt="delete sheet" title="delete sheet"></a>
     </div>
     </div>  
   </div>    
   <?php endforeach ?>  
   <h2>My Signed-Up Slots</h2>
   <?php  if($slots==null):?>
   <p>You have not signed up in any slots</p>
   <?php endif ?>
   <?php foreach($slots as $r): ?>
    <div class="inforows">
    <div class="sbox">
      <div>
     <p>Title: <?php echo "$r[title]"; ?></p>
     <p>Date and time: <?php echo "$r[timeslot]"; ?></p>
   </div>
   <div>
     <a href="slotview.php?slotid=<?php echo $r['slotid']; ?>"><img src="images/view.png" alt="view details" title="view details"></a> <!--DO THIS!! -->
     <a href="cancelslot.php?slotid=<?php echo $r['slotid']; ?>"><img src="images/delete.png" alt="cancel slot" title="cancel slot"></a>
   </div>
   </div> 
   </div> 
   <?php endforeach ?>
   </section>  
    <?php include "includes/footer.php" ?>  
    </body>
</html>
