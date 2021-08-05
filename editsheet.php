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
                <textarea name="description" id="description" cols="50" rows="5";><?=$description;?></textarea>              
              </div>
              <div class="input">
                <label for="date">Time Slot</label>
                <ol>
            <?php  foreach($slots as $r): ?>
                <div>
                <li>Date and time: <?= $r['timeslot']; ?>
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
                  <input id="public" name="status" type="radio" value="Y" <?=$privacy == "Y" ? 'checked' : ''?> />
                  <label for="public">Public</label>
                </div>
                <div>
                  <input id="private" name="status" type="radio" value="N" <?=$privacy == "N" ? 'checked' : ''?> />
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
