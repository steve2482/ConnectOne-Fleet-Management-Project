<?php
  
  require_once(realpath(dirname(__FILE__) . "/../resources/database-config.php"));

  // Get ID
  $id = mysqli_real_escape_string($conn, $_GET['id']);
  // Query database for specific truck
  $query = 'SELECT * FROM fleet WHERE Id = '.$id;

  $result = mysqli_query($conn, $query);

  $truck = mysqli_fetch_assoc($result);

  mysqli_free_result($result);

  mysqli_close($conn);
?>


    <?php include('../resources/templates/header.php'); ?>
    <?php include('./navbar.php'); ?>
    
    <div class="container">
      <h1 style="text-align: center;"></h1>
      <div class="panel panel-connect rounded">
        <div class="panel-heading">
          <h1 class="panel-title"><strong>Truck <?php echo $truck['Id'] ?></strong><a href=<?php echo './edit-truck.php?id=' . $truck['Id'] ?> class="btn btn-primary pull-right">Edit</a></h1>
        </div>
        <ul class="list-group">
          <li class="list-group-item info">Driver: <?php echo $truck['Driver'] ?></li>
          <li class="list-group-item info">Warehouse: <?php echo $truck['Warehouse'] ?></li>
          <?php 

          if ($truck['Insurance']) {
            echo '<li class="list-group-item info">Insurance Card: ' . '<a href=' . $truck['Insurance'] . '>PDF File</a></li>';
          } else {
            echo '<li class="list-group-item info">Insurance Card: <span class="warning">No Insurance Card On File</span></li>';
          }

          if ($truck['Registration']) {
            echo '<li class="list-group-item info">Registration: ' . '<a href =' . $truck['Registration'] . '>PDF File</a></li>';
          } else {
            echo '<li class="list-group-item info">Registration Card: <span class="warning">No Registration Card On File</span></li>';
          }

          ?>    
    
          <li class="list-group-item info">GPS Serial Number: <?php echo $truck['GPS'] ?></li>
          <li class="list-group-item info">Easy Pass Serial Number: <?php echo $truck['EasyPass'] ?></li>
          <li class="list-group-item info">Last Oil Change: <?php echo $truck['OilChange'] ?></li>
          <li class="list-group-item info">Last Inspection: <?php echo $truck['Inspection'] ?></li>
        </ul>
      </div>        
    </div>
    
    <?php include('../resources/templates/footer.php'); ?>
  