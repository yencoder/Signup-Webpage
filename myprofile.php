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
          <p>Username</p>
          <p><?php echo $results['username']; ?></p>
        </div>
        <div>
          <p>Email</p>
          <p><?php echo $results['email']; ?></p>
        </div>
      </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>