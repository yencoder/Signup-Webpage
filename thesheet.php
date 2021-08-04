<?php

session_start(); //start session
  //check session for whatever user info was stored

include 'includes/library.php';
$pdo = connectdb();

$sheetid = $_GET['sheetid'];
$query = "SELECT * FROM signin_info where sheetid = ?";
$stmt=$pdo->prepare($query);
$results = $stmt->execute([$sheetid]);
$t1row = $stmt->fetch();

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
  <?php $page_title = "Sign up sheet"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body>
    <?php include 'includes/header.php'?>
    <section class = "vsheet">
    <h2><?php echo $t1row['title'] ?></h2>
    
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
      <?php echo "$r[user]"; 
      $name="$r[user]";
      if($name==null): ?>
      <a href="register.php?slotid=<?php echo $r['slotid']; ?>">Register</a>      
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