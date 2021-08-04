<?php
require_once('includes/library.php');
// CREATE ARRAY FOR ERRORS
$errors = array();
// GET AND SANTIZE EACH INPUT
$email = trim(filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL));
$password = trim(filter_var($_POST['password'] ?? null, FILTER_SANITIZE_STRING));
$confirmPassword = trim(filter_var($_POST['confirmPassword'] ?? null, FILTER_SANITIZE_STRING));
// ENSURE THAT THERE IS INFORMATION IN $_POST
if (isset($_POST['submit'])) {
  // CONNECT TO THE DATABASE
  $pdo = connectDB();
  // CHECK CREDITIONALS  
  $query = "SELECT email, `password` FROM `signup_users` WHERE email=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$email]);
  $results = $stmt->fetch();
  // CHECK EMAIL FIELD
  if(empty($email)) { $errors['emptyEmail'] = true; }
  else {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['incorrectEmail'] = true; }
    // IF EMAIL DOES NOT EXIST IN THE DATABASE
    elseif (!$results) { $errors['emailDoesNotExist'] = true; }
    // IF EMAIL EXISTS IN THE DATABASE
    else {
      // CHECK PASSWORD FIELD
      $upperCase = preg_match('@[A-Z]@', $password);
      $lowerCase = preg_match('@[a-z]@', $password);
      $numberCase = preg_match('@[0-9]@', $password);
      $specialCase = preg_match('@[^\w]@', $password);
      if(empty($password)) { $errors['emptyPassword'] = true; }
      else {
        // IF SAME AS OLD PASSWORD
        if (password_verify($password, $results['password'])) { $errors['oldPassword'] = true; }
        elseif (!$upperCase || !$lowerCase || !$numberCase || !$specialCase || strlen($password) < 8) {
          if (!$upperCase) { $errors['passwordCharsUpper'] = true; }
          if (!$lowerCase) { $errors['passwordCharsLower'] = true; }
          if (!$numberCase) { $errors['passwordCharsNumber'] = true; }
          if (!$specialCase) { $errors['passwordCharsSpecial'] = true; }
          if (!strlen($password) < 8) { $errors['passwordLength'] = true; }
        }
      }
      // CHECK CONFIRM PASSWORD FIELD
      if ($password != $confirmPassword) { $errors['confirmPassword'] = true; }
    }
  }
  // ERROR VERIFICATION
  if(!sizeof($errors)) {
    // INSERT CREDITIONALS INTO THE DATABASE
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE signup_users SET `password` = ? WHERE email = ?";
    $stmt = $pdo->prepare($query)->execute([$password, $email]);
    // REDIRECT
    header("Location: login.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "Reset Password"; ?>
    <?php include "includes/metadata.php" ?>
    <link rel="stylesheet" href="styles/master.css"/>
  </head>
  <body class="logincreate">
    <?php include 'includes/header.php';?>
    <section>
      <form action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST">
        <h2>Reset Password</h2>
        <div class="container">
          <div>
            <label for="email"><b>Your email</b></label>
            <input id = "email" type="text" placeholder="Email" name="email" value="<?=$email?>">
            <span class="error <?=!isset($errors['emptyEmail']) ? 'hidden' : "";?>">Please enter an email</span>
            <span class="error <?=!isset($errors['incorrectEmail']) ? 'hidden' : "";?>">Please use a valid email address</span>
            <span class="error <?=!isset($errors['emailDoesNotExist']) ? 'hidden' : "";?>">Email does not exist</span>
          </div>
          <div>
            <label for="password"><b>Enter a password</b></label>
            <input id="password" type="password" placeholder="Password" name="password" autocomplete="new-password">
            <span class="error <?=!isset($errors['emptyPassword']) ? 'hidden' : "";?>">Please enter a new password</span>
            <span class="error <?=!isset($errors['passwordCharsUpper']) ? 'hidden' : "";?>">Password must contain at least 1 upper case letter</span>
            <span class="error <?=!isset($errors['passwordCharsLower']) ? 'hidden' : "";?>">Password must contain at least 1 lower case letter</span>
            <span class="error <?=!isset($errors['passwordCharsNumber']) ? 'hidden' : "";?>">Password must contain at least 1 number</span>
            <span class="error <?=!isset($errors['passwordCharsSpecial']) ? 'hidden' : "";?>">Password must contain at least 1 special character</span>
            <span class="error <?=!isset($errors['passwordLength']) ? 'hidden' : "";?>">Password must be longer than 8 characters</span>
            <span class="error <?=!isset($errors['oldPassword']) ? 'hidden' : "";?>">Cannot use previously used passwords</span>
          </div>
          <div>
            <label for="confirmPassword"><b>Confirm password</b></label>
            <input id="confirmPassword" type="password" placeholder="Repeat Password" name="confirmPassword">
            <span class="error <?=!isset($errors['confirmPassword']) ? 'hidden' : "";?>">Passwords do not match</span>
          </div>
          <div>
            <button type="submit" name="submit">Reset Password</button>
          </div>           
        </div>
      </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>