<?php
$errors = array();

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$confirmPassword = $_POST['confirmPassword'] ?? null;
$email = $_POST['email'] ?? null;
$unique = false;

// ENSURE THAT THERE IS INFORMATION IN $_POST
if (isset($_POST['submit'])) {
    // CONNECT TO THE DATABASE
    $pdo = connectDB();

    // CHECK THE DATABASE FOR THE USER
    $query = "SELECT id,username, password FROM `cois3420_users` WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $results = $stmt->fetch();

    // CHECK CREDITIONALS
    if (isset($_POST['submit'])) {
      // CHECK USERNAME
      if(empty(trim($username))) {
        $errors = ['emptyUsername'];
      }
      elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        $errors = ['usernameChars'];
      }
      // IF USER ALREADY EXISTS
      elseif ($results === false) {
        $unique = true;
      }
      else {
        $unique = false;
        $errors = ['usernameExists'];
      }
      
      // CHECK EMAIL
      if(empty(trim($email))) {
        $errors = ['emptyEmail'];
      }
      $query = "SELECT id,email, password FROM `cois3420_users` WHERE email = ?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$email]);
      $results = $stmt->fetch();
      // IF EMAIL EXISTS
      if ($results === false) {
        $unique = true;
      } else {
        $unique = false;
        $errors = ['emailExists'];
      }
      // CHECK PASSWORD
      if(empty(trim($password))) {
        $errors = ['emptyPassword'];
      }
      elseif (strlen(trim($password)) < 8) {
        $errors = ['passwordLength'];
      }

      // CHECK CONFIRM PASSWORD
      if ($password != $confirmPassword) {
        $errors = ['confirmPassword'];
      }
    }

    // IF USER LOGGED IN SUCCESSFULLY
    if (password_verify($password, $results['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['userid'] = $results['id'];

        header("Location: list.php");
        exit();
    }

    // IF PASSWORD IS INCORRECT
    else {
        $errors['login'] = true;
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
            <label for="uname"><b>Unique username</b></label>
            <input id = "uname" type="text" placeholder="Username" name="uname" required>
            <span class="error <?=!isset($errors['emptyUsername']) ? 'hidden' : "";?>">Please enter a username</span>
            <span class="error <?=!isset($errors['usernameChars']) ? 'hidden' : "";?>">Username must be unique and can only contain letters, numbers, and underscores</span>
            <span class="error <?=!isset($errors['usernameExists']) ? 'hidden' : "";?>">Username taken</span>
          </div>
          <div>
            <label for="email"><b>Your email</b></label>
            <input id = "email" type="text" placeholder="Email" name="email" required>
            <span class="error <?=!isset($errors['emptyEmail']) ? 'hidden' : "";?>">Please enter an email</span>
            <span class="error <?=!isset($errors['emailExists']) ? 'hidden' : "";?>">Email taken</span>
          </div>
          <div>
            <label for="psw"><b>Enter a password</b></label>
            <input id="psw" type="password" placeholder="Password" name="psw" required>
            <span class="error <?=!isset($errors['emptyPassword']) ? 'hidden' : "";?>">Please enter a password</span>
            <span class="error <?=!isset($errors['passwordLength']) ? 'hidden' : "";?>">Password must be longer than 8 characters</span>
          </div>
          <div>
            <label for="repeat"><b>Repeat to confirm password</b></label>
            <input id="repeat" type="password" placeholder="Repeat Password" name="repeat" required>
            <span class="error <?=!isset($errors['confirmPassword']) ? 'hidden' : "";?>">Password do not match!</span>
          </div>
          <div>
            <button type="submit">Create account</button>
          </div>           
        </div>    
      </form>
  </section>
      <?php include "includes/footer.php" ?>
  </body>
</html>