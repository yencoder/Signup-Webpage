<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Log in</title>
    <link rel="stylesheet" href="styles/logincss.css" />
  </head>
  <body>
  <?php include 'includes/header.php';?>
  <section>
    <form method="post"> 
      <h2>Log in</h2>
      <div class="container">
          <div>
        <label for="uname"><b>Username</b></label>
        <input id ="uname" type="text" placeholder="Enter Username" name="uname" required>
          </div>
          <div>
        <label for="email"><b>Email</b></label>
        <input id ="email" type="text" placeholder="Enter Email" name="email" required>
           </div>
           <div>
        <label for="psw"><b>Password</b></label>
        <input id = "psw" type="password" placeholder="Enter Password" name="psw" required>
          </div>
          <div>
        <button type="submit">Login</button>
        </div>
        <div>
        <label>
          <input type="checkbox" name="remember"> Remember me
        </label>
        </div>
        <div>
        <span class="psw"><a href="#">Forgot password</a></span>
        <span><a href="create.php">New account</a></span>
        </div>
      </div>    
    </form>
  </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>