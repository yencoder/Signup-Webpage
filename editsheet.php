<?php
require 'includes/header.php';
//check session for whatever user info was stored
//if(!isset($_SESSION['username'])){
  //no user info, redirect
//header("Location:login.php");
//exit();
//}
$userid = $_SESSION['userid'];
$sheetid = $_SESSION['sheetid'];
include 'includes/library.php';
$pdo = connectdb();

$query = "SELECT * FROM signin_info where sheetid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$sheetid]);
$sheets = $stmt->fetchAll();

$query = "SELECT * FROM slot_info where sheetid = '?'"; 
$stmt=$pdo->prepare($query);                        
$results = $stmt->execute([$sheetid]);                
$slots = $stmt->fetchAll();
<<<<<<< HEAD
=======

if(isset($_POST['add'])) {
  // ERROR VERIFICATION
  if (!isset($date)) { $errors['date'] = true; }
  if (!isset($title)) { $errors['emptyTitle'] = true; }
  if(count($errors)===0) {
    // RETRIEVE USER EMAIL
    $query = "SELECT `email` FROM `signup_users` WHERE userid=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userid]);
    $results = $stmt->fetch();
    $query = "INSERT into slot_info values (NULL, $sheetid, $userid,?,?,?,?)"; //foreign key
    $stmt = $pdo->prepare($query)->execute([$title, $date, $username, $results['email']]);
  }
}

if(isset($_POST['confirm'])){
    $query = "UPDATE `signin_info`,'slot_info' WHERE sheetid = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$sheetid]);
}
>>>>>>> e7965f8a4527eb7baf83e1071e58085118f3e936
?>



<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/6786f5cbb4.js" crossorigin="anonymous"></script>
    <title>Editing</title>
    <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body>
    <section class="signup">
          <form id="signupform" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
          <h2>
              Edit Your Sign Up Sheet
          </h2>
            <div class="input">
                <label for="title">Sheet Title</label>
                <?php  foreach($sheets as $r): ?>
                <input id="title" name="title" type="text" value="<?= $r['title']; ?>"; />               
             <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a Sheet Title</span>
                <?php endforeach ?>
              </div>
              <div class="input">
                <label for="description">Description</label>
                <?php  foreach($sheets as $r): ?>
                <textarea name="description" id="description" cols="50" rows="5";><?= $r['description']; ?></textarea> 
                <?php endforeach ?>             
              </div>
              <div class="input">
                <label for="date">Time Slot</label>
                <ol>
            <?php  foreach($slots as $r): ?>
                <div>
                <p>Date and time: <?= $r['timeslot']; ?></p>
                <button id="delete" name="delete">Delete</button> </li>
                <?php endforeach ?>
            </ol>
            </div>
                <div>
                <input id="date" name="date" type="datetime-local" placeholder="YYYY-MM-DD"/>
                <span class="error <?=!isset($errors['date']) ? 'hidden' : "";?>">It's Empty!</span>
                <button id="add" name="add">ADD</button>
            </div>
              </div>
              <fieldset>
                <legend>Privacy</legend>    
                <div>
                <input id="public" name="privacy" type="radio" value='Y' 
              <?php if (isset($_POST['privacy']) && $_POST['privacy'] == "Y") echo 'checked="checked"';?>/>
                  <label for="public">Public</label>
                </div>
                <div>
                <input id="private" name="privacy" type="radio" value='N' 
              <?php if (isset($_POST['privacy']) && $_POST['privacy'] == "N") echo 'checked="checked"';?>/>
                  <label for="private">Private</label>
                </div>          
              </fieldset>
              <span class="error <?=!isset($errors['privacy']) ? 'hidden' : "";?>">Please choose one</span>
              <button id="confirm" name='confirm'>CONFIRM YOUR EDITING</button>
          </form>
  </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>
