<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Vaccination.php';

session_start();

$db = new Database();
$conn = $db->getConn();

if (!isLoggedIn()) {

  die("unauthorised");
}

$vaccines = Vaccination::vaccineReminder($conn);

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

<h3>Vaccine Reminder Report</h3>

<table class="table">
  <tr>
    <th>Vaccination Type</th>
    <th>Due Date</th>
    <th>PetID</th>
    <th>Species</th>
    <th>Breed</th>
    <th>Sex</th>
    <th>Alteration Status</th>
    <th>Microchip ID</th>
    <th>Surrender Date</th>
    <th>First Name</th>
    <th>Last Name</th>
  </tr>

  <?php foreach ($vaccines as $vaccine) : ?>
    <?php


    if ($vaccine['alteration_status'] == TRUE) {
      $alterationStatus = "True";
    } else $alterationStatus = "False";

    ?>
    <tr>
      <td><?= $vaccine['vaccine_name']; ?></td>
      <td><?= $vaccine['due_date']; ?></td>
      <td><?= $vaccine['id']; ?></td>
      <td><?= $vaccine['species_name']; ?></td>
      <td><?= $vaccine['breed_name']; ?></td>
      <td><?= $vaccine['sex']; ?></td>
      <td><?= $alterationStatus; ?></td>
      <td><?= $vaccine['microchip_id']; ?></td>
      <td><?= $vaccine['surrender_date']; ?></td>
      <td><?= $vaccine['first_name']; ?></td>
      <td><?= $vaccine['last_name']; ?></td>
    </tr>

  <?php endforeach; ?>
</table>
