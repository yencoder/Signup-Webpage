<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/6786f5cbb4.js" crossorigin="anonymous"></script>
    <title>Sign Up Sheet</title>
    <link rel="stylesheet" href="styles/signup.css" />
  </head>
  <body>
    <?php include 'includes/header.php';?>
          <form>
          <h2>
              Create Your Sign Up Sheet
          </h2>
            <div class="input">
                <label for="title">Sheet Title</label>
                <input id="title" name="title" type="text" placeholder="COIS 3420 Project" />
              </div>
              <div class="input">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="50" rows="5"></textarea>
              </div>
              <div class="input">
                <label for="date">Time Slot</label>
                <input id="date" name="date" type="datetime-local" />
              </div>
              <fieldset>
                <legend>Privacy</legend>    
                <div>
                  <input id="public" name="privacy" type="radio" value="Y" />
                  <label for="public">Public</label>
                </div>
                <div>
                  <input id="private" name="privacy" type="radio" value="N" />
                  <label for="private">Private</label>
                </div>
              </fieldset>
              <button id="save">Save</button>
          </form>
    <?php include "includes/footer.php" ?>
  </body>
</html>
