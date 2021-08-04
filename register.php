<?php

session_start(); //start session
include 'includes/library.php';
$pdo = connectdb();
  //check session for whatever user info was stored
$slotid = $_GET['slotid'];
if(isset($_SESSION['username'])){
    //no user info, redirect
    
$userid = $_SESSION['userid'];   
    
$query = "SELECT * FROM signup_users where userid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$userid]);
$userinfo = $stmt->fetch();
$email=$userinfo['email'];
$username=$userinfo['username'];
   
$query = "UPDATE slot_info set user=?, useremail=? where slotid=?";
$stmt = $pdo->prepare($query)->execute([$username, $email, $slotid]);

  header("Location:mystuffview.php");
  exit();
}
  $errors = array();
  $name = $_POST['name'] ?? null;
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $email = $_POST['email'] ?? null;
  $email = filter_var($email, FILTER_SANITIZE_STRING);

  if (isset($_POST['submit'])) {
    if(empty($name)) {
      $errors['name'] = true;
    }
    if(empty($email)) {
      $errors['emptyEmail'] = true;
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['incorrectEmail'] = true;
    }

    if(!sizeof($errors)) {      
      $query = "UPDATE slot_info SET user=?, useremail=? WHERE slotid=?";
      $stmt = $pdo->prepare($query)->execute([$name, $email, $slotid]);
      // REDIRECT
      header("Location:home.php");
      exit();
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $page_title = "Register"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body class = "reg"> 
    <?php include 'includes/header.php'?>    
    <form id="requestform" action="<?=htmlentities($_SERVER['PHP_SELF']).'?slotid='.$slotid;?>" method="post" novalidate>
    <h2>Register for slot</h2>
    <div>
    <label for="name">Name:</label>
            <input
              id="name"
              name="name"
              type="text"
              value="<?=$name?>" />
            <span class="error <?=!isset($errors['name']) ? 'hidden' : "";?>">Please enter your name</span>
    </div>
    <div>
          <label for="email">Email:</label>
            <input
              id="email"
              name="email"
              type="text"
              value="<?=$email?>" />
              <span class="error <?=!isset($errors['emptyEmail']) ? 'hidden' : "";?>">Please enter an email</span>
            <span class="error <?=!isset($errors['incorrectEmail']) ? 'hidden' : "";?>">Please use a valid email address</span>
    </div>          

    <button id="submit" name="submit">Register</button>
    </form>
    <?php include "includes/footer.php" ?>
  </body>
</html>