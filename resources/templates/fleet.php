<?php
  
  require_once(realpath(dirname(__FILE__) . "/../database-config.php"));

  // Query database for all truck ID's
  $query = 'SELECT * FROM fleet';

  $result = mysqli_query($conn, $query);

  $trucks = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $half = round(count($trucks) / 2);

  mysqli_free_result($result);

  mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Fleet</title>
    <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css">
  </head>

  <body>
    
    <div class="container">
      <h1 style="text-align: center;">Fleet List</h1>
        <div class="col-md-6">
          <div class="list-group">
            <?php 
              for ($i = 0; $i < $half; $i++) {
                echo '<a href="#" class="list-group-item"><strong>Truck ' . $trucks[$i]['Truck Id'] . '</stong></a>';
              }
            ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="list-group">
            <?php 
              for ($i = $half; $i < count($trucks); $i++) {
                echo '<a href="#" class="list-group-item"><strong>Truck ' . $trucks[$i]['Truck Id'] . '</strong></a>';
              }
            ?>
          </div>
        </div>
    </div>

  </body>
</html>
