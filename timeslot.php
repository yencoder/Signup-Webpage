<?php 
require 'includes/header.php';
$userid = $_SESSION['userid'];
$sheetid = $_SESSION['sheetid'];
include 'includes/library.php';
$pdo = connectdb();

$errors = array(); 
$date = $_POST['date'] ?? null;

if(isset($_POST['add'])){
    if (!isset($date)) {
      $errors['date'] = true;
  }
  if(count($errors)=== 0){
    $query = "INSERT into slot_info values (NULL,$sheetid,$userid,?)"; //foreign key
    //prepare & execute query
    $stmt = $pdo->prepare($query)->execute([$date]);
  }
  }


?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/6786f5cbb4.js" crossorigin="anonymous"></script>
    <title>Sign Up Sheet</title>
    <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body>
    <section class="timeslot">
          <form id="signupform" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
          <h2>
              Create Your Sign Up Sheet
          </h2>
          <h3>
            *****Step Two*****
          </h3>
          <div class="input">
                <label for="date">Time Slot</label>
                <input id="date" name="date" type="datetime-local" placeholder="YYYY-MM-DD"/>
                <span class="error <?=!isset($errors['date']) ? 'hidden' : "";?>">It's Empty!</span>
                <button id="add" name="add">ADD</button>
              </div> 
              <button id="steptwo" name='steptwo'>Save </button>         
          </form>
  </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>
