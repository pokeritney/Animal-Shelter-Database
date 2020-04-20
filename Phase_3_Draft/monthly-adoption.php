<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Adoption.php';

session_start();

$db = new Database();
$conn = $db->getConn();

if (!isLoggedIn()) {

  die("unauthorised");
}

$adoptions = Adoption::monthlyAdoption($conn);

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

<h2>Monthly Adoption Report</h2>

<table width=60% class="table">
  <tr>
    <th>Month</th>
    <th>Species</th>
    <th>Breed</th>
    <th>Number of Surrenders</th>
    <th>Number of Adoptions</th>
  </tr>

  <?php foreach ($adoptions as $adoption) : ?>
    <tr>
      <td><?= $adoption['month_name']; ?></td>
      <td><?= $adoption['species_name']; ?></td>
      <td><?= $adoption['breed_name']; ?></td>
      <td><?= $adoption['surrender_count']; ?></td>
      <td><?= $adoption['adoption_count']; ?></td>
    </tr>

  <?php endforeach; ?>
</table>
