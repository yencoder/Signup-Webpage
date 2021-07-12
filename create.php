<?php
require_once('includes/library.php');

// CREATE ARRAY FOR ERRORS
$errors = array();

// GET AND SANTIZE EACH INPUT
$username = filter_var($_POST['username'] ?? null, FILTER_SANITIZE_STRING);
// encrypt password with a hash on the database as well 
$password = filter_var($_POST['password'] ?? null, FILTER_SANITIZE_STRING);
$confirmPassword = filter_var($_POST['confirmPassword'] ?? null, FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL);

// ENSURE THAT THERE IS INFORMATION IN $_POST
if (isset($_POST['submit'])) {
    // CONNECT TO THE DATABASE
    $pdo = connectDB();

    // CHECK THE DATABASE FOR THE USER
    $query = "SELECT * FROM `signup_users` WHERE username=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $results = $stmt->fetch();

    // CHECK CREDITIONALS
      // CHECK USERNAME FIELD
      if(empty(trim($username))) {
        $errors['emptyUsername'] = true;
      }
      elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        $errors['usernameChars'] = true;
      }
      // IF USER ALREADY EXISTS IN THE DATABASE
      if ($results) {
        $errors['usernameExists'] = true;
      }
      
      // CHECK EMAIL FIELD
      if(empty(trim($email))) {
        $errors['emptyEmail'] = true;
      }
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['incorrectEmail'] = true; // change errror name
      }
      // IF EMAIL EXISTS ALREADY EXISTS IN THE DATABASE
      $query = "SELECT * FROM `signup_users` WHERE email=?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$email]);
      $results = $stmt->fetch();
      if ($results) {
        $errors['emailExists'] = true;
      }

      // CHECK PASSWORD FIELD
      if(empty(trim($password))) {
        $errors['emptyPassword'] = true;
      }
      elseif (strlen(trim($password)) < 8) {
        $errors['passwordLength'] = true;
      }
// use regular expression to filter password to ensure at least 1 caps and 1 numeric

      // CHECK CONFIRM PASSWORD FIELD
      if ($password != $confirmPassword) {
        $errors['confirmPassword'] = true;
      }
      
      // PASSWORD VERIFY
      if(!sizeof($errors)) {

        // INSERT INFO INTO THE DATABASE
// hash password before writing it to the database
        $query = "INSERT INTO signup_users(userid, username, email, `password`) VALUES (NULL,?,?, ?)";
        $stmt = $pdo->prepare($query)->execute([$username, $email, $password]);
        
        // REDIRECT
        header("Location: login.php");
        exit();
      }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "Create Account"; ?>
    <?php include "includes/metadata.php" ?>
    <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body class="logincreate">
    <?php include 'includes/header.php';?>
    <section>
      <form action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST">
        <h2>Create New Account</h2>
        <div class="container">
          <div>
            <label for="username"><b>Username</b></label>
            <input id = "username" type="text" placeholder="Username" name="username" autocomplete="new-password" value="<?=$username?>">
            <span class="error <?=!isset($errors['emptyUsername']) ? 'hidden' : "";?>">Please enter a username</span>
            <span class="error <?=!isset($errors['usernameChars']) ? 'hidden' : "";?>">Username must be unique and can only contain letters, numbers, and underscores</span>
            <span class="error <?=!isset($errors['usernameExists']) ? 'hidden' : "";?>">Username taken</span>
          </div>
          <div>
            <label for="email"><b>Your email</b></label>
            <input id = "email" type="text" placeholder="Email" name="email" value="<?=$email?>">
            <span class="error <?=!isset($errors['emptyEmail']) ? 'hidden' : "";?>">Please enter an email</span>
            <span class="error <?=!isset($errors['incorrectEmail']) ? 'hidden' : "";?>">Please use a valid email address</span>
            <span class="error <?=!isset($errors['emailExists']) ? 'hidden' : "";?>">Email taken</span>
          </div>
          <div>
            <label for="password"><b>Enter a password</b></label>
            <input id="password" type="password" placeholder="Password" name="password" autocomplete="new-password">
            <span class="error <?=!isset($errors['emptyPassword']) ? 'hidden' : "";?>">Please enter a password</span>
            <span class="error <?=!isset($errors['passwordLength']) ? 'hidden' : "";?>">Password must be longer than 8 characters</span>
          </div>
          <div>
            <label for="confirmPassword"><b>Repeat to confirm password</b></label>
            <input id="confirmPassword" type="password" placeholder="Repeat Password" name="confirmPassword">
            <span class="error <?=!isset($errors['confirmPassword']) ? 'hidden' : "";?>">Password do not match</span>
          </div>
          <div>
            <button type="submit" name="submit">Create account</button>
          </div>           
        </div>    
      </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>