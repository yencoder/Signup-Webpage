<?php
require "includes/header.php";
$userid = $_SESSION['userid'];
$sheetid = $_GET['sheetid'];

// CONNECT TO DATABASE
include 'includes/library.php';
$pdo = connectdb();
// GET SIGNUP SHEET INFO
$query = "SELECT * FROM `signin_info` WHERE sheetid=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$sheetid]);
$results = $stmt->fetch();
// GET SLOT INFO
$query = "SELECT * FROM slot_info where sheetid=?"; 
$stmt=$pdo->prepare($query);                        
$slots = $stmt->execute([$sheetid]);                
$slots = $stmt->fetchAll();

// ERROR VERIFICATION
if (isset($_POST['delete'])) {    
  $query = "DELETE FROM `signin_info` WHERE sheetid=?";
  $stmt = $pdo->prepare($query)->execute([$sheetid]);
  // REDIRECT
  header("Location:mystuffview.php");
  exit();  
} else if (isset($_POST["previous"])) {
  // REDIRECT
  header("Location:mystuffview.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "Delete Sheet"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body>
    <section class="delete">
      <form method="POST">
        <h2>Delete Your Sheet</h2>
        <div>
          <p class="label">Sheet Title</p>
          <p><?php echo $results['title']; ?></p>
        </div>
        <div>
          <p class="label">Description</p>
          <p><?php echo $results['description']; ?></p>
        </div>
        <div>
          <p class="label">Time Slots</p>
          <div class="inforows">
            <?php if($slots==null):?><p>You have not signed up in any slots</p><?php endif ?>
            <?php foreach($slots as $r): ?>
              <div class="sbox">
                <div>
                  <p>Title: <?php echo "$r[title]"; ?></p>
                  <p>Date and time: <?php echo "$r[timeslot]"; ?></p>
                </div>
              </div>
            <?php endforeach ?>
          </div>
        </div>
        <button id="delete" name='delete'>Delete the Sheet </button>
        <button id="previous" name="previous"><a href="mystuffview.php">Previous page</a></button>
      </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>