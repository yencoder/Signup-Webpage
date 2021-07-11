<?php
require_once('includes/library.php');

// CREATE ARRAY FOR ERRORS
$errors = array();

// GET AND SANTIZE EACH INPUT
$username = filter_var($_POST['username'] ?? null, FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'] ?? null, FILTER_SANITIZE_STRING);
$confirmPassword = filter_var($_POST['confirmPassword'] ?? null, FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL);
$unique = false;

// ENSURE THAT THERE IS INFORMATION IN $_POST
if (isset($_POST['submit'])) {
    // CONNECT TO THE DATABASE
    $pdo = connectDB();

    // CHECK THE DATABASE FOR THE USER
    $query = "SELECT * FROM `signup_users` WHERE username=?, email=?, password=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $email, $password]);
    $results = $stmt->fetch();

    // CHECK CREDITIONALS
    if (isset($_POST['submit'])) {
      // CHECK USERNAME FIELD
      if(empty(trim($username))) {
        $errors['emptyUsername'] = true;
      }
      elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        $errors['usernameChars'] = true;
      }
      // IF USER ALREADY EXISTS IN THE DATABASE
      if ($results['username'] === false) {
        $unique = true;
      }
      else {
        $errors['usernameExists'] = true;
      }
      
      // CHECK EMAIL FIELD
      if(empty(trim($email))) {
        $errors['emptyEmail'] = true;
      }
      // IF EMAIL EXISTS ALREADY EXISTS IN THE DATABASE
      if ($results['email'] === false) {
        $unique = true;
      } 
      else {
        $errors['emailExists'] = true;
      }

      // CHECK PASSWORD FIELD
      if(empty(trim($password))) {
        $errors['emptyPassword'] = true;
      }
      elseif (strlen(trim($password)) < 8) {
        $errors['passwordLength'] = true;
      }

      // CHECK CONFIRM PASSWORD FIELD
      if ($password != $confirmPassword) {
        $errors['confirmPassword'] = true;
      }
      
      // PASSWORD VERIFY
      if(!sizeof($errors) && $unique == true) {

        // INSERT INFO INTO THE DATABASE
        $query = "INSERT INTO signup_users(userid, username, email, password) VALUES (default,?,?,?)";
        $stmt = $pdo->prepare($query)->execute(['userid', $username, $email, $password]);

        // START SESSION
        session_start();
        $_SESSION['username'] = $results['username'];
        $_SESSION['userid'] = $results['userid'];

        // REDIRECT
        header("Location: home.php");
        exit();
      }
    }
    else {
      $errors = ['login'];
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
      <form method="post"> 
        <h2>Create New Account</h2>
        <div class="container">
          <div>
            <label for="uname"><b>Username</b></label>
            <input id = "uname" type="text" placeholder="Username" name="uname">
            <span class="error <?=!isset($errors['emptyUsername']) ? 'hidden' : "";?>">Please enter a username</span>
            <span class="error <?=!isset($errors['usernameChars']) ? 'hidden' : "";?>">Username must be unique and can only contain letters, numbers, and underscores</span>
            <span class="error <?=!isset($errors['usernameExists']) ? 'hidden' : "";?>">Username taken</span>
          </div>
          <div>
            <label for="email"><b>Your email</b></label>
            <input id = "email" type="text" placeholder="Email" name="email">
            <span class="error <?=!isset($errors['emptyEmail']) ? 'hidden' : "";?>">Please enter an email</span>
            <span class="error <?=!isset($errors['emailExists']) ? 'hidden' : "";?>">Email taken</span>
          </div>
          <div>
            <label for="psw"><b>Enter a password</b></label>
            <input id="psw" type="password" placeholder="Password" name="psw">
            <span class="error <?=!isset($errors['emptyPassword']) ? 'hidden' : "";?>">Please enter a password</span>
            <span class="error <?=!isset($errors['passwordLength']) ? 'hidden' : "";?>">Password must be longer than 8 characters</span>
          </div>
          <div>
            <label for="repeat"><b>Repeat to confirm password</b></label>
            <input id="repeat" type="password" placeholder="Repeat Password" name="repeat">
            <span class="error <?=!isset($errors['confirmPassword']) ? 'hidden' : "";?>">Password do not match</span>
          </div>
          <div>
            <button type="submit">Create account</button>
            <span class="error <?=!isset($errors['login']) ? 'hidden' : "";?>">Something went wrong, please try again</span>
          </div>           
        </div>    
      </form>
  </section>
      <?php include "includes/footer.php" ?>
  </body>
</html>