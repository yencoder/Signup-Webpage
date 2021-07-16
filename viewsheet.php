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

$query = "SELECT * FROM signin_info where creatorid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$userid]);
$t1row = $stmt->fetch();

$sheetid = $t1row['sheetid'];
$query = "SELECT * FROM slot_info where sheetid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$sheetid]);
$t2row = $stmt->fetch();

$query = "SELECT title, timeslot, user FROM slot_info WHERE sheetid = ?";
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
  <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Sign Up Sheet</title>
    <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body>
    <?php include 'includes/header.php'?>
    
    <!-- Title of the sheet should be selected from table in the database and displayed first -->
    <h1><?php echo $t1row['title'] ?></h1>
    
    <!-- The description from the table should be put next -->
    <p><?php echo $t1row['description'] ?></p>

    <!-- Each available or taken slot should be displayed with sections Title, Time then Name -->
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
      <td><?php echo "$r[user]"; ?></td>
    </tr>
     <?php endforeach ?>  
   </tbody>
   </table>

    <!-- Each time a person signs up. the tables are updated. numofpeoplesignedup in the first table.
    and add the name and email to the second table. -->

    <?php include "includes/footer.php" ?>
  </body>
</html>