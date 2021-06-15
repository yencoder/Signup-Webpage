<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/6786f5cbb4.js" crossorigin="anonymous"></script>
    <title>Search Page</title>
    <link rel="stylesheet" href="styles/search.css" />
  </head>
  <body>
    <?php include 'includes/header.php';?>
    <h2>Search Your Sign Up Sheet</h2>
    <section>
    <i class="fa fa-search"></i>
    <input id="search" name="search" type="text" placeholder="Enter Search Title, Username, Time Slot..." />
    <button id="go">Search</button>
</section>
    <?php include "includes/footer.php" ?>
</body>