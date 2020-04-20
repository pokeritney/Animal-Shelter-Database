<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'lib/url.php';

session_start();

$db = new Database();
$conn = $db->getConn();



if ( ! isLoggedIn()) {

    die("unauthorised");

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
</ul>



<h2>View Reports</h2>

<a href="animal-control.php">Animal Control Report</a><br>

<a href="volunteer-month.php">Volunteer of the Month</a><br>

<a href="monthly-adoption.php">Monthly Adoption Report</a><br>

<a href="volunteer-lookup.php">Volunteer Lookup</a><br>

<a href="vaccine-reminder.php">Vaccine Reminder Report</a>

<?php require 'lib/footer.php'; ?>
