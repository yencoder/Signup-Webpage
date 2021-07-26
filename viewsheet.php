<?php

session_start(); //start session
  //check session for whatever user info was stored
if(!isset($_SESSION['username'])){
    //no user info, redirect
  header("Location:login.php");
  exit();
}
$userid = $_SESSION['userid'];
include 'includes/library.php';
$pdo = connectdb();

$sheetid = $_GET['sheetid'];
$query = "SELECT * FROM signin_info where sheetid = ? and creatorid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute(array($sheetid,$userid));
$t1row = $stmt->fetch();

$query = "SELECT * FROM slot_info where sheetid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$sheetid]);
$t2row = $stmt->fetch();

$query = "SELECT * FROM slot_info WHERE sheetid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$sheetid]);
if (!$stmt) {
  die("Something went horribly wrong");
}
$table = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $page_title = "View Sign up sheet"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body>
    <?php include 'includes/header.php'?>
    <section class = "vsheet">
    <h1><?php echo $t1row['title'] ?></h1>
    
    <p><?php echo $t1row['description'] ?></p>

    <table>
   <thead>
       <tr>
           <th>Title</th>
           <th>Timeslot</th>
           <th>Name</th>
       </tr>
   </thead>
   <tbody>
   <?php  foreach($table as $r): ?>
    <tr>
      <td><?php echo "$r[title]"; ?></td>
      <td><?php echo "$r[timeslot]"; ?></td>
      <td>
      <?php echo "$r[user]"; // FIX THIS
      $name="$r[user]";
      if($name==null): ?>
      ---     
      <?php endif ?>
      </td>
    </tr>
     <?php endforeach ?>  
   </tbody>
   </table>
   </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>
