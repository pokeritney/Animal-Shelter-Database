<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Application.php';

session_start();

$db = new Database();
$conn = $db->getConn();

if ( ! isLoggedIn()) {

    die("unauthorised");

}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

  var_dump($_POST);

}

if (isset($_GET['id'])) {
    $application = Application::getByID($conn, $_GET['id']);


} else {
    $application = null;
}


?>

<?php require 'lib/header.php';?>

<?php if (isLoggedIn()): ?>

    <p>You are logged in. <a href="logout.php">Log out</a></p>

<?php else: ?>

    <p>You are not logged in. <a href="login.php">Log in</a></p>

<?php endif; ?>

<a href="animal.php">Animal Dashboard</a>
<?php if (isEmployee()): ?>
    <a href="add-animal.php">Add Animal</a>
<?php endif; ?>
<?php if (isAdminUser()): ?>
    <a href="view-reports.php">View Reports</a>
<?php endif; ?>


<?php if (isAdminUser()): ?>
  <p>AdminUser</p>
<?php endif; ?>

<?php if (isEmployee()): ?>
  <p>Employee</p>

<?php endif; ?>

<?php if (isVolunteer()): ?>
  <p>Volunteer</p>
<?php endif; ?>

<h2>Application has been Added!</h2>

<?php if ($application) : ?>
  <p>Application ID:  <?= htmlspecialchars($application['applicationID']); ?></p>
  <p>Application Date:  <?= htmlspecialchars($application['application_date']); ?></p>
  <p>First Name:  <?= htmlspecialchars($application['primary_first_name']); ?></p>
  <p>Last Name:  <?= htmlspecialchars($application['primary_last_name']); ?></p>
  <p>Email:  <?= htmlspecialchars($application['email']); ?></p>
  <p>Telephone Number:  <?= htmlspecialchars($application['phone_number']); ?></p>
  <p>Street Address:  <?= htmlspecialchars($application['street']); ?></p>
  <p>City:  <?= htmlspecialchars($application['city']); ?></p>
  <p>State:  <?= htmlspecialchars($application['state']); ?></p>
  <p>Zip Code:  <?= htmlspecialchars($application['zip_code']); ?></p>
  <p>Co Applicant First Name:  <?= htmlspecialchars($application['co_first_name']); ?></p>
  <p>Co Applicant Last Name:  <?= htmlspecialchars($application['co_last_name']); ?></p>
  <p>Status:  <?= htmlspecialchars($application['status']); ?></p>
<?php endif; ?>





<?php require 'lib/footer.php'; ?>
