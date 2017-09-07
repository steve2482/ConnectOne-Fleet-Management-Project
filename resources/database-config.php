<?php
  // If development server connect to development db, else connect to production db
  if ($_SERVER['SERVER_NAME'] === 'localhost') {
    $conn = mysqli_connect('localhost', 'root', 'Nascar2482##', 'connectone');

    if(mysqli_connect_errno()) {
      echo 'Failed to connect DB' . mysqli_connect_errno();
    }
  } else {
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);

    $conn = mysqli_connect($server, $username, $password, $db);

    if(mysqli_connect_errno()) {
      echo 'Failed to connect DB' . mysqli_connect_errno();
    }
  }
?>

