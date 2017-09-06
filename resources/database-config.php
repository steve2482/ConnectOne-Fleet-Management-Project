<?php
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

  $server = $url["host"];
  $username = $url["user"];
  $password = $url["pass"];
  $db = substr($url["path"], 1);

  $conn = mysqli_connect($server, $username, $password, $db);

  if(mysqli_connect_errno()) {
    echo 'Failed to connect DB' . mysqli_connect_errno();
  }
?>
