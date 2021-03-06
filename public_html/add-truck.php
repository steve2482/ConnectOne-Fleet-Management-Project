<?php



  require_once(realpath(dirname(__FILE__) . "/../resources/database-config.php"));

  class Form {
    private $method;
    private $action;
    public $successMessage;
    public $errors;

    // Constructor function
    public function __construct($method, $action) {
    $this->method = $method;
    $this->action = htmlspecialchars($action);
    $this->successMessage = [];
    $this->errors = [];
    }

    // Method to display add form
    public function displayForm() {
      return '<div>
                <form id="add-form" enctype="multipart/form-data" method="' . $this->method . '" action="' . $this->action . '">
                  <div class="form-group">
                    <label>Truck Id</label>
                    <input type="number" name="truck-id" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Driver</label>
                    <input type="text" name="driver" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Warehouse</label>
                    <select name="warehouse" class="form-control rounded">
                      <option value="" disabled selected>Select Warehouse</option>
                      <option value="Oxford, CT">Oxford, CT</option>
                      <option value="Warehouse 2">Warehouse 2</option>
                    </select>
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
                  <button type="submit" name="submit" class="btn btn-connect rounded"><strong>Submit</strong></button>
                </form>
              </div>';
    }

    // Method to handle form submission
    public function handleSubmission() {
      global $conn;
      // if post request received
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // check that the form was not submitted with empty truck id
        if (empty($_POST['truck-id'])) {
          array_push($this->errors, 'Truck Id field is required!');
          $this->successMessage = [];
        }
        else {
          // validate inputs are safe and grab inputs
          $truckId = $this->validateInputs($_POST['truck-id']);
          $driver = $this->validateInputs($_POST['driver']);
          $warehouse = $this->validateInputs($_POST['warehouse']);
          $gps = $this->validateInputs($_POST['gps']);
          $easyPass = $this->validateInputs($_POST['easyPass']);
          $oilChange = $_POST['oilChange'];
          $inspection = $_POST['inspection'];

          // set path of ins/ref files and name 
          $targetPath = '../resources/uploads/';
          $insurancePath = $targetPath . 'truck' . $truckId . '-insurance';
          $registrationPath = $targetPath . 'truck' . $truckId . '-registration';

          // If both files exist and are of correct type, continue upload
          if ($_FILES['insurance'] && $_FILES['insurance']['type'] == 'application/pdf' && $_FILES['registration'] && $_FILES['registration']['type'] == 'application/pdf') {

            // save new files
            move_uploaded_file($_FILES['insurance']['tmp_name'], $insurancePath);
            move_uploaded_file($_FILES['registration']['tmp_name'], $registrationPath);

            // save info to DB
            $query = "INSERT INTO fleet (Id, Driver, Warehouse, Insurance, Registration, GPS, EasyPass, OilChange, Inspection) VALUES ('$truckId', '$driver', '$warehouse', '$insurancePath', '$registrationPath', '$gps', '$easyPass', '$oilChange', '$inspection')";
            if (mysqli_query($conn, $query)) {
              array_push($this->successMessage, 'Truck ' . $truckId . 'added successfully!');
              $this->errors = [];
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }           
          } 

          // If only insurance file exists
          elseif ($_FILES['insurance'] && $_FILES['insurance']['type'] == 'application/pdf') {

            // save new insurance file
            move_uploaded_file($_FILES['insurance']['tmp_name'], $insurancePath);

            // save info to DB
            $query = "INSERT INTO fleet (Id, Driver, Warehouse, Insurance, GPS, EasyPass, OilChange, Inspection) VALUES ('$truckId', '$driver', '$warehouse', '$insurancePath', '$gps', '$easyPass', '$oilChange', '$inspection')";
            if (mysqli_query($conn, $query)) {
              array_push($this->successMessage, 'Truck ' . $truckId . 'added successfully!');
              $this->errors = [];
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }
          }

          // If only registration file exists
          elseif ($_FILES['registration'] && $_FILES['registration']['type'] == 'application/pdf') {

            // save new registration file
            move_uploaded_file($_FILES['registration']['tmp_name'], $registrationPath);

            // save info to DB
            $query = "INSERT INTO fleet (Id, Driver, Warehouse, Registration, GPS, EasyPass, OilChange, Inspection) VALUES ('$truckId', '$driver', '$warehouse', '$registrationPath', '$gps', '$easyPass', '$oilChange', '$inspection')";
            if (mysqli_query($conn, $query)) {
              array_push($this->successMessage, 'Truck ' . $truckId . 'added successfully!');
              $this->errors = [];
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }
          }

          // If no new files
          elseif (!$_FILES['insurance']['name'] && !$_FILES['registration']['name']) {

            // save info to DB
            $query = "INSERT INTO fleet (Id, Driver, Warehouse, GPS, EasyPass, OilChange, Inspection) VALUES ('$truckId', '$driver', '$warehouse', '$gps', '$easyPass', '$oilChange', '$inspection')";
            if (mysqli_query($conn, $query)) {
              array_push($this->successMessage, 'Truck ' . $truckId . 'added successfully!');
              $this->errors = [];
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }
          }

          // Else something isn't right
          else {
            array_push($this->errors, 'Something went wrong while trying to add truck, please try again.');
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

<?php include('../resources/templates/header.php'); ?>

<?php include('./navbar.php'); ?>

<div class="container rounded opac-container">
  <div>
    <h1 class='header'>Add Truck</h1>
  </div>  
    
    

  <?php
    $addForm = new Form('post', $_SERVER["PHP_SELF"]);
    echo $addForm->displayForm();
    if (isset($_POST['submit'])) {
      $addForm->handleSubmission();
      foreach ($addForm->errors as $error) {
        $errorHTML = '<p class="alert alert-danger">' . $error . '</p>';
        echo $errorHTML;
      }
      foreach ($addForm->successMessage as $message) {
      $successHTML = '<p class="alert alert-success">' . $message . '</p>';
      echo $successHTML;
      } 
    }
  ?>
</div>

<?php include('../resources/templates/footer.php'); ?>
