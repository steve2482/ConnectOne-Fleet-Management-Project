
<nav class="navbar">
  <div class="container">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <img class="nav-img" alt="Connect/One" src="../resources/images/connectone.PNG" width="390" height="50">
      </div>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="./index.php"><strong>Home</strong><span class="sr-only">(current)</span></a></li>
          <li><a href="./add-truck.php"><strong>Add</strong></a></li>
          <li><a href="#"><strong>Inspection</strong></a></li>
        </ul>
        <form class="navbar-form navbar-right" role="search" method="POST" action="./truck.php">
          <div class="form-group">
            <input type="text" class="form-control rounded" id='search' placeholder="Search By Truck ID" name="truckId">
          </div>
          <button type="submit" class="btn btn-connect rounded"><strong>Submit</strong></button>
        </form>
      </div>
    </div>
  </div>
</nav>