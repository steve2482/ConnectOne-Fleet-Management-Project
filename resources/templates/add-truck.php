<?php

  require_once(realpath(dirname(__FILE__) . "/../database-config.php"));

  class Form {
    private $method;
    private $action;
    public $errors;

    // Constructor function
    public function __construct($method, $action) {
    $this->method = $method;
    $this->action = htmlspecialchars($action);
    $this->errors = [];
    }

    // Method to display add form
    public function displayForm() {
      return '<div class="container">
                <form id="add-form" method="' . $this->method . '" action="' . $this->action . '">
                  <div class="form-group">
                    <label>Truck Id</label>
                    <input type="number" name="truck id" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Driver</label>
                    <input type="text" name="driver" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Warehouse</label>
                    <input type="text" name="warehouse" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Insurance Card</label>
                    <input type="file" name="insurance">
                  </div>
                  <div class="form-group">
                    <label>Vehicle Registration</label>
                    <input type="file" name="registration">
                  </div>
                  <div class="form-group">
                    <label>GPS Tracking #</label>
                    <input type="text" name="gps" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Easy Pass Id</label>
                    <input type="text" name="easyPass" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Last Oil Change</label>
                    <input type="date" name="oilChange" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Last Inspection</label>
                    <input type="date" name="inspection" class="form-control rounded">
                  </div>
                  <br>
                  <button type="submit" name="submit" class="btn btn-connect rounded">Submit</button>
                </form>
              </div>';
    }

    // Method to handle form submission
    public function handleSubmission() {
      global $conn;
      // if post request received
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // check that the form was not submitted with empty truck id
        if (empty($_POST['truck id'])) {
          array_push($this->errors, 'Truck Id field is required!');
        }
        // else validate inputs are safe
        else {
          $truckId = $this->validateInputs($_POST['truck id']);
          $driver = $this->validateInputs($_POST['driver']);
          $warehouse = $this->validateInputs($_POST['warehouse']);
          $gps = $this->validateInputs($_POST['gps']);
          $easyPass = $this->validateInputs($_POST['easyPass']);
          $oilChange = $_POST['oilChange'];
          $inspection = $_POST['inspection'];
          $query = "INSERT INTO fleet (Truck Id, Driver, Warehouse, GPS Tracking Number, Easy Pass Number, Last Oil Change, Last Inspection) VALUES ('$truckId', '$driver', '$warehouse', '$gps', '$easyPass', '$oilChange', '$inspection')";
          if (mysqli_query($conn, $query)) {
            header('Location: ../../public_html/index.php');
          } else {
            echo 'ERROR: ' . mysqli_error($conn);
          }
        }
      }
    }

    // Method to validate inputs
    private function validateInputs($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Truck</title>
  <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css">
  <link rel="stylesheet" href="../stylesheets/navbar.css">
</head>
<body>

  <?php include('./navbar.php'); ?>
  
  <div class="container">
    <h1>Add Truck Here</h1>
    
    <?php
      $addForm = new Form('post', $_SERVER["PHP_SELF"]);
    ?>
    <div class="container">
      </div>
      <?php 
        echo $addForm->displayForm();
        if (isset($_POST['submit'])) {
          $addForm->handleSubmission();
          foreach ($addForm->errors as $error) {
            $errorHTML = '<p class="alert alert-danger">' . $error . '</p>';
            echo $errorHTML;
          }
        }
      ?>
    </div>    
  </div>
  
</body>
</html>