<?php
require "includes/header.php";
$userid = $_SESSION['userid'];

// CONNECT TO DATABASE
include 'includes/library.php';
$pdo = connectdb();
//get signup sheet info
$query = "SELECT * FROM `signin_info` WHERE userid=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userid]);
$results = $stmt->fetch();
//get timeslot info
$query = "SELECT * FROM `slot_info` WHERE sheetid=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$sheetid]);
$result = $stmt->fetch();
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $page_title = "Delete Sheet"; ?>
    <?php include "includes/metadata.php" ?>
  </head>
  <body>
  <body>
    <section class="delete">
        <form method="POST">
            <h2>Delete Your Sheet</h2>
            <div>
                <p class="label">Sheet Title</p>
                <p><?php echo $results['title']; ?></p>
            </div>
            <div>
                <p class="label">Description</p>
                <p><?php echo $results['description']; ?></p>
            </div>
            <div>
                <p class="label">Time Slots</p>
                <p><?php echo $result['timeslot']; ?></p>
            </div>
            <button id="delete" name='delete'>Delete the Sheet </button>
        </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>
