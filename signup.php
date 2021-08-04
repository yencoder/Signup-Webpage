<?php
require 'includes/header.php';
include 'includes/library.php';
$pdo = connectdb();
if ($_SESSION == null) {$_SESSION['userid'] = 0;}
$userid = $_SESSION['userid'];

$errors = array(); //declare empty array to add errors too
//get name from post or set to NULL if doesn't exist
$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;
$privacy = $_POST['status'] ?? null;

if (isset($_POST['save'])) { 
  //validate user has entered a name
  if (!isset($title) || strlen($title) === 0) {
    $errors['title'] = true;
  }
  //validate user has entered some description
  if (!isset($description) || strlen($description) === 0) {
    $errors['description'] = true;
  }
  if (empty($privacy)) {
    $errors['privacy'] = true;
  }
  //saved page
  if(count($errors)=== 0){
    // $qry = "SELECT * FROM signup_users WHERE userid = ?";
    //$stmt = $pdo->prepare($qry)->execute;
    $query = "INSERT into signin_info values (NULL,?,?,$userid,?,NULL,NULL,Now())"; 
    //prepare & execute query
    $stmt = $pdo->prepare($query)->execute([$title,$description,$privacy]);
    $_SESSION['sheetid']=$pdo->lastInsertId();
    header("Location: timeslot.php");  
    exit;
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
    <section class="signup">
          <form id="signupform" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
          <h2>Create Your Sign Up Sheet</h2>
          <h3>*****Step One*****</h3>
            <div class="input">
                <label for="title">Sheet Title</label>
                <input id="title" name="title" type="text" placeholder="COIS 3420 Project" value="<?=$title?>"/>
                <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a sheet title</span>
              </div>
              <div class="input">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="50" rows="5" value="<?=$description?>"></textarea>
                <span class="error <?=!isset($errors['description']) ? 'hidden' : "";?>">Please enter your description</span>
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
              <button id="save" name='save'>Save </button>
          </form>
  </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>
