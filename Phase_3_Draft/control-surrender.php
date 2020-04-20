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

$current_month = $_GET['month'];
$current_year = $_GET['year'];

if (isset($_GET['month'])) {
  $sur_animals = Control::surrenderAnimals($conn, $_GET['month'], $_GET['year']);
} else {
  $sur_animals = null;
}
// var_dump($sur_animals);

$selected = strval($current_year) . ' - ' . strval($current_month);

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
<li class="nav-item">
<a class="nav-link" href="animal-control.php">Animal Control Report</a>
</li>
<?php endif; ?>
</ul>


<h3>Animals Surrendered By Animal Control In:</h3>

<p>Year - Month</p>
<p><?php echo ($selected . "<br />"); ?></p>


<table width='70%'>
  <tr>
    <th>PetID</th>
    <th>Species</th>
    <th>Breed</th>
    <th>Sex</th>
    <th>Alteration Status</th>
    <th>Microchip ID</th>
    <th>Surrender Date</th>
  </tr>

  <?php foreach ($sur_animals as $animal) : ?>
    <?php
    if ($animal['alteration_status'] == TRUE) {
      $alterationStatus = "True";
    } else $alterationStatus = "False";

    ?>
    <tr>
      <td><?= $animal['animalID']; ?></td>
      <td><?= $animal['species_name']; ?></td>
      <td><?= $animal['breed_name']; ?></td>
      <td><?= $animal['sex']; ?></td>
      <td><?= $alterationStatus; ?></td>
      <td><?= $animal['microchip_id']; ?></td>
      <td><?= $animal['surrender_date']; ?></td>
    </tr>

  <?php endforeach; ?>
</table>
