<?php
require 'includes/header.php';
// CONNECT TO DATABASE
include 'includes/library.php';
$pdo = connectdb();
if ($_SESSION == null) {$_SESSION['userid'] = 0;}
$userid = $_SESSION['userid'];
// CREATE ARRAY FOR ERRORS
$errors = array();
// GET AND SANTIZE EACH INPUT
$title = trim(filter_var($_POST['title'] ?? null, FILTER_SANITIZE_STRING));
$description = trim(filter_var($_POST['description'] ?? null, FILTER_SANITIZE_STRING));
$privacy = trim(filter_var($_POST['privacy'] ?? null, FILTER_SANITIZE_STRING));
// POST SUBMISSION
if (isset($_POST['save'])) { 
  // CHECK TITLE FIELD
  if (!isset($title) || strlen($title) === 0) { $errors['title'] = true; }
  // CHECK DESCRIPTION FIELD
  if (!isset($description) || strlen($description) === 0) { $errors['description'] = true; }
  // CHECK PRIVACY FIELD
  if (empty($privacy)) { $errors['privacy'] = true; }
  // ERROR VERIFICATION
  if(count($errors)=== 0) {
    $query = "INSERT into signin_info values (NULL,?,?,$userid,?,NULL,NULL,Now())"; 
    $stmt = $pdo->prepare($query)->execute([$title,$description,$privacy]);
    $_SESSION['sheetid']=$pdo->lastInsertId();
    // REDIRECT
    header("Location: timeslot.php");  
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "Sign Up Sheet"; ?>
    <script src="https://kit.fontawesome.com/6786f5cbb4.js" crossorigin="anonymous"></script>
    <?php include "includes/metadata.php" ?>
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