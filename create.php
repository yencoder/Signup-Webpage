<?php
require_once('includes/library.php');

// CREATE ARRAY FOR ERRORS
$errors = array();

// GET AND SANTIZE EACH INPUT
$username = trim(filter_var($_POST['username'] ?? null, FILTER_SANITIZE_STRING));
$password = trim(filter_var($_POST['password'] ?? null, FILTER_SANITIZE_STRING));
$confirmPassword = trim(filter_var($_POST['confirmPassword'] ?? null, FILTER_SANITIZE_STRING));
$email = trim(filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL));

// ENSURE THAT THERE IS INFORMATION IN $_POST
if (isset($_POST['submit'])) {
  // CONNECT TO THE DATABASE
  $pdo = connectDB();

  // CHECK CREDITIONALS
  // CHECK USERNAME FIELD
  if(empty($username)) {
    $errors['emptyUsername'] = true;
  }
  elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors['usernameChars'] = true;
  }
  // IF USER ALREADY EXISTS IN THE DATABASE
  $query = "SELECT * FROM `signup_users` WHERE username=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$username]);
  $results = $stmt->fetch();
  if ($results) {
    $errors['usernameExists'] = true;
  }
  
  // CHECK EMAIL FIELD
  if(empty($email)) {
    $errors['emptyEmail'] = true;
  }
  elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['incorrectEmail'] = true;
  }
  // IF EMAIL ALREADY EXISTS IN THE DATABASE
  $query = "SELECT * FROM `signup_users` WHERE email=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$email]);
  $results = $stmt->fetch();
  if ($results) {
    $errors['emailExists'] = true;
  }
  
  // CHECK PASSWORD FIELD
  $upperCase = preg_match('@[A-Z]@', $password);
  $lowerCase = preg_match('@[a-z]@', $password);
  $numberCase = preg_match('@[0-9]@', $password);
  $specialCase = preg_match('@[^\w]@', $password);
  if(empty($password)) {
    $errors['emptyPassword'] = true;
  }
  elseif (!$upperCase || !$lowerCase || !$numberCase || !$specialCase || strlen($password) < 8) {
    if (!$upperCase) { $errors['passwordCharsUpper'] = true; }
    if (!$lowerCase) { $errors['passwordCharsLower'] = true; }
    if (!$numberCase) { $errors['passwordCharsNumber'] = true; }
    if (!$specialCase) { $errors['passwordCharsSpecial'] = true; }
    if (!strlen($password) < 8) { $errors['passwordLength'] = true; }
  }
  // CHECK CONFIRM PASSWORD FIELD
  if ($password != $confirmPassword) {
    $errors['confirmPassword'] = true;
  }

  // ERROR VERIFICATION
  if(!sizeof($errors)) {
    // INSERT CREDITIONALS INTO THE DATABASE
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO signup_users(userid, username, email, `password`) VALUES (NULL,?,?,?)";
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
            <span class="error <?=!isset($errors['passwordCharsUpper']) ? 'hidden' : "";?>">Password must contain at least 1 upper case letter</span>
            <span class="error <?=!isset($errors['passwordCharsLower']) ? 'hidden' : "";?>">Password must contain at least 1 lower case letter</span>
            <span class="error <?=!isset($errors['passwordCharsNumber']) ? 'hidden' : "";?>">Password must contain at least 1 number</span>
            <span class="error <?=!isset($errors['passwordCharsSpecial']) ? 'hidden' : "";?>">Password must contain at least 1 special character</span>
            <span class="error <?=!isset($errors['passwordLength']) ? 'hidden' : "";?>">Password must be longer than 8 characters</span>
          </div>
          <div>
            <label for="confirmPassword"><b>Confirm password</b></label>
            <input id="confirmPassword" type="password" placeholder="Repeat Password" name="confirmPassword">
            <span class="error <?=!isset($errors['confirmPassword']) ? 'hidden' : "";?>">Passwords do not match</span>
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
