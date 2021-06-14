<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Create Account</title>
    <link rel="stylesheet" href="styles/logincss.css" />
  </head>
  <body>
  <?php include 'includes/header.php';?>
  <section>
      <form method="post"> 
        <h2>Create New Account</h2>
        <div class="container">
            <div>
          <label for="uname"><b>Unique username</b></label>
          <input id = "uname" type="text" placeholder="Username" name="uname" required>
            </div>
            <div>
          <label for="email"><b>Your email</b></label>
          <input id = "email" type="text" placeholder="Email" name="email" required>
             </div>
             <div>
          <label for="psw"><b>Enter a password</b></label>
          <input id="psw" type="password" placeholder="Password" name="psw" required>
            </div>
            <div>
              <label for="repeat"><b>Repeat to confirm password</b></label>
        <input id="repeat" type="password" placeholder="Repeat Password" name="repeat" required>
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

