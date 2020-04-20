<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Control.php';

session_start();

$db = new Database();
$conn = $db->getConn();

if (!isLoggedIn()) {

  die("unauthorised");
}

// current year and month
// $year = date("Y");
// $month = date("m");

// echo $year;
// echo $month;

// NEED TO CHANGE THE SHORTCUT
$control4 = Control::controlNumber($conn, 4, 2020);
$control3 = Control::controlNumber($conn, 3, 2020);
$control2 = Control::controlNumber($conn, 2, 2020);
$control1 = Control::controlNumber($conn, 1, 2020);
$control12 = Control::controlNumber($conn, 12, 2019);
$control11 = Control::controlNumber($conn, 11, 2019);
$control10 = Control::controlNumber($conn, 10, 2019);

$rescue4 = Control::rescueNumber($conn, 4, 2020);
$rescue3 = Control::rescueNumber($conn, 3, 2020);
$rescue2 = Control::rescueNumber($conn, 2, 2020);
$rescue1 = Control::rescueNumber($conn, 1, 2020);
$rescue12 = Control::rescueNumber($conn, 12, 2019);
$rescue11 = Control::rescueNumber($conn, 11, 2019);
$rescue10 = Control::rescueNumber($conn, 10, 2019);

?>


<?php require 'lib/header.php'; ?>
<ul class="nav">
<?php if (isLoggedIn()) : ?>
 <li class="nav-item">
  <a class="nav-link" href="logout.php">Log out</a>
</li>
<?php else : ?>
 <li class="nav-item">
  <a class="nav-link" href="login.php">Log in</a>
</li>
<?php endif; ?>
<li class="nav-item">
<a class="nav-link" href="animal.php">Animal Dashboard</a>
</li>
<?php if (isAdminUser()) : ?>
  <li class="nav-item">
  <a class="nav-link" href="view-reports.php">View Reports</a>
</li>
<?php endif; ?>
</ul>


<h2>Animal Control Report</h2>
<p>Year - Month</p>
<p>2020 - 4</p>
<table cellpadding="15">
    <tr>
      <th>Months</th>
      <th>Animal Control Surrendered</th>
      <th>Animal Adopted After 60 Days</th>
    </tr>
    <tr>
      <td>April</td>
      <th><a href="control-surrender.php?month=4&year=2020"><?= $control4; ?></a>
      </th>
      <th><a href="control-rescue.php?month=4&year=2020"><?= $rescue4; ?></a>
      </th>
    </tr>
    <tr>
      <td>March</td>
      <th><a href="control-surrender.php?month=3&year=2020"><?= $control3; ?></a>
      </th>
      <th><a href="control-rescue.php?month=3&year=2020"><?= $rescue3; ?></a>
      </th>
    </tr>
    <tr>
      <td>February</td>
      <th><a href="control-surrender.php?month=2&year=2020"><?= $control2; ?></a>
      </th>
      <th><a href="control-rescue.php?month=2&year=2020"><?= $rescue2; ?></a>
      </th>
    </tr>
    <tr>
      <td>January</td>
      <th><a href="control-surrender.php?month=1&year=2020"><?= $control1; ?></a>
      </th>
      <th><a href="control-rescue.php?month=1&year=2020"><?= $rescue1; ?></a>
      </th>
    </tr>
    <tr>
      <td>December</td>
      <th><a href="control-surrender.php?month=12&year=2019"><?= $control12; ?></a>
      </th>
      <th><a href="control-rescue.php?month=12&year=2019"><?= $rescue12; ?></a>
      </th>
    </tr>
    <tr>
      <td>November</td>
      <th><a href="control-surrender.php?month=11&year=2019"><?= $control11; ?></a>
      </th>
      <th><a href="control-rescue.php?month=11&year=2019"><?= $rescue11; ?></a>
      </th>
    </tr>
    <tr>
      <td>October</td>
      <th><a href="control-surrender.php?month=10&year=2019"><?= $control10; ?></a>
      </th>
      <th><a href="control-rescue.php?month=10&year=2019"><?= $rescue10; ?></a>
      </th>
    </tr>
</table>
