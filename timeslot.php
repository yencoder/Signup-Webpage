<?php 
require 'includes/header.php';
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$sheetid = $_SESSION['sheetid'];

include 'includes/library.php';
$pdo = connectdb();

$errors = array(); 
$date = $_POST['date'] ?? null;
$title = trim(filter_var($_POST['title'] ?? null, FILTER_SANITIZE_STRING));

if(isset($_POST['add'])){
  if (!isset($date)) { $errors['date'] = true; }
  if (!isset($title)) { $errors['emptyTitle'] = true; }
  if(count($errors)===0) {
    // RETRIEVE USER EMAIL
    $query = "SELECT `email` FROM `signup_users` WHERE userid=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userid]);
    $results = $stmt->fetch();
  
    $query = "INSERT into slot_info values (NULL, $sheetid, $userid,?,?,?,?)"; //foreign key
    //prepare & execute query
    $stmt = $pdo->prepare($query)->execute([$title, $date, $username, $results['email']]);
  }
}
else if (isset($_POST['save'])) {
  header("Location: mystuffview.php");  
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/6786f5cbb4.js" crossorigin="anonymous"></script>
    <title>Sign Up Sheet</title>
    <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body>
    <section class="timeslot">
        <form id="signupform" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
          <h2>Create Your Sign Up Sheet</h2>
          <h3>*****Step Two*****</h3>
          <div class="input">
            <label for="title">Title</label>
            <input type="text" id = "title" name="title" placeholder="Slot Title">
            <span class="error <?=!isset($errors['emptyTitle']) ? 'hidden' : "";?>">Please enter a Title</span>
          </div>
          <div class="input">
            <label for="date">Time Slot</label>
            <input id="date" name="date" type="datetime-local" placeholder="YYYY-MM-DD"/>
            <span class="error <?=!isset($errors['date']) ? 'hidden' : "";?>">It's Empty!</span>
            <button id="add" name="add">ADD</button>
          </div> 
          <button id="save" name='save'>Save </button>
        </form>
    </section>
    <?php include "includes/footer.php" ?>
  </body>
</html>