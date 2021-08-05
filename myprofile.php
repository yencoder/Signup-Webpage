<?php
require "includes/header.php";
$userid = $_SESSION['userid'];
// CONNECT TO DATABASE
include 'includes/library.php';
$pdo = connectdb();
// RETRIEVE USER CREDITIONALS
$query = "SELECT * FROM `signup_users` WHERE userid=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userid]);
$results = $stmt->fetch();
// RETREIVE PROFILE PICTURE
$src = $results['profilePic'];
// POST SUBMIT
if (isset($_POST['edit'])) {
  header("Location: editprofile.php");
  exit();
} else if (isset($_POST['delete'])) {
  
  header("Location: home.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "My Profile"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body>
    <section class="myProfile">
      <form method="POST">
        <h2>My Profile</h2>
        <div>
          <p>Profile Picture</p>
          <img src="<?=$src?>" alt="Profile Pic" />
        </div>
        <div>
          <p>Username</p>
          <p><?php echo $results['username']; ?></p>
        </div>
        <div>
          <p>Email</p>
          <p><?php echo $results['email']; ?></p>
        </div>
        <div>
          <p>Password</p>
          <p><?php echo "********";?></p>
        </div>
        <div>
          <button type="submit" name="edit">Edit Profile</button>
          <button type="submit" name="delete">Delete Account</button>
        </div>
      </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>