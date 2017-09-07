<?php
  require_once(realpath(dirname(__FILE__) . "/../resources/database-config.php"));

  // Query database for all truck ID's
  $query = 'SELECT * FROM fleet';

  $result = mysqli_query($conn, $query);

  $trucks = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $half = round(count($trucks) / 2);

  mysqli_free_result($result);

  // mysqli_close($conn);

  // Check if delete button was clicked
  if (isset($_POST['delete'])) {
    $deleteId = mysqli_real_escape_string($conn, $_POST['deleteId']);
    echo $deleteId;
    $query = 'DELETE FROM fleet WHERE Id = ' . $deleteId;

    if (mysqli_query($conn, $query)) {
      header('Location: ./index.php');
    } else {
      echo 'ERROR: ' . mysqli_error($conn);
    }
  }
?>

<div class="container">
  <h1 class='header'>Fleet List</h1>
    <div class="col-md-6">
      <div class="list-group">
        <?php 
          for ($i = 0; $i < $half; $i++) {
            echo '<a href="./truck.php?id=' . $trucks[$i]['Id'] . '" class="list-group-item list-item"><strong>Truck ' . $trucks[$i]['Id'] . '</strong>            
                <form class="pull-right" method="POST" action="' . $_SERVER["PHP_SELF"] . '">
                  <input type="hidden" name="deleteId" value="' . $trucks[$i]['Id'] . '">
                  <button type="submit" name="delete" class="btn btn-danger btn-xs glyphicon glyphicon-remove"></button>
                </form>
                <form class="pull-right" id="edit-btn" method="POST" action="' . $_SERVER["PHP_SELF"] . '">
                  <button type="submit" name="edit" class="btn btn-primary btn-xs glyphicon glyphicon-pencil"></button>
                </form>
              </a>';
          }
        ?>
      </div>
    </div>
    <div class="col-md-6">
      <div class="list-group">
        <?php 
          for ($i = $half; $i < count($trucks); $i++) {
            echo '<a href="./truck.php?id=' . $trucks[$i]['Id'] . '" class="list-group-item list-item"><strong>Truck ' . $trucks[$i]['Id'] . '</strong>            
                <form class="pull-right" method="POST" action="' . $_SERVER["PHP_SELF"] . '">
                  <input type="hidden" name="deleteId" value="' . $trucks[$i]['Id'] . '">
                  <button type="submit" name="delete" class="btn btn-danger btn-xs glyphicon glyphicon-remove"></button>
                </form>
                <form class="pull-right" id="edit-btn" method="POST" action="' . $_SERVER["PHP_SELF"] . '">
                  <button type="submit" name="edit" class="btn btn-primary btn-xs glyphicon glyphicon-pencil"></button>
                </form>
              </a>';
          }
        ?>
      </div>
    </div>
</div>



