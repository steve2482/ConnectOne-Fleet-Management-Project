<?php
  require_once(realpath(dirname(__FILE__) . "/../resources/database-config.php"));

  $truck = [];
  if (!isset($_POST['submit'])) {
    // Get ID
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query database for specific truck
    $query = 'SELECT * FROM fleet WHERE Id = '.$id;

    $result = mysqli_query($conn, $query);

    $truck = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
  }
  

  class EditForm {
    private $method;
    private $action;
    public $truck;
    public $successMessage;
    public $errors;

    // Constructor function
    public function __construct($method, $action, $truck) {
    $this->method = $method;
    $this->action = htmlspecialchars($action);
    $this->truck = $truck;
    $this->successMessage = [];
    $this->errors = [];
    }

    // Method to display add form
    public function displayForm() {
      return '<div>
                <form id="add-form" enctype="multipart/form-data" method="' . $this->method . '" action="' . $this->action . '">
                  <div class="form-group">
                    <label>Truck Id</label>
                    <input type="number" name="truck-id" value="' . $this->truck['Id'] . '" class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Driver</label>
                    <input type="text" name="driver" value="' . $this->truck['Driver'] . '"class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Warehouse</label>
                    <input type="text" name="warehouse" value="' . $this->truck['Warehouse'] . '"class="form-control rounded">
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
                    <input type="text" name="gps" value="' . $this->truck['GPS'] . '"class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Easy Pass Id</label>
                    <input type="text" name="easyPass" value="' . $this->truck['EasyPass'] . '"class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Last Oil Change</label>
                    <input type="date" name="oilChange" value="' . $this->truck['OilChange'] . '"class="form-control rounded">
                  </div>
                  <div class="form-group">
                    <label>Last Inspection</label>
                    <input type="date" name="inspection" value="' . $this->truck['Inspection'] . '"class="form-control rounded">
                  </div>
                  <br>
                  <input type="hidden" name="updateId" value="' . $this->truck['Id'] . '">
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
        if (empty($_POST['truck-id'])) {
          array_push($this->errors, 'Truck Id field is required!');
          $this->successMessage = [];
        }
        else {

          // validate inputs are safe and grab inputs
          $updateId = $_POST['updateId'];
          $truckId = $this->validateInputs($_POST['truck-id']);
          $driver = $this->validateInputs($_POST['driver']);
          $warehouse = $this->validateInputs($_POST['warehouse']);
          $gps = $this->validateInputs($_POST['gps']);
          $easyPass = $this->validateInputs($_POST['easyPass']);
          $oilChange = $_POST['oilChange'];
          $inspection = $_POST['inspection'];

          // save files to server
          $targetPath = '../resources/uploads/';
          $insurancePath = $targetPath . 'truck' . $truckId . '-insurance';
          $registrationPath = $targetPath . 'truck' . $truckId . '-registration';

          // If both files exist and are of correct type, continue upload
          if ($_FILES['insurance'] && $_FILES['insurance']['type'] == 'application/pdf' && $_FILES['registration'] && $_FILES['registration']['type'] == 'application/pdf') {

            // if old files exist, delete old files
            unlink($insurancePath);
            unlink($registrationPath);            

            // save new files
            move_uploaded_file($_FILES['insurance']['tmp_name'], $insurancePath);
            move_uploaded_file($_FILES['registration']['tmp_name'], $registrationPath);

            // save info to DB
            $query =  "UPDATE fleet SET 
                      Id = '$truckId',
                      Driver = '$driver',
                      Warehouse = '$warehouse',
                      Insurance = '$insurancePath',
                      Registration = '$registrationPath',
                      GPS = '$gps',
                      EasyPass = '$easyPass',
                      OilChange = '$oilChange',
                      Inspection = '$inspection'
                      WHERE Id = " . $updateId;
            if (mysqli_query($conn, $query)) {
              $this->errors = [];
              echo '<script type="text/javascript">window.location.pathname = decodeURIComponent("./public_html/index.php")</script>';
              
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }            
          } 

          // If only insurance file exists
          elseif ($_FILES['insurance'] && $_FILES['insurance']['type'] == 'application/pdf') {

            // delete old insurance file
            unlink($insurancePath);            

            // save new insurance file
            move_uploaded_file($_FILES['insurance']['tmp_name'], $insurancePath);

            // save info to DB
            $query = "UPDATE fleet SET 
                      Id = '$truckId',
                      Driver = '$driver',
                      Warehouse = '$warehouse',
                      Insurance = '$insurancePath',
                      GPS = '$gps',
                      EasyPass = '$easyPass',
                      OilChange = '$oilChange',
                      Inspection = '$inspection'
                      WHERE Id = " . $updateId;;
            if (mysqli_query($conn, $query)) {
              $this->errors = [];              
              echo '<script type="text/javascript">window.location.pathname = decodeURIComponent("./public_html/index.php")</script>';

            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }
          }

          // If only registration file exists
          elseif ($_FILES['registration'] && $_FILES['registration']['type'] == 'application/pdf') {

            // delete old registration file            
            unlink($registrationPath);

            // save new registration file
            move_uploaded_file($_FILES['registration']['tmp_name'], $registrationPath);

            // save info to DB
            $query = "UPDATE fleet SET 
                      Id = '$truckId',
                      Driver = '$driver',
                      Warehouse = '$warehouse',
                      Registration = '$registrationPath',
                      GPS = '$gps',
                      EasyPass = '$easyPass',
                      OilChange = '$oilChange',
                      Inspection = '$inspection'
                      WHERE Id = " . $updateId;;
            if (mysqli_query($conn, $query)) {
              $this->errors = [];
              echo '<script type="text/javascript">window.location.pathname = decodeURIComponent("./public_html/index.php")</script>';
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }
          }

          // If no new files
          elseif (!$_FILES['insurance']['name'] && !$_FILES['registration']['name']) {

            // save info to DB
            $query = "UPDATE fleet SET 
                      Id = '$truckId',
                      Driver = '$driver',
                      Warehouse = '$warehouse',
                      GPS = '$gps',
                      EasyPass = '$easyPass',
                      OilChange = '$oilChange',
                      Inspection = '$inspection'
                      WHERE Id = " . $updateId;;
            if (mysqli_query($conn, $query)) {
              array_push($this->successMessage, 'Truck ' . $truckId . ' updated successfully!');              
              $this->errors = [];
              echo '<script type="text/javascript">window.location.pathname = decodeURIComponent("./public_html/index.php")</script>';
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }
          }

          // Else something isn't right
          else {
            array_push($this->errors, 'Something went wrong while trying to update truck, please try again.');
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

<div class="container opac-container">
  <div>
    <h1 class='header'>Edit Truck</h1>
  </div>

  <?php
    $editForm = new EditForm('post', $_SERVER["PHP_SELF"], $truck);
    echo $editForm->displayForm();
    if (isset($_POST['submit'])) {
      $editForm->handleSubmission();
      foreach ($editForm->errors as $error) {
        $errorHTML = '<div class="container"><p class="alert alert-danger">' . $error . '</p></div>';
        echo $errorHTML;
      }
      foreach ($editForm->successMessage as $message) {
      $successHTML = '<div class="container"><p class="alert alert-success">' . $message . '</p></div>';
      echo $successHTML;
      } 
    }
  ?>
</div>

<?php include('../resources/templates/footer.php'); ?>