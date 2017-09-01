<?php
  $conn = mysqli_connect('localhost', 'root', 'Nascar2482##', 'connectone');

  if(mysqli_connect_errno()) {
    echo 'Failed to connect DB' . mysqli_connect_errno();
  }
?>