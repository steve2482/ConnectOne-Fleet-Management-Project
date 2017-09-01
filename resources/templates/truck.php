<?php
  
  require_once(realpath(dirname(__FILE__) . "/../database-config.php"));

  // Get ID
  $id = mysqli_real_escape_string($conn, $_GET['id']);
  // Query database for specific truck
  $query = 'SELECT * FROM fleet WHERE Id = '.$id;

  $result = mysqli_query($conn, $query);

  $truck = mysqli_fetch_assoc($result);

  mysqli_free_result($result);

  mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Fleet</title>
    <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="../stylesheets/navbar.css">
    <link rel="stylesheet" href="../stylesheets/truck.css">
  </head>

  <body>

    <?php include('./navbar.php'); ?>
    
    <div class="container">
      <h1 style="text-align: center;"></h1>
      <div class="panel panel-connect rounded">
        <div class="panel-heading">
          <h1 class="panel-title"><strong>Truck <?php echo $truck['Id'] ?></strong></h1>
        </div>
        <ul class="list-group">
          <li class="list-group-item">Driver: <?php echo $truck['Driver'] ?></li>
          <li class="list-group-item">Warehouse: <?php echo $truck['Warehouse'] ?></li>
          <li class="list-group-item">Insurance Card: <?php echo $truck['Insurance'] ?></li>
          <li class="list-group-item">Registration: <?php echo $truck['Registration'] ?></li>
          <li class="list-group-item">GPS Serial Number: <?php echo $truck['GPS'] ?></li>
          <li class="list-group-item">Easy Pass Serial Number: <?php echo $truck['EasyPass'] ?></li>
          <li class="list-group-item">Last Oil Change: <?php echo $truck['OilChange'] ?></li>
          <li class="list-group-item">Last Inspection: <?php echo $truck['Inspection'] ?></li>
        </ul>
      </div>        
    </div>

  </body>
</html>