<?php
$errors = array();

$user = $_POST['uname'] ?? null;
$pass = $_POST['psw'] ?? null;

if(isset($_COOKIE['mysitecookie'])){
  $user=$_COOKIE['mysitecookie'];
 }

if (isset($_POST['submit'])) { //only do this code if the form has been submitted
  include 'includes/library.php';
  $pdo = connectDB();
 
  //query for the username
  $query = "select userid, username, password from signup_users where username=?";  
  $stmt=$pdo->prepare($query);
  $results = $stmt->execute([$user]);

  if($row = $stmt->fetch()) {      
    if(password_verify($pass, $row['password'])) {
      session_start();
      $_SESSION['username'] = $user;
      $_SESSION['userid'] = $row['userid'];

      header("Location:mystuffview.php");
      exit();

    } else {
      $errors['login'] = true;
    }
  }  else {
    $errors['login'] = true;
  }
  //set cookie if box checked
  if (isset($_POST['remember']))
  setcookie("mysitecookie",$username,time()+60*60*24*30*12);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $page_title = "Log in"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body class="logincreate">
    <?php include 'includes/header.php';?>
    <section>
    <form action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off">
        <h2>Log in</h2>
        <div class="container">
        <div>
        <label for="uname">Username</label>
        <input id ="uname" type="text" placeholder="Enter Username" name="uname" required value="<?=$user;?>">
          </div>
          <div>
           <div>
        <label for="psw">Password</label>
        <input id = "psw" type="password" placeholder="Enter Password" name="psw" required value="<?=$pass;?>"> 
           </div>
           <div>
             <span class="error <?=!isset($errors['login']) ? 'hidden' : "";?>">Invalid username and/or password</span>
            </div>
           <div>
           <button id="submit" name="submit">Log In</button>
        </div>
        <div>
        <label>Remember me</label>
        <input type="checkbox" name="remember">
        </div>
        <div>
        <span class="psw"><a href="forgot.php">Forgot password</a></span>
        <span><a href="create.php">New account</a></span>
        </div>
      </div>    
    </form>
  </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>
