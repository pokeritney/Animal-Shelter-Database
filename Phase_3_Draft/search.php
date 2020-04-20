<?php

require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Application.php';
require 'lib/url.php';

session_start();

$db = new Database();
$conn = $db->getConn();



if ( ! isLoggedIn()) {

    die("unauthorised");

  }

  if (isset($_GET['id'])) {

      $animalID = $_GET['id'];
  }



  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();
    $conn = $db->getConn();


    $primary_last_name = $_POST['primary_last_name'];
    $co_last_name = $_POST['co_last_name'];

    $applications = Application::search($conn, $primary_last_name, $co_last_name);


}

?>






<?php require 'lib/header.php';?>
<ul class="nav">
<?php if (isLoggedIn()): ?>
    <li class="nav-item">
    <a class="nav-link" href="logout.php">Log out</a>
  </li>
<?php else: ?>
    <li class="nav-item">
    <a class="nav-link" href="login.php">Log in</a>
  </li>
<?php endif; ?>
<li class="nav-item">
<a class="nav-link" href="animal.php">Animal Dashboard</a>
</li>

<?php if (isAdminUser()): ?>
   <li class="nav-item">
    <a class="nav-link" href="edit-application.php">Review Adoption Applications</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" href="view-reports.php">View Reports</a>
  </li>
<?php endif; ?>
</ul>

<h2>Search for Adopter</h2>



<h5>Enter partial or full Last Name:</h5>
<h5>Leave blank to retrieve all Approved Applications</h5>
<form method=POST>
<div>
<label for="primary_last_name">Applicant Last Name: </label>
<input type="text" id="primary_last_name" name="primary_last_name" value="">
<nbsp>and/or
<label for="co_last_name"> Co-Applicant Last Name: </label>
<input type="text" id="co_last_name" name="co_last_name" value="">
<button>search</button>
</div>
</form>


<?php if(empty($applications)) : ?>
<p></p>
<p></p>
<p>There are no records meeting search criteria.</p>
<?php else : ?>

<?php require 'lib/adopter-form.php'; ?>

<?php endif; ?>



<?php require 'lib/footer.php'; ?>
