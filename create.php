<?php
require_once('includes/library.php');
// CREATE ARRAY FOR ERRORS
$errors = array();
// GET AND SANTIZE EACH INPUT
$username = trim(filter_var($_POST['username'] ?? null, FILTER_SANITIZE_STRING));
$password = trim(filter_var($_POST['password'] ?? null, FILTER_SANITIZE_STRING));
$confirmPassword = trim(filter_var($_POST['confirmPassword'] ?? null, FILTER_SANITIZE_STRING));
$email = trim(filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL));
$pictureErrorMessage = "";
$newname = "";
// CHECK AND MOVE PROFILE PICTURE
function checkAndMoveFile($filekey, $sizelimit, $newname) {
  try {
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if(!isset($_FILES[$filekey]['error']) || is_array($_FILES[$filekey]['error'])) {
      throw new RuntimeException('Invalid parameters.');
    }
    // Check Error value.
    switch ($_FILES[$filekey]['error']) {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new RuntimeException('No file sent.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new RuntimeException('Exceeded filesize limit.');
      default:
        throw new RuntimeException('Unknown errors.');
    }
    // You should also check filesize here.
    if ($_FILES[$filekey]['size'] > $sizelimit) {
      throw new RuntimeException('Exceeded filesize limit.');
    }
    // Check the File type  Note: this example assumes image uploaded
    if (exif_imagetype( $_FILES[$filekey]['tmp_name']) != IMAGETYPE_GIF 
      and exif_imagetype( $_FILES[$filekey]['tmp_name']) != IMAGETYPE_JPEG
      and exif_imagetype( $_FILES[$filekey]['tmp_name']) != IMAGETYPE_PNG) {
        throw new RuntimeException('Invalid file format.');
    }
    // $newname should be unique and tested
    if (!move_uploaded_file($_FILES[$filekey]['tmp_name'], $newname)){
      throw new RuntimeException('Failed to move uploaded file.');
    }
    echo 'File is uploaded successfully.';
  }
  catch (RuntimeException $e) { 
    $pictureErrorMessage = $e->getMessage();
    $errors['pictureError'] = true;
  }
}
// ENSURE THAT THERE IS INFORMATION IN $_POST
if (isset($_POST['submit'])) {
  // CONNECT TO THE DATABASE
  $pdo = connectDB();
  // CHECK CREDITIONALS
  // CHECK USERNAME FIELD
  if(empty($username)) { $errors['emptyUsername'] = true; }
  elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) { $errors['usernameChars'] = true; }
  // IF USER ALREADY EXISTS IN THE DATABASE
  $query = "SELECT * FROM `signup_users` WHERE username=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$username]);
  $results = $stmt->fetch();
  if ($results) { $errors['usernameExists'] = true; }
  // CHECK EMAIL FIELD
  if(empty($email)) { $errors['emptyEmail'] = true; }
  elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['incorrectEmail'] = true; }
  // IF EMAIL ALREADY EXISTS IN THE DATABASE
  $query = "SELECT * FROM `signup_users` WHERE email=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$email]);
  $results = $stmt->fetch();
  if ($results) { $errors['emailExists'] = true; }
  // CHECK PASSWORD FIELD
  $upperCase = preg_match('@[A-Z]@', $password);
  $lowerCase = preg_match('@[a-z]@', $password);
  $numberCase = preg_match('@[0-9]@', $password);
  $specialCase = preg_match('@[^\w]@', $password);
  if(empty($password)) { $errors['emptyPassword'] = true; }
  elseif (!$upperCase || !$lowerCase || !$numberCase || !$specialCase || strlen($password) < 8) {
    if (!$upperCase) { $errors['passwordCharsUpper'] = true; }
    if (!$lowerCase) { $errors['passwordCharsLower'] = true; }
    if (!$numberCase) { $errors['passwordCharsNumber'] = true; }
    if (!$specialCase) { $errors['passwordCharsSpecial'] = true; }
    if (!strlen($password) < 8) { $errors['passwordLength'] = true; }
  }
  // CHECK CONFIRM PASSWORD FIELD
  if ($password != $confirmPassword) { $errors['confirmPassword'] = true; }
  // CHECK PROFILE PICTURE
  if (is_uploaded_file($_FILES['profilePicture']['tmp_name'])) {
    // CUSTOMIZE FILE NAME
    $uniqueID = $username;
    $path = WEBROOT."www_data/";
    $fileroot = "user_";
    //get the original file name for extension, where 'fileToProcess' was the name of the file upload form element
    $filename = $_FILES['profilePicture']['name'];
    $exts = explode(".", $filename); // split based on period
    $ext = $exts[count($exts)-1]; //take the last split (contents after last period)
    $filename = $fileroot.$uniqueID.".".$ext;  //build new filename
    $newname = $path.$filename; //add path the file name
  }
  // ERROR VERIFICATION
  if(!sizeof($errors)) {
    // INSERT CREDITIONALS INTO THE DATABASE
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO signup_users(userid, username, email, `password`) VALUES (NULL,?,?,?)";
    $stmt = $pdo->prepare($query)->execute([$username, $email, $password]);
    // UPLOAD FILE TO SERVER
    checkAndMoveFile('profilePicture', 1024000, $newname);
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
      <form action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" enctype="multipart/form-data">
        <h2>Create New Account</h2>
        <div class="container">
          <div>
            <label for="username">Username</label>
            <input id = "username" type="text" placeholder="Username" name="username" autocomplete="new-password" value="<?=$username?>">
            <span class="error <?=!isset($errors['emptyUsername']) ? 'hidden' : "";?>">Please enter a username</span>
            <span class="error <?=!isset($errors['usernameChars']) ? 'hidden' : "";?>">Username must be unique and can only contain letters, numbers, and underscores</span>
            <span class="error <?=!isset($errors['usernameExists']) ? 'hidden' : "";?>">Username taken</span>
          </div>
          <div>
            <label for="email">Your email</label>
            <input id = "email" type="text" placeholder="Email" name="email" value="<?=$email?>">
            <span class="error <?=!isset($errors['emptyEmail']) ? 'hidden' : "";?>">Please enter an email</span>
            <span class="error <?=!isset($errors['incorrectEmail']) ? 'hidden' : "";?>">Please use a valid email address</span>
            <span class="error <?=!isset($errors['emailExists']) ? 'hidden' : "";?>">Email taken</span>
          </div>
          <div>
            <label for="password">Enter a password</label>
            <input id="password" type="password" placeholder="Password" name="password" autocomplete="new-password">
            <span class="error <?=!isset($errors['emptyPassword']) ? 'hidden' : "";?>">Please enter a password</span>
            <span class="error <?=!isset($errors['passwordCharsUpper']) ? 'hidden' : "";?>">Password must contain at least 1 upper case letter</span>
            <span class="error <?=!isset($errors['passwordCharsLower']) ? 'hidden' : "";?>">Password must contain at least 1 lower case letter</span>
            <span class="error <?=!isset($errors['passwordCharsNumber']) ? 'hidden' : "";?>">Password must contain at least 1 number</span>
            <span class="error <?=!isset($errors['passwordCharsSpecial']) ? 'hidden' : "";?>">Password must contain at least 1 special character</span>
            <span class="error <?=!isset($errors['passwordLength']) ? 'hidden' : "";?>">Password must be longer than 8 characters</span>
          </div>
          <div>
            <label for="confirmPassword">Confirm password</label>
            <input id="confirmPassword" type="password" placeholder="Repeat Password" name="confirmPassword">
            <span class="error <?=!isset($errors['confirmPassword']) ? 'hidden' : "";?>">Passwords do not match</span>
          </div>
          <div>
            <input type="hidden" name="MAX_FILE_SIZE" value="1024000"/>
            <label for="profilePicture">Profile Picture</label>
            <input id="profilePicture" type="file" name="profilePicture">
            <span class="error <?=!isset($errors['pictureError']) ? 'hidden' : "";?>">$pictureErrorMessage</span>
          </div>
          <div><button type="submit" name="submit" value="Upload">Create account</button></div>           
        </div>    
      </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>