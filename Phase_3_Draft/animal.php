<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Animal.php';

session_start();

$db = new Database();
$conn = $db->getConn();

if ( ! isLoggedIn()) {

    die("unauthorised");

}


$speciesFilter = "%";
$statusFilter = "%";

$species = Animal::getCapacity($conn);
$animals = Animal::getAll($conn, $speciesFilter, $statusFilter);


if ($_SERVER["REQUEST_METHOD"] === "POST"){


  $speciesFilter = $_POST['speciesFilter'] ?? "%";
  $statusFilter = $_POST['statusFilter'] ?? "%";
  $animals = Animal::getAll($conn, $speciesFilter, $statusFilter);

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
    <a class="nav-link" href="add-application.php">Add Adoption Application</a>
  </li>
<?php if (isEmployee()): ?>

<?php endif; ?>
<?php if (isAdminUser()): ?>
  <li class="nav-item">
    <a class="nav-link" href="edit-application.php">Review Adoption Applications</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="view-reports.php">View Reports</a>
  </li>
<?php endif; ?>
</ul>



<h2>Animal Dashboard</h2>

<h3>Number of Available Spaces</h3>


<ul>
  <?php foreach ($species as $specie): ?>
      <?php
          if ($specie['inshelter']>0) {
            $numAvailable = $specie['capacity'] - $specie['inshelter'];
          }else $numAvailable = $specie['capacity'];

      ?>

      <li>
        <article>
          <h4>
            <?php if (isEmployee() and $numAvailable >0): ?>

                <a href="add-animal.php?species=<?= $specie['species_name']?>"><?= $specie['species_name']?> <?= $numAvailable ?></a>

            <?php else: ?>
              <?= $specie['species_name']?>
              <?= $numAvailable ?>
            <?php endif; ?>

          </h4>

        </article>
      </li>
    <?php endforeach; ?>

</ul>





<table>

<form method="post">
  <th>Filter by</th>
  <tr>
    <td>
      Species:
    </td>
    <td>
      Adoptability Status:
    </td>
  </tr>
  <tr>
    <td>
    <div>
    <input type = "radio" name="speciesFilter" value="%" <?php if ($speciesFilter == "%") {echo "checked";} ?>> All</br>
    <?php foreach ($species as $specie): ?>
    <input type = "radio" name="speciesFilter" value=<?= $specie['species_name']; ?>
    <?php if ($speciesFilter == $specie['species_name']) { echo "checked"; } ?> >   <?= $specie['species_name']; ?></br>
    <?php endforeach; ?>
    </div>
    </td>
<td>

    <div>
      <input type = "radio" name="statusFilter" value="%" <?php if ($statusFilter == "%") {echo "checked";} ?>> All</br>
      <input type = "radio" name="statusFilter" value="Adoptable" <?php if ($statusFilter == "Adoptable") {echo "checked";} ?>>Adoptable</br>
      <input type = "radio" name="statusFilter" value="Not Adoptable" <?php if ($statusFilter == "Not Adoptable") {echo "checked";} ?>>Not Adoptable</br>
    </div>
  </td>
  </tr>
<tr>
    <td><button>Filter</button></td>
</tr>

</table>


<table class="table">
  <colgroup span="6"></colgroup>
    <tr>
      <th>Name</th>
      <th>Species</td>
      <th>Breed</td>
      <th>Sex</th>
      <th>Alteration Status</th>
      <th>Age in Months</th>
      <th>Adoptability Status</th>
    </tr>

<?php foreach ($animals as $animal): ?>
      <?php
        if($animal['alteration_status']==TRUE){
          $alterationStatus="True";
        }else $alterationStatus="False";

      ?>
      <tr>
        <td><a href="animal-detail.php?id=<?= $animal['animalID']; ?>"><?= $animal['name']; ?></a></td>
        <td><?= $animal['species_name']; ?></td>
        <td><?= $animal['breed_name']; ?></td>
        <td><?= $animal['sex']; ?></td>
        <td><?= $alterationStatus ?></td>
        <td><?= $animal['age_months']; ?></td>
        <td><?= $animal['adoptability_status']; ?></td>
      </tr>

<?php endforeach; ?>
</table>

</form>


<?php require 'lib/footer.php'; ?>
